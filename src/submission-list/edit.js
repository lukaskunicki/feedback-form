import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

export default function Edit() {
	const blockProps = useBlockProps({
		className: 'wp-block-submission-list',
	});

	return (
		<div {...blockProps}>
			<div className="wp-block-submission-list__container">
				<div className="wp-block-submission-list__items">
					<div className="wp-block-submission-list__header">
						<span>{__('First Name', 'feedback-block')}</span>
						<span>{__('Last Name', 'feedback-block')}</span>
						<span>{__('Email', 'feedback-block')}</span>
						<span>{__('Subject', 'feedback-block')}</span>
					</div>
					<div className="wp-block-submission-list__item">
						<span>{__('John', 'feedback-block')}</span>
						<span>{__('Doe', 'feedback-block')}</span>
						<span>john@example.com</span>
						<span>{__('Sample Subject', 'feedback-block')}</span>
					</div>
					<div className="wp-block-submission-list__item">
						<span>{__('Jane', 'feedback-block')}</span>
						<span>{__('Smith', 'feedback-block')}</span>
						<span>jane@example.com</span>
						<span>{__('Another Subject', 'feedback-block')}</span>
					</div>
				</div>
				<div className="wp-block-submission-list__editor-info">
					{__(
						'This block displays a list of feedback submissions. Only administrators will be able to see the actual submissions on the frontend.',
						'feedback-block'
					)}
				</div>
			</div>
		</div>
	);
}
