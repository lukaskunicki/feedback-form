<?php

if (!current_user_can('manage_options')) {
    return sprintf(
        '<div class="wp-block-submission-list__unauthorized">%s</div>',
        esc_html__('You are not authorized to view the content of this page.', 'feedback-block')
    );
}

$wrapper_attributes = get_block_wrapper_attributes([
    'class' => 'wp-block-submission-list',
    'data-wp-interactive' => 'feedback-block/submission-list',
    'data-wp-init' => 'callbacks.initList',
    'data-wp-context' => wp_json_encode([
        'nonce' => wp_create_nonce('wp_rest')
    ]),
    'data-nonce' => wp_create_nonce('wp_rest')
]);
?>

<div <?php echo $wrapper_attributes; ?>>
    <div class="wp-block-submission-list__container">
        <div class="wp-block-submission-list__loading" data-wp-bind--hidden="!state.isLoading">
            <?php esc_html_e('Loading submissions...', 'feedback-block'); ?>
        </div>
        <div class="wp-block-submission-list__items" data-wp-bind--hidden="state.isLoading">
            <div data-wp-bind--hidden="!state.hasAnyEntries">
                <div class="wp-block-submission-list__header">
                    <span><?php esc_html_e('First Name', 'feedback-block'); ?></span>
                    <span><?php esc_html_e('Last Name', 'feedback-block'); ?></span>
                    <span><?php esc_html_e('Email', 'feedback-block'); ?></span>
                    <span><?php esc_html_e('Subject', 'feedback-block'); ?></span>
                </div>
                <template data-wp-each--submission="state.submissions">
                    <div class="wp-block-submission-list__item" 
                        data-wp-on--click="actions.toggleDetails"
                        data-wp-bind--data-id="context.submission.id"
                        data-wp-class--selected="state.selectedSubmission === context.submission.id">
                        <span data-wp-text="context.submission.first_name"></span>
                        <span data-wp-text="context.submission.last_name"></span>
                        <span data-wp-text="context.submission.email"></span>
                        <span data-wp-text="context.submission.subject"></span>
                    </div>
                    <div class="wp-block-submission-list__details" 
                         data-wp-bind--hidden="actions.checkIfExpanded">
                        <div class="wp-block-submission-list__detail-row">
                            <div class="wp-block-submission-list__loading-details" data-wp-bind--hidden="!state.isLoadingDetails">
                                <?php esc_html_e('Fetching details...', 'feedback-block'); ?>
                            </div>
                            <div data-wp-bind--hidden="state.isLoadingDetails">
                                <div class="wp-block-submission-list__detail-grid">
                                    <div class="wp-block-submission-list__detail-item">
                                        <strong><?php esc_html_e('First Name:', 'feedback-block'); ?></strong>
                                        <span data-wp-text="state.selectedSubmissionDetails.first_name"></span>
                                    </div>
                                    <div class="wp-block-submission-list__detail-item">
                                        <strong><?php esc_html_e('Last Name:', 'feedback-block'); ?></strong>
                                        <span data-wp-text="state.selectedSubmissionDetails.last_name"></span>
                                    </div>
                                    <div class="wp-block-submission-list__detail-item">
                                        <strong><?php esc_html_e('Email:', 'feedback-block'); ?></strong>
                                        <span data-wp-text="state.selectedSubmissionDetails.email"></span>
                                    </div>
                                    <div class="wp-block-submission-list__detail-item">
                                        <strong><?php esc_html_e('Subject:', 'feedback-block'); ?></strong>
                                        <span data-wp-text="state.selectedSubmissionDetails.subject"></span>
                                    </div>
                                    <div class="wp-block-submission-list__detail-item wp-block-submission-list__detail-item--full-width">
                                        <strong><?php esc_html_e('Message:', 'feedback-block'); ?></strong>
                                        <div class="wp-block-submission-list__message-content" data-wp-text="state.selectedSubmissionDetails.message"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            <div class="wp-block-submission-list__empty" data-wp-bind--hidden="state.hasAnyEntries">
                <?php esc_html_e('No submissions found.', 'feedback-block'); ?>
            </div>
        </div>

        <div class="wp-block-submission-list__pagination" data-wp-bind--hidden="!state.hasPagination">
            <template data-wp-each--page="state.pages">
                <button class="wp-block-submission-list__page-button"
                        data-wp-on--click="actions.loadSubmissions"
                        data-wp-bind--data-page="context.page"
                        data-wp-class--active="state.currentPage === context.page"
                        data-wp-text="context.page">
                </button>
            </template>
        </div> 
    </div>
</div>
