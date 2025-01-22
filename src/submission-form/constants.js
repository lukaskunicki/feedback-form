export const INITIAL_STATE = {
	submitting: false,
	success: false,
	error: '',
	firstName: '',
	lastName: '',
	email: '',
	subject: '',
	message: '',
};

export const API_ENDPOINTS = {
	SUBMIT: '/wp-json/feedback-block/v1/submit',
};

export const FORM_FIELDS = {
	FIRST_NAME: 'firstName',
	LAST_NAME: 'lastName',
	EMAIL: 'email',
	SUBJECT: 'subject',
	MESSAGE: 'message',
};
