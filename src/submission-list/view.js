import { getContext, store } from '@wordpress/interactivity';

import { API_ENDPOINTS, ERROR_MESSAGES, INITIAL_STATE, QUERY_PARAMS } from './constants';

const { state, actions } = store('feedback-block/submission-list', {
	state: {
		get currentPage() {
			return state.page || INITIAL_STATE.page;
		},
		get submissions() {
			return state.submissionsList || INITIAL_STATE.submissionsList;
		},
		get selectedSubmission() {
			return state.selected || INITIAL_STATE.selected;
		},
		get selectedSubmissionDetails() {
			return state.selectedDetails || INITIAL_STATE.selectedDetails;
		},
		get isLoading() {
			return state.loading || INITIAL_STATE.loading;
		},
		get isLoadingDetails() {
			return state.loadingDetails || INITIAL_STATE.loadingDetails;
		},
		get totalPages() {
			return state.total || INITIAL_STATE.total;
		},
		get hasAnyEntries() {
			return !!state.submissionsList?.length;
		},
		get hasPagination() {
			return state.totalPages !== 1;
		},
		get pages() {
			const total = state.total || INITIAL_STATE.total;
			return total > 1 ? Array.from({ length: total }, (_, i) => i + 1) : [];
		},
	},
	actions: {
		async loadSubmissions(event) {
			const targetPage = event?.target?.dataset?.page
				? parseInt(event.target.dataset.page, 10)
				: 1;

			if (targetPage === state.page) {
				return;
			}

			state.loading = true;
			state.page = targetPage;

			const context = getContext();
			if (context?.page) {
				state.page = context.page;
			}

			try {
				const nonce = context?.nonce || document.querySelector('[data-nonce]')?.dataset?.nonce;
				
				if (!nonce) {
					throw new Error('Security token not found');
				}

				const response = await fetch(
					`${API_ENDPOINTS.GET_ENTRIES}/?page=${state.page}&${QUERY_PARAMS.BASIC_FIELDS}`,
					{
						headers: {
							'X-WP-Nonce': nonce,
						},
					}
				);

				if (!response.ok) {
					throw new Error(ERROR_MESSAGES.LOAD_SUBMISSIONS);
				}

				const data = await response.json();
				state.submissionsList = data.entries;
				state.total = data.total_pages || INITIAL_STATE.total;
			} catch (error) {
				console.error('Error loading submissions:', error);
			} finally {
				state.loading = false;
			}
		},

		async toggleDetails() {
			const context = getContext();
			const submission = context.submission;
			const nonce = context?.nonce || document.querySelector('[data-nonce]')?.dataset?.nonce;
			
			if (!nonce) {
				console.error('Security token not found');
				return;
			}

			state.selectedDetails = null;

			if (state.selected === submission.id) {
				state.selected = null;
				return;
			}

			state.selected = submission.id;
			state.loadingDetails = true;

			try {
				const response = await fetch(API_ENDPOINTS.GET_ENTRY_DETAILS(submission.id), {
					headers: {
						'X-WP-Nonce': nonce,
					},
				});

				if (!response.ok) {
					throw new Error(ERROR_MESSAGES.LOAD_DETAILS);
				}

				state.selectedDetails = await response.json();
			} catch (error) {
				console.error('Error loading submission details:', error);
				this.resetSelection();
			} finally {
				state.loadingDetails = false;
			}
		},

		resetSelection() {
			state.selected = INITIAL_STATE.selected;
			state.selectedDetails = INITIAL_STATE.selectedDetails;
		},

		checkIfExpanded() {
			const context = getContext();
			return state.selected !== context.submission.id;
		},
	},
	callbacks: {
		initList() {
			actions.loadSubmissions();
		},
	},
});
