import Swal from "sweetalert2";
import got from 'got';


( function ( $ ) {
	class FutureWordPress_Frontend {
		/**
		 * Constructor
		 */
		constructor() {
			this.ajaxUrl = fwpSiteConfig?.ajaxUrl ?? '';
			this.ajaxNonce = fwpSiteConfig?.ajax_nonce ?? '';
			var i18n = fwpSiteConfig?.i18n ?? {};
			this.i18n = {
				confirm_cancel_subscribe	: 'Do you really want to cancel this Subscription?',
				i_confirm_it				: 'Yes I confirm it',
				confirming					: 'Confirming',
				submit						: 'Submit',
				request_failed				: 'Request failed',
				give_your_old_password		: 'Give here your old password',
				...i18n
			}
			this.init();this.toOpenEdit();this.inputEventListner();
			this.cancelSubscription();this.changePassword();
			// console.log( 'frontend init...' );
		}
		init() {
			const thisClass = this;var theInterval, selector;
			selector = '.fwp-tabs__navs';
			theInterval = setInterval( () => {
				document.querySelectorAll( selector + ':not([data-handled])' ).forEach( ( e, i ) => {
					e.dataset.handled = true;
					thisClass.tabs( e );
				} );
			}, 1000 );
		}
		tabs( e ) {
			e.querySelectorAll( '.fwp-tabs__nav-item' ).forEach( ( tabEl, tabI ) => {
				tabEl.addEventListener( 'click', function( event ) {
					if( this.dataset.target ) {
						e.querySelector( '.active' ).classList.remove( 'active' );
						this.classList.add( 'active' );
						document.querySelector( this.dataset.target ).parentElement.querySelector( '.active' ).classList.remove( 'active' );
						document.querySelector( this.dataset.target ).classList.add( 'active' );
					}
				} );
			} );
		}
		cancelSubscription() {
			const thisClass = this;var node;
			node = document.querySelector( '.fwp-sweetalert-field:not([data-handled])' );
			if( node ) {
				node.addEventListener( 'click', ( event ) => {
					  Swal.fire({
						title: 'Attantion!',
						text: thisClass.i18n.confirm_cancel_subscribe,
						icon: 'warning',
						confirmButtonText: thisClass.i18n.i_confirm_it,
						// input: 'text',
						// inputAttributes: {
						//   autocapitalize: 'off'
						// },
						showCancelButton: true,
						showLoaderOnConfirm: true,
						preConfirm: (login) => {
						  return fetch( thisClass.ajaxUrl + `?action=futurewordpress/project/action/cancelsubscription` )
							.then(response => {
							  if (!response.ok) {
								throw new Error(response.statusText)
							  }
							  return response.json()
							})
							.catch(error => {
							  Swal.showValidationMessage(
								thisClass.i18n.request_failed + `: ${error}`
							  )
							})
						},
						allowOutsideClick: () => !Swal.isLoading()
					  }).then((result) => {
						if (result.isConfirmed ) {
						  Swal.fire({
							// title: `${result.value.login}'s avatar`,
							// imageUrl: result.value.avatar_url: 
							title: ( result.value.success ) ? 'Success' : 'Failed',icon: ( result.value.success ) ? 'success' : 'error',
							text: result.value?.data??'Request sent but doesn\'t update anything.',
						  })
						}
					  })
				} );
			}
		}
		changePassword() {
			const thisClass = this;var node;
			node = document.querySelector( '#change-password-field' );
			if( node ) {
				node.addEventListener( 'change', ( event ) => {
					  Swal.fire({
						title: thisClass.i18n.confirming,
						text: thisClass.i18n.give_your_old_password,
						icon: 'warning',
						confirmButtonText: thisClass.i18n.submit,
						input: 'text',
						inputAttributes: {
						  autocapitalize: 'off'
						},
						showCancelButton: true,
						showLoaderOnConfirm: true,
						preConfirm: (login) => {
						  return fetch( thisClass.ajaxUrl + `?action=futurewordpress/project/action/changepassword` )
							.then(response => {
							  if (!response.ok) {
								throw new Error(response.statusText)
							  }
							  return response.json()
							})
							.catch(error => {
							  Swal.showValidationMessage(
								thisClass.i18n.request_failed + `: ${error}`
							  )
							})
						},
						allowOutsideClick: () => !Swal.isLoading()
					  }).then((result) => {
						if (result.isConfirmed ) {
						  Swal.fire({
							title: ( result.value.success ) ? 'Success' : 'Failed',icon: ( result.value.success ) ? 'success' : 'error',
							text: result.value?.data??'Request sent but doesn\'t update anything.',
						  })
						}
					  })
				} );
			}
		}
		toOpenEdit() {
			var i, el;
			document.querySelectorAll( '[id^=basic-editopen-]' ).forEach( ( pen ) => {
				pen.addEventListener( 'click', ( e ) => {
					el = pen.previousElementSibling;
					if( el.hasAttribute( 'disabled' ) ) {
						el.removeAttribute( 'disabled' );el.classList.remove( 'form-control-solid' );
						i = pen.querySelector( 'i' );
						if( i ) {i.classList.remove( 'fa-pencil-alt' );i.classList.add( 'fa-circle-notch' );}
						// fa-circle-notch | fa-times | afa-check | fa-spinner  | 'fa-spin'
					} else {}
				} );
			} );
		}
		inputEventListner() {
			var i, el;
			document.querySelectorAll( 'input' ).forEach( ( input ) => {
				input.addEventListener( 'change', ( e ) => {
					el = input.nextElementSibling;
					if( el && el.classList.contains( 'input-group-text' ) ) {
						// input.setAttribute( 'disabled' );
						i = el.querySelector( 'i' );
						if( i ) {i.classList.remove( 'fa-circle-notch' );i.classList.add( 'fa-spinner', 'fa-spin' );}
						//  | fa-times | afa-check |   | 
					} else {}
				} );
			} );
		}
	}
	new FutureWordPress_Frontend();
} )( ( typeof jQuery !== 'undefined' ) ? jQuery : false );
