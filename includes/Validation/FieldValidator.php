<?php

declare(strict_types=1);

namespace FeedbackBlock\Includes\Validation;

if (!defined('ABSPATH')) {
    exit;
}

use WP_Error;

/**
 * Field Validator Class
 *
 * Handles validation and sanitization of form fields.
 *
 * @package FeedbackBlock\Includes\Validation
 */
class FieldValidator implements FieldValidatorInterface {
    /**
     * Maximum allowed lengths for different field types.
     *
     * @var array<string, int>
     */
    private const MAX_LENGTHS = [
        'name' => 100,
        'email' => 100,
        'subject' => 200,
        'message' => 5000,
    ];

    /**
     * Validates a field value based on its type and options.
     *
     * @param string $value   The value to validate.
     * @param array  $options Validation options.
     *
     * @return bool|WP_Error True if valid, WP_Error if invalid.
     */
    public function validate(string $value, array $options = []): bool|WP_Error {
        $type = $options['type'] ?? '';
        $maxLength = $options['maxLength'] ?? self::MAX_LENGTHS[$type] ?? 0;

        if (empty($value)) {
            return new WP_Error(
                'empty_field',
                __('Field cannot be empty', 'feedback-block')
            );
        }

        if ($maxLength > 0 && mb_strlen($value) > $maxLength) {
            return new WP_Error(
                'field_too_long',
                sprintf(
                    __('Field exceeds maximum length of %d characters', 'feedback-block'),
                    $maxLength
                )
            );
        }

        return match($type) {
            'name' => $this->validateName($value),
            'email' => $this->validateEmail($value),
            default => true,
        };
    }

    /**
     * Sanitizes a field value.
     *
     * @param string $value The value to sanitize.
     *
     * @return string The sanitized value.
     */
    public function sanitize(string $value): string {
        return sanitize_text_field($value);
    }

    /**
     * Validates a name field.
     *
     * @param string $value The name to validate.
     *
     * @return bool|WP_Error True if valid, WP_Error if invalid.
     */
    private function validateName(string $value): bool|WP_Error {
        if (!preg_match('/^[\p{L}\s\'-]+$/u', $value)) {
            return new WP_Error(
                'invalid_name',
                __('Name can only contain letters, spaces, hyphens and apostrophes', 'feedback-block')
            );
        }
        return true;
    }

    /**
     * Validates an email field.
     *
     * @param string $value The email to validate.
     *
     * @return bool|WP_Error True if valid, WP_Error if invalid.
     */
    private function validateEmail(string $value): bool|WP_Error {
        if (!is_email($value)) {
            return new WP_Error(
                'invalid_email',
                __('Invalid email address', 'feedback-block')
            );
        }
        return true;
    }
}