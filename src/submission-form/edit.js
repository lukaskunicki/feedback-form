import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

export default function Edit() {
	const blockProps = useBlockProps();

	return (
		<div {...blockProps}>
			<div className="wp-block-feedback-form__content">
				<h2 className="wp-block-feedback-form__title">
					{__('Submission Form', 'feedback-block')}
				</h2>
				<div className="wp-block-feedback-form__form">
					<div className="wp-block-feedback-form__field">
						<label className="wp-block-feedback-form__label" htmlFor="firstName-editor">
							{__('First Name', 'feedback-block')} *
						</label>
						<input
							className="wp-block-feedback-form__input"
							type="text"
							id="firstName-editor"
							disabled
						/>
					</div>
					<div className="wp-block-feedback-form__field">
						<label className="wp-block-feedback-form__label" htmlFor="lastName-editor">
							{__('Last Name', 'feedback-block')} *
						</label>
						<input
							className="wp-block-feedback-form__input"
							type="text"
							id="lastName-editor"
							disabled
						/>
					</div>
					<div className="wp-block-feedback-form__field">
						<label className="wp-block-feedback-form__label" htmlFor="email-editor">
							{__('Email', 'feedback-block')} *
						</label>
						<input
							className="wp-block-feedback-form__input"
							type="email"
							id="email-editor"
							disabled
						/>
					</div>
					<div className="wp-block-feedback-form__field">
						<label className="wp-block-feedback-form__label" htmlFor="subject-editor">
							{__('Subject', 'feedback-block')} *
						</label>
						<input
							className="wp-block-feedback-form__input"
							type="text"
							id="subject-editor"
							disabled
						/>
					</div>
					<div className="wp-block-feedback-form__field">
						<label className="wp-block-feedback-form__label" htmlFor="message-editor">
							{__('Message', 'feedback-block')} *
						</label>
						<textarea
							className="wp-block-feedback-form__textarea"
							id="message-editor"
							disabled
						></textarea>
					</div>
					<div className="wp-block-feedback-form__actions">
						<button className="wp-block-feedback-form__submit" disabled>
							{__('Submit', 'feedback-block')}
						</button>
					</div>
				</div>
			</div>
		</div>
	);
}
