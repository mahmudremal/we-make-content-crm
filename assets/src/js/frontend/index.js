import Swal from "sweetalert2";
import { toast } from 'toast-notification-alert';


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
				you_paused					: 'Pause your Retainer',
				you_un_paused				: 'Your unpaused Retainer',
				...i18n
			}
			this.init();this.toOpenEdit();this.inputEventListner();
			this.cancelSubscription();this.changePassword();
			this.toggleStatus();this.passwordToggle();
			// this.accordion();
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
					const swalWithBootstrapButtons = Swal.mixin({
						customClass: {
							confirmButton			: 'btn btn-danger',
							cancelButton			: 'btn btn-primary'
						},
						buttonsStyling: false
					})
					swalWithBootstrapButtons.fire({
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
			const thisClass = this;var i, el;
			document.querySelectorAll( '[id^=basic-editopen-]' ).forEach( ( pen ) => {
				pen.addEventListener( 'click', ( e ) => {
					el = pen.previousElementSibling;
					if( el.hasAttribute( 'disabled' ) ) {
						el.removeAttribute( 'disabled' );el.classList.remove( 'form-control-solid' );
						i = pen.querySelector( 'i' );if( i ) {i.classList.remove( 'fa-pencil-alt' );i.classList.add( 'fa-circle-notch' );}
						if( el.parentElement ) {el.parentElement.classList.remove( 'input-group-solid' );}
						// fa-circle-notch | fa-times | afa-check | fa-spinner  | 'fa-spin'
					} else {}
				} );
			} );
		}
		inputEventListner() {
			const thisClass = this;var i, el;
			document.querySelectorAll( 'input' ).forEach( ( input ) => {
				input.addEventListener( 'change', ( e ) => {
					el = input.nextElementSibling;
					// console.log( [ el, input, this ] );
					if( el && el.classList.contains( 'input-group-text' ) ) {
						// input.setAttribute( 'disabled' );
						i = el.querySelector( 'i' );
						if( i ) {i.classList.remove( 'fa-circle-notch' );i.classList.add( 'fa-spinner', 'fa-spin' );}
						//  | fa-times | afa-check |   | 
						var formdata = new FormData();
						formdata.append( 'action', 'futurewordpress/project/action/singlefield' );
						formdata.append( 'field', input.name );
						formdata.append( 'value', input.value );
						formdata.append( '_nonce', thisClass.ajaxNonce );
						thisClass.sendToServer( formdata );
					} else {}
				} );
			} );
		}
		toggleStatus() {
			const thisClass = this;
			document.querySelectorAll( '.fwp-form-checkbox-pause-subscribe' ).forEach( ( el, ei ) => {
				el.addEventListener( 'change', ( event ) => {
					var formdata = new FormData();
						formdata.append( 'action', 'futurewordpress/project/action/singlefield' );
						formdata.append( 'field', el.name );
						formdata.append( 'value', el.value );
						formdata.append( '_nonce', thisClass.ajaxNonce );
						// thisClass.sendToServer( formdata );
					// toast.show({title: ( el.checked ) ? thisClass.i18n.you_paused : thisClass.i18n.you_un_paused, position: 'topright', type: ( el.checked ) ? 'info' : 'alert' });
					Swal.fire( { position: 'top-end', icon: 'success', title: ( el.checked ) ? thisClass.i18n.you_paused : thisClass.i18n.you_un_paused, showConfirmButton: false, timer: 1500 } );
				} );
			} );
		}
		accordion() {
			document.querySelectorAll( '.accordion' ).forEach( ( al, ai ) => {
				al.querySelectorAll( '.accordion-header' ).forEach( ( el, ei ) => {
						el.addEventListener( 'toggle', ( event ) => {
								let node = document.querySelector( el.dataset.bsTarget );
								if( node ) {
										node.classList.toggle( 'show' );
								}
						} );
				} );
			} );
		
		}
		datatable() {
			const thisClass = this;
			document.querySelectorAll( '#kt_datatable_content_library' ).forEach( ( el, ei ) => {
				$( el ).DataTable( {
					ajax: thisClass.ajaxUrl + '?action=futurewordpress/project/database/contents&_nonce=' + thisClass.ajaxNonce
				} );
			} );
		
		}
		passwordToggle() {
			document.querySelectorAll( '.input-group-text.password-toggle' ).forEach( ( el, ei ) => {
				el.addEventListener( 'click', ( event ) => {
						el.classList.toggle( 'showing' );
						ei = el.nextElementSibling;
						if( ei ) {
								ei.type = ( ei.type == 'text' ) ? 'password' : 'text';
						}
				} );
			} );
		
		}
		sendToServer( data ) {
			const thisClass = this;var message;
			$.ajax({
				url: thisClass.ajaxUrl,
				type: "POST",
				data: data,    
				cache: false,
				contentType: false,
				processData: false,
				success: function( json ) {
					// console.log( json );
					message = ( json.data.message ) ? json.data.message : json.data;
					if( json.success ) {
						toast.show({title: message, position: 'bottomright', type: 'info'});
					} else {
						toast.show({title: message, position: 'bottomright', type: 'warn'});
					}
				},
				error: function( err ) {
					console.log( err.responseText );
				}
			});
		}
	}
	new FutureWordPress_Frontend();
} )( jQuery );
