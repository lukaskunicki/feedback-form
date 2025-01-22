export const INITIAL_STATE = {
	page: 1,
	submissionsList: [],
	selected: null,
	selectedDetails: null,
	loading: false,
	loadingDetails: false,
	total: 1,
};

export const API_ENDPOINTS = {
	GET_ENTRIES: '/wp-json/feedback-block/v1/entries',
	GET_ENTRY_DETAILS: (id) => `/wp-json/feedback-block/v1/entries/${id}`,
};

export const QUERY_PARAMS = {
	BASIC_FIELDS: 'fields=basic',
};

export const ERROR_MESSAGES = {
	LOAD_SUBMISSIONS: 'Failed to load submissions',
	LOAD_DETAILS: 'Failed to load submission details',
};
