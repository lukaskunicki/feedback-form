<?php
/**
 * Class to handle feedback submissions
 *
 * @package feedback-block
 */

namespace FeedbackBlock\Includes;

if (!defined('ABSPATH')) {
    exit;
}

use FeedbackBlock\Includes\Validation\FieldValidator;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;

/**
 * Handles feedback submissions and REST API endpoints
 *
 * @package FeedbackBlock\Includes
 */
class FeedbackSubmissions {
    /**
     * Table name for submissions
     *
     * @var string
     */
    private string $table_name;

    /**
     * Field validator instance
     *
     * @var FieldValidator
     */
    private FieldValidator $validator;

    /**
     * Constructor
     */
    public function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'feedback_submissions';
        $this->validator = new FieldValidator();

        add_action('rest_api_init', [$this, 'registerEndpoints']);
    }

    /**
     * Register REST API endpoints
     *
     * @return void
     */
    public function registerEndpoints(): void {
        register_rest_route('feedback-block/v1', '/submit', [
            'methods' => 'POST',
            'callback' => [$this, 'handleSubmission'],
            'permission_callback' => '__return_true',
        ]);

        register_rest_route('feedback-block/v1', '/entries', [
            'methods' => 'GET',
            'callback' => [$this, 'getEntries'],
            'permission_callback' => [$this, 'checkAdminPermission'],
        ]);

        register_rest_route('feedback-block/v1', '/entries/(?P<id>\d+)', [
            'methods' => 'GET',
            'callback' => [$this, 'getEntry'],
            'permission_callback' => [$this, 'checkAdminPermission'],
        ]);
    }

    /**
     * Handle form submission
     *
     * @param WP_REST_Request $request Request object
     * @return WP_REST_Response|WP_Error Response object or error
     */
    public function handleSubmission(WP_REST_Request $request): WP_REST_Response|WP_Error {
        if (!wp_verify_nonce($request->get_header('X-WP-Nonce'), 'wp_rest')) {
            return new WP_Error(
                'invalid_nonce',
                __('Invalid security token', 'feedback-block'),
                ['status' => 403]
            );
        }

        $params = $request->get_params();
        $required_fields = ['firstName', 'lastName', 'email', 'subject', 'message'];

        foreach ($required_fields as $field) {
            if (empty($params[$field])) {
                return new WP_Error(
                    'missing_field',
                    sprintf(__('Field %s is required', 'feedback-block'), $field),
                    ['status' => 400]
                );
            }
        }

        $validation_result = $this->validateFields($params);
        if (is_wp_error($validation_result)) {
            return $validation_result;
        }

        global $wpdb;
        $result = $wpdb->insert(
            $this->table_name,
            [
                'first_name' => $this->validator->sanitize($params['firstName']),
                'last_name' => $this->validator->sanitize($params['lastName']),
                'email' => $this->validator->sanitize($params['email']),
                'subject' => $this->validator->sanitize($params['subject']),
                'message' => $this->validator->sanitize($params['message']),
            ],
            ['%s', '%s', '%s', '%s', '%s']
        );

        if (!$result) {
            return new WP_Error(
                'db_error',
                __('Failed to save submission', 'feedback-block'),
                ['status' => 500]
            );
        }

        return new WP_REST_Response(['message' => __('Submission saved successfully', 'feedback-block')], 201);
    }

    /**
     * Get paginated entries
     *
     * @param WP_REST_Request $request Request object
     * @return WP_REST_Response Response object
     */
    public function getEntries(WP_REST_Request $request): WP_REST_Response {
        global $wpdb;

        $page = (int) ($request->get_param('page') ?? 1);
        $per_page = 10;
        $offset = ($page - 1) * $per_page;

        $fields = $request->get_param('fields') === 'basic' 
            ? 'id, first_name, last_name, email, subject' 
            : '*';

        $total = (int) $wpdb->get_var("SELECT COUNT(id) FROM {$this->table_name}");
        $total_pages = ceil($total / $per_page);

        $entries = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT {$fields} FROM {$this->table_name} ORDER BY created_at DESC LIMIT %d OFFSET %d",
                $per_page,
                $offset
            )
        );

        return new WP_REST_Response([
            'entries' => $entries,
            'total_pages' => $total_pages,
        ]);
    }

    /**
     * Get single entry
     *
     * @param WP_REST_Request $request Request object
     * @return WP_REST_Response|WP_Error Response object or error
     */
    public function getEntry(WP_REST_Request $request): WP_REST_Response|WP_Error {
        global $wpdb;

        $id = (int) $request->get_param('id');
        $entry = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM {$this->table_name} WHERE id = %d",
                $id
            )
        );

        if (!$entry) {
            return new WP_Error(
                'not_found',
                __('Entry not found', 'feedback-block'),
                ['status' => 404]
            );
        }

        return new WP_REST_Response($entry);
    }

    /**
     * Check if user has admin permission
     *
     * @return bool Whether user has permission
     */
    public function checkAdminPermission(): bool {
        return current_user_can('manage_options');
    }

    /**
     * Validate submission fields
     *
     * @param array $fields Fields to validate
     * @return true|WP_Error True if valid, WP_Error if invalid
     */
    private function validateFields(array $fields): true|WP_Error {
        $name_result = $this->validator->validate($fields['firstName'], ['type' => 'name']);
        if (is_wp_error($name_result)) {
            return new WP_Error(
                'invalid_first_name',
                $name_result->get_error_message(),
                ['status' => 400]
            );
        }

        $name_result = $this->validator->validate($fields['lastName'], ['type' => 'name']);
        if (is_wp_error($name_result)) {
            return new WP_Error(
                'invalid_last_name',
                $name_result->get_error_message(),
                ['status' => 400]
            );
        }

        $email_result = $this->validator->validate($fields['email'], ['type' => 'email']);
        if (is_wp_error($email_result)) {
            return new WP_Error(
                'invalid_email',
                $email_result->get_error_message(),
                ['status' => 400]
            );
        }

        $subject_result = $this->validator->validate($fields['subject']);
        if (is_wp_error($subject_result)) {
            return new WP_Error(
                'invalid_subject',
                $subject_result->get_error_message(),
                ['status' => 400]
            );
        }

        $message_result = $this->validator->validate($fields['message']);
        if (is_wp_error($message_result)) {
            return new WP_Error(
                'invalid_message',
                $message_result->get_error_message(),
                ['status' => 400]
            );
        }

        return true;
    }
} 
