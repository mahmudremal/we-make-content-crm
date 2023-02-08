import Swal from "sweetalert2";
import { toast } from 'toast-notification-alert';
import validator from 'validator';

// const stripe = require('stripe')('your_stripe_api_key');
// import stripe from 'stripe';
// import {loadStripe} from '@stripe/stripe-js';
// const stripe = await loadStripe('pk_test_TYooMQauvdEDq54NiTphI7jx');

/**
 * import Stripe from 'stripe';
const stripe = new Stripe('sk_test_...');

const customer = await stripe.customers.create({
  email: 'customer@example.com',
});

console.log(customer.id);
 */



( function ( $ ) {
	class FutureWordPress_Frontend {
		/**
		 * Constructor
		 */
		constructor() {
			this.ajaxUrl = fwpSiteConfig?.ajaxUrl ?? '';
			this.ajaxNonce = fwpSiteConfig?.ajax_nonce ?? '';
			this.lastAjax	 = false;
			var i18n = fwpSiteConfig?.i18n ?? {};
			this.i18n = {
				confirm_cancel_subscribe	: 'Do you really want to cancel this Subscription?',
				i_confirm_it							: 'Yes I confirm it',
				confirming								: 'Confirming',
				successful								: 'Successful',
				submit										: 'Submit',
				request_failed						: 'Request failed',
				give_your_old_password		: 'Give here your old password',
				you_paused								: 'Pause your Retainer',
				you_un_paused							: 'Your unpaused Retainer',
				sometextfieldmissing			: 'Some required field you missed. Pleae fillup them first, then we can proceed.',
				rqstrongpass							: 'Strong password required',
				renumber									: 'Only field allowed number only. Please recheck.',
				rqemail										: 'You provide a wrong email address. Please fix.',
				passnotmatched						: 'Password not matched',
				...i18n
			}
			this.init();this.toOpenEdit();this.inputEventListner();
			this.cancelSubscription();this.changePassword();
			this.toggleStatus();this.passwordToggle();
			this.regWidget();
			// this.fetchDataWidthContract();
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
			const thisClass = this;var i, el, userid;
			userid = document.querySelector( 'input[type="hidden"][name="userid"]' );
			if( userid ) {userid = userid.value;} else {userid = false;}
			document.querySelectorAll( 'input' ).forEach( ( input ) => {
				input.addEventListener( 'change', ( e ) => {
					el = input.nextElementSibling;
					// console.log( [ el, input, this ] );
					if( el && el.classList.contains( 'input-group-text' ) ) {
						// input.setAttribute( 'disabled' );
						i = el.querySelector( 'i' );
						if( i ) {i.classList.remove( 'fa-circle-notch' );i.classList.add( 'fa-spinner', 'fa-spin' );}
						//  | fa-times | fa-check |   | 
						var formdata = new FormData();
						formdata.append( 'action', 'futurewordpress/project/action/singlefield' );
						formdata.append( 'field', input.name );
						formdata.append( 'value', input.value );
						formdata.append( 'userid', userid );
						formdata.append( '_nonce', thisClass.ajaxNonce );
						thisClass.sendToServer( formdata, (i)?i:false );
					}
				} );
			} );
		}
		toggleStatus() {
			const thisClass = this;var userid;
			userid = document.querySelector( 'input[type="hidden"][name="userid"]' );
			if( userid ) {userid = userid.value;} else {userid = false;}
			document.querySelectorAll( '.fwp-form-checkbox-pause-subscribe' ).forEach( ( el, ei ) => {
				document.body.addEventListener( 'subscription-status-on subscription-status-off', () => {
					Swal.fire( { position: 'top-end', icon: 'success', title: ( el.checked ) ? thisClass.i18n.you_paused : thisClass.i18n.you_un_paused, showConfirmButton: false, timer: 3500 } );
				} );
				
				el.addEventListener( 'change', ( event ) => {
					var formdata = new FormData();
						formdata.append( 'action', 'futurewordpress/project/action/singlefield' );
						formdata.append( 'field', el.name );
						formdata.append( 'value', ( el.checked ) ? 'on' : 'off' );
						formdata.append( 'userid', userid );
						formdata.append( '_nonce', thisClass.ajaxNonce );
						thisClass.sendToServer( formdata );
						// toast.show({title: ( el.checked ) ? thisClass.i18n.you_paused : thisClass.i18n.you_un_paused, position: 'topright', type: ( el.checked ) ? 'info' : 'alert' });
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
		fetchDataWidthContract() {
			document.querySelectorAll( '.document-sign-page *' ).forEach( ( e ) => {
				var replacer = {
						'client_name': 'Remal Mahmud',
						'client_address': 'Bangladesh',
						'todays_date': '20 Dec 2022',
						'retainer_amount': '280$',
				};
				Object.keys( replacer ).forEach(function( lsi ) {
						e.textContent = e.textContent.replace( '{{' + lsi + '}}', replacer[ lsi ] );
				} );
			} );
		}
		sendToServer( data, i = false ) {
			const thisClass = this;var message;
			$.ajax({
				url: thisClass.ajaxUrl,
				type: "POST",
				data: data,    
				cache: false,
				contentType: false,
				processData: false,
				success: function( json ) {
					thisClass.lastAjax = json;
					message = ( json.data.message ) ? json.data.message : json.data;
					if( json.success ) {
						toast.show({title: message, position: 'bottomright', type: 'info'});
						if( i ) {i.classList.remove( 'fa-spinner', 'fa-spin' );i.classList.add( 'fa-check' );}
					} else {
						toast.show({title: message, position: 'bottomright', type: 'warn'});
						if( i ) {i.classList.remove( 'fa-spinner', 'fa-spin' );i.classList.add( 'fa-times' );}
					}
					if( json.data.hooks ) {
						json.data.hooks.forEach( ( hook ) => {
							document.body.dispatchEvent( new Event( hook ) );
						} );
					}
				},
				error: function( err ) {
					console.log( err.responseText );toast.show({title: err.responseText, position: 'bottomright', type: 'alert'});
					if( i ) {i.classList.remove( 'fa-spinner', 'fa-spin' );i.classList.add( 'fa-times' );}
				}
			});
		}
		addEventListener( elem, event ) {
			
			elem.dispatchEvent( new Event( 'fuck' ) )
		}
		regWidget() {
			const thisClass = this;var currentTab = 0, isNotOkay, tab, wrap, widzard = document.querySelector( '#register-existing-account-wizard' );
			if( ! widzard ) {return;}this.prevImage();this.submitWidget( widzard );
			var rendWidget = function( done ) {
				tab = widzard.querySelector( 'fieldset.active' );tab.classList.remove( 'active' );
				if( done && currentTab >= 1 ) {widzard.querySelectorAll( '#top-tab-list li' )[(currentTab-1)].classList.add( 'done' );}
				widzard.querySelectorAll( 'fieldset' )[currentTab].classList.add( 'active' );
				tab = widzard.querySelectorAll( '#top-tab-list li' )[currentTab];tab.classList.add( 'active' );
			}
			document.querySelectorAll( 'button[name="next"]' ).forEach( ( al, ai ) => {
				al.addEventListener( 'click', ( e ) => {isNotOkay = false;
					if( e.target.previousElementSibling ) {
						wrap = e.target.previousElementSibling;
						wrap.querySelectorAll( 'input[required]' ).forEach( ( input ) => {
							switch( input.type ) {
								case 'email' :
									if( ! validator.isEmail( input.value ) ) {isNotOkay = thisClass.i18n.rqemail;thisClass.handleError( input );}
									break;
								case 'text' :
									if( validator.isEmpty( input.value ) ) {isNotOkay = thisClass.i18n.sometextfieldmissing;thisClass.handleError( input );}
									break;
								case 'number' :
									if( ! validator.isNumeric( input.value ) ) {isNotOkay = thisClass.i18n.renumber;thisClass.handleError( input );}
									break;
								case 'password' :
									if( ! validator.isStrongPassword( input.value ) ) {isNotOkay = thisClass.i18n.rqstrongpass;thisClass.handleError( input );}
									if( wrap.querySelector( '#password-field-1' ) && wrap.querySelector( '#password-field-2' ) && wrap.querySelector( '#password-field-1' ).value != wrap.querySelector( '#password-field-2' ).value ) {isNotOkay = thisClass.i18n.passnotmatched;}
									break;
								default:
									break;
							}
						} );
					}
					if( isNotOkay === false ) {
						currentTab = ( currentTab + 1 );rendWidget( true );
					} else {
						toast.show({title: isNotOkay, position: 'bottomright', type: 'warn'});
					}
				} );
			} );
			document.querySelectorAll( 'button[name="previous"]' ).forEach( ( al, ai ) => {
				al.addEventListener( 'click', ( e ) => {
					currentTab = ( currentTab - 1 );rendWidget( false );
				} );
			} );
		}
		handleError( e ) {
			e.classList.add( 'border-danger' );
		}
		prevImage() {
			var preview, image, reader, file, input;
			image = document.querySelector( '.profile-image-preview' );
			input = document.querySelector( '[name="profile-image"]' );
			if( input && image ) {
				input.addEventListener( 'change', ( e ) => {
					preview = document.getElementById("preview");
					file = input.files[0];
					reader = new FileReader();
					reader.onloadend = function () {
						preview.src = reader.result;
						image.classList.add( 'active' );
					}
					if (file) {
						reader.readAsDataURL(file);
					} else {
						preview.src = "";
						image.classList.remove( 'remove' );
					}
				} );
			}
		}
		submitWidget( widzard ) {
			const thisClass = this;var el, message;
			document.body.addEventListener( 'register-existing-account-wizard-success', ( event ) => {
				el = document.querySelector( '#avaters' );if( el ) {el.classList.toggle( 'active', 'done' );}
				el = document.querySelector( '#confirm' );if( el ) {el.classList.add( 'active', 'done' );}
				el = document.querySelectorAll( '#register-existing-account-wizard fieldset' );if( el[3] ) {el[2].classList.remove( 'active' );el[3].classList.add( 'active' );}
				if( thisClass.lastAjax && thisClass.lastAjax.data.redirect ) {
					location.href = thisClass.lastAjax.data.redirect;
				} else {
					Swal.fire({
						title: thisClass.i18n.successful,
						text: ( thisClass.lastAjax.data.message ) ? thisClass.lastAjax.data.message : thisClass.lastAjax.data,
					})
				}
			} );
			widzard.addEventListener( 'submit', ( event ) => {
				event.preventDefault();
				var formdata = new FormData( event.target );
				thisClass.sendToServer( formdata );
			} );
		}
		tryHttpXhr() {
			var params = '', form = event.target, data = new FormData( form );
			for (var [key, value] of data.entries()) {params += encodeURIComponent(key) + "=" + encodeURIComponent(value) + "&";}
			params = params.slice(0, -1);

			var xhr = new XMLHttpRequest();
			xhr.open( "POST", thisClass.ajaxUrl + "?" + params, true );
			xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			xhr.upload.addEventListener("progress", function(e) {
				if (e.lengthComputable) {
					var percentComplete = (e.loaded / e.total) * 100;
					// document.getElementById("progressBar").value = percentComplete;
				}
			});
			xhr.onreadystatechange = function() {
				if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
					console.log(this.responseText);
				}
			};
			xhr.send(  ); // new FormData( event.target )
		}
		handleStripeBtn() {
			this.stripePublishaleKey = 'sk_test_51MYvdBI8VOGXMyoFiYpojuTUhvmS1Cxwhke4QK6jfJopnRN4fT8Qq6sy2Rmf2uvyHBtbafFpWVqIHBFoZcHp0vqq00HaOBUh1P';
			const thisClass = this;var el, widget;
			if( typeof Stripe !=='undefined' ) {return;}
			widget = document.querySelector( '.pay_retainer-amount' );
			if( ! widget ) {return;}
			widget.addEventListener( 'click', async ( event ) => {
				event.preventDefault();
				const stripe = new Stripe( thisClass.stripePublishaleKey );

				thisClass.stripe = await loadStripe(  thisClass.stripePublishaleKey );
				window.stripe = thisClass.stripe;
				var customer = await thisClass.stripe.customers.create({
					email: 'customer@example.com',
				});
				
				console.log(customer.id);
			} );
		}
	}
	new FutureWordPress_Frontend();
} )( jQuery );
