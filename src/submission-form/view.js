import { getContext, store } from '@wordpress/interactivity';

import { API_ENDPOINTS, FORM_FIELDS, INITIAL_STATE } from './constants';

const { state } = store('feedback-block/submission-form', {
	state: {
		get isSubmitting() {
			return state.submitting || INITIAL_STATE.submitting;
		},
		get submitSuccess() {
			return state.success || INITIAL_STATE.success;
		},
		get formError() {
			return state.error || INITIAL_STATE.error;
		},
		get formData() {
			return {
				[FORM_FIELDS.FIRST_NAME]:
					state[FORM_FIELDS.FIRST_NAME] || INITIAL_STATE[FORM_FIELDS.FIRST_NAME],
				[FORM_FIELDS.LAST_NAME]:
					state[FORM_FIELDS.LAST_NAME] || INITIAL_STATE[FORM_FIELDS.LAST_NAME],
				[FORM_FIELDS.EMAIL]: state[FORM_FIELDS.EMAIL] || INITIAL_STATE[FORM_FIELDS.EMAIL],
				[FORM_FIELDS.SUBJECT]:
					state[FORM_FIELDS.SUBJECT] || INITIAL_STATE[FORM_FIELDS.SUBJECT],
				[FORM_FIELDS.MESSAGE]:
					state[FORM_FIELDS.MESSAGE] || INITIAL_STATE[FORM_FIELDS.MESSAGE],
			};
		},
	},
	actions: {
		async submitForm(event) {
			event.preventDefault();

			if (state.isSubmitting) {
				return;
			}

			state.submitting = true;
			state.error = '';

			const context = getContext();
			const nonce = context?.nonce || document.querySelector('[data-nonce]')?.dataset?.nonce;

			if (!nonce) {
				state.error = 'Security token not found';
				state.submitting = false;
				return;
			}

			const form = event.target;
			const formData = new FormData(form);

			try {
				const response = await fetch(API_ENDPOINTS.SUBMIT, {
					method: 'POST',
					headers: {
						'X-WP-Nonce': nonce,
					},
					body: formData,
				});

				const data = await response.json();

				if (!response.ok) {
					throw new Error(data.message || 'Submission failed');
				}

				state.success = true;
				this.resetForm();
			} catch (error) {
				state.error = error.message;
			} finally {
				state.submitting = false;
			}
		},

		updateField(event) {
			const { name, value } = event.target;
			state[name] = value;
		},

		resetForm() {
			Object.keys(FORM_FIELDS).forEach((key) => {
				state[FORM_FIELDS[key]] = INITIAL_STATE[FORM_FIELDS[key]];
			});
		},
	},
	callbacks: {
		initForm() {
			const context = getContext();
			const userData = context?.userData;

			if (!userData) {
				return;
			}

			state[FORM_FIELDS.FIRST_NAME] =
				userData[FORM_FIELDS.FIRST_NAME] || INITIAL_STATE[FORM_FIELDS.FIRST_NAME];
			state[FORM_FIELDS.LAST_NAME] =
				userData[FORM_FIELDS.LAST_NAME] || INITIAL_STATE[FORM_FIELDS.LAST_NAME];
			state[FORM_FIELDS.EMAIL] =
				userData[FORM_FIELDS.EMAIL] || INITIAL_STATE[FORM_FIELDS.EMAIL];
		},
	},
});
