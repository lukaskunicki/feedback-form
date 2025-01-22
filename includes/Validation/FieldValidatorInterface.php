<?php

declare(strict_types=1);

namespace FeedbackBlock\Includes\Validation;

if (!defined('ABSPATH')) {
    exit;
}

use WP_Error;

/**
 * Field Validator Interface
 *
 * Interface for field validation and sanitization.
 *
 * @package FeedbackBlock\Includes\Validation
 */
interface FieldValidatorInterface {
    /**
     * Validates a field value.
     *
     * @param string $value   The value to validate.
     * @param array  $options Validation options.
     *
     * @return bool|WP_Error True if valid, WP_Error if invalid.
     */
    public function validate(string $value, array $options = []): bool|WP_Error;

    /**
     * Sanitizes a field value.
     *
     * @param string $value The value to sanitize.
     *
     * @return string The sanitized value.
     */
    public function sanitize(string $value): string;
} 