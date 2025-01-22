<?php

$wrapper_attributes = get_block_wrapper_attributes([
	'class' => 'wp-block-feedback-form',
	'data-wp-interactive' => 'feedback-block/submission-form',
	'data-wp-init' => 'callbacks.initForm',
	'data-wp-context' => wp_json_encode([
		'userData' => is_user_logged_in() ? [
			'firstName' => wp_get_current_user()->first_name,
			'lastName' => wp_get_current_user()->last_name,
			'email' => wp_get_current_user()->user_email,
		] : null,
		'nonce' => wp_create_nonce('wp_rest')
	]),
	'data-nonce' => wp_create_nonce('wp_rest')
]);

?>
<div <?php echo $wrapper_attributes; ?>>
	<div class="wp-block-feedback-form__content" data-wp-bind--hidden="state.submitSuccess">
		<h2 class="wp-block-feedback-form__title"><?php esc_html_e('Submit your feedback', 'feedback-block'); ?></h2>
		<form class="wp-block-feedback-form__form" data-wp-on--submit="actions.submitForm">
			<div class="wp-block-feedback-form__field">
				<label class="wp-block-feedback-form__label" for="firstName"><?php esc_html_e('First Name', 'feedback-block'); ?> *</label>
				<input 
					class="wp-block-feedback-form__input"
					type="text" 
					id="firstName" 
					name="firstName" 
					required 
					data-wp-on--input="actions.updateField"
					data-wp-bind--value="state.firstName"
				>
			</div>

			<div class="wp-block-feedback-form__field">
				<label class="wp-block-feedback-form__label" for="lastName"><?php esc_html_e('Last Name', 'feedback-block'); ?> *</label>
				<input 
					class="wp-block-feedback-form__input"
					type="text" 
					id="lastName" 
					name="lastName" 
					required
					data-wp-on--input="actions.updateField"
					data-wp-bind--value="state.lastName"
				>
			</div>

			<div class="wp-block-feedback-form__field">
				<label class="wp-block-feedback-form__label" for="email"><?php esc_html_e('Email', 'feedback-block'); ?> *</label>
				<input 
					class="wp-block-feedback-form__input"
					type="email" 
					id="email" 
					name="email" 
					required
					data-wp-on--input="actions.updateField"
					data-wp-bind--value="state.email"
				>
			</div>

			<div class="wp-block-feedback-form__field">
				<label class="wp-block-feedback-form__label" for="subject"><?php esc_html_e('Subject', 'feedback-block'); ?> *</label>
				<input 
					class="wp-block-feedback-form__input"
					type="text" 
					id="subject" 
					name="subject" 
					required
					data-wp-on--input="actions.updateField"
					data-wp-bind--value="state.subject"
				>
			</div>

			<div class="wp-block-feedback-form__field">
				<label class="wp-block-feedback-form__label" for="message"><?php esc_html_e('Message', 'feedback-block'); ?> *</label>
				<textarea 
					class="wp-block-feedback-form__textarea"
					id="message" 
					name="message" 
					required
					data-wp-on--input="actions.updateField"
					data-wp-bind--value="state.message"
				></textarea>
			</div>

			<div class="wp-block-feedback-form__actions">
				<button 
					class="wp-block-feedback-form__submit"
					type="submit" 
					data-wp-bind--disabled="state.isSubmitting"
				>
					<?php esc_html_e('Submit', 'feedback-block'); ?>
				</button>
			</div>

			<div 
				class="wp-block-feedback-form__error" 
				data-wp-bind--hidden="!state.formError"
			>
				<p class="wp-block-feedback-form__error-text" data-wp-text="state.formError"></p>
			</div>
		</form>
	</div>

	<div 
		class="wp-block-feedback-form__success" 
		data-wp-bind--hidden="!state.submitSuccess"
	>
		<p class="wp-block-feedback-form__success-text"><?php esc_html_e('Thank you for sending us your feedback', 'feedback-block'); ?></p>
	</div>
</div>
