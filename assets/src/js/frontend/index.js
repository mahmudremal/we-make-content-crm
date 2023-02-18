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
			this.lastAjax	 = false;this.profile	 = fwpSiteConfig?.profile ?? false;
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
				are_u_sure								: 'Are you sure?',
				...i18n
			}
			this.init();this.toOpenEdit();this.inputEventListner();
			this.cancelSubscription();this.changePassword();
			this.toggleStatus();this.passwordToggle();
			this.regWidget();this.handleTabHref();
			this.submitArchiveFiles();this.trackWpformAjax();
			this.setup_hooks();this.deleteArchive();
			this.handlePayCardError();
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
			const thisClass = this;var node, theInterval;
			theInterval = setInterval(() => {
				node = document.querySelector( '.fwp-sweetalert-field:not([data-handled])' );
				if( node ) {
					node.dataset.handled = true;
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
								return fetch( thisClass.ajaxUrl + `?action=futurewordpress/project/action/cancelsubscription&userid=` + node.dataset.userid )
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
								}).then( () => {
									location.reload();
								} )
							}
							})
					} );
				}
			}, 1000 );
				
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
			const thisClass = this;var i, el, theInterval;
			theInterval = setInterval(() => {
				document.querySelectorAll( '[id^=basic-editopen-]:not([data-handled])' ).forEach( ( pen ) => {
					pen.dataset.handled = true;
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
			}, 1000 );
		}
		inputEventListner() {
			const thisClass = this;var i, el, userid, theInterval;
			theInterval = setInterval(() => {
				userid = document.querySelector( 'input[type="hidden"][name="userid"]:not([data-handled])' );
				if( userid ) {userid.dataset.handled = true;userid = userid.value;} else {userid = false;}
				document.querySelectorAll( 'input:not([data-handled])' ).forEach( ( input ) => {
					input.dataset.handled = true;
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
			}, 1000 );
		}
		toggleStatus() {
			const thisClass = this;var userid, theInterval;
			theInterval = setInterval(() => {
				userid = document.querySelector( 'input[type="hidden"][name="userid"]:not([data-handled])' );
				if( userid ) {userid.dataset.handled = true;userid = userid.value;} else {userid = false;}
				document.querySelectorAll( '.fwp-form-checkbox-pause-subscribe:not([data-handled])' ).forEach( ( el, ei ) => {
					el.dataset.handled = true;
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
			}, 1000 );
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
			var theInterval = setInterval(() => {
				document.querySelectorAll( '.input-group-text.password-toggle:not([data-handled])' ).forEach( ( el, ei ) => {
					el.dataset.handled = true;
					el.addEventListener( 'click', ( event ) => {
							el.classList.toggle( 'showing' );
							ei = el.nextElementSibling;
							if( ei ) {
									ei.type = ( ei.type == 'text' ) ? 'password' : 'text';
							}
					} );
				} );
			}, 1000 );
		}
		fetchDataWidthContract() {
			document.querySelectorAll( '.document-sign-page *' ).forEach( ( e ) => {
				var replacer = {};
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
		setup_hooks() {
			document.body.addEventListener( 'reload-page', () => {location.reload();} );
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
		handleTabHref() {
			const thisClass = this;var selector, href, split, tab;selector = '.nav-pills .nav-link[role="tab"]';
			if( thisClass.profile && thisClass.profile.profilePath ) {
				split = thisClass.profile.profilePath.split( '/' );
				thisClass.profile.profilePath =  thisClass.profile.profilePath.substring( 0, ( thisClass.profile.profilePath.length - split[ ( split.length - 1 ) ].length - 1 ) );
			}
			document.querySelectorAll( selector ).forEach( link => {
				link.addEventListener( 'click', e => {
					e.preventDefault();
					href = link.href.split('#');href = ( href[1] ) ? href[1] : href[0];
					href = href.split('-');href = ( href[1] ) ? href[1] : href[0];
					thisClass.navigateTo( thisClass.profile.profilePath + '/' + href );
				} );
			} );
			setTimeout(() => {
				if( thisClass.profile && thisClass.profile.currentTab ) {
					tab = document.querySelector( selector + '[href="#profile-' + thisClass.profile.currentTab + '"]' );
					if( tab ) {tab.click();}
				}
			}, 500);
		}
		navigateTo( url ) {
			// if( this.profile ) {}
			// if( history.pushState && performance && performance.navigation.type == 0  ) {
			// 	history.pushState({}, '', url);
			// } else {
			if( url ) {
				history.replaceState({}, '', url);
			}
			// fetch( url ).then( response => response.text() ).then( html => {
			// 	document.querySelector('#content').innerHTML = html;
			// } );
		}
		deleteArchive() {
			const thisClass = this;var el, message;
			document.querySelectorAll( '.archive-delete-btn' ).forEach( ( archive ) => {
				archive.addEventListener( 'click', ( event ) => {
					event.preventDefault();
					Swal.fire({
						icon: 'info',
						title: thisClass.i18n.are_u_sure,
						showCancelButton: true,
						showLoaderOnConfirm: true
					} ).then((result) => {
						if (result.isConfirmed ) {
							var formdata = new FormData();
								formdata.append( 'action', 'futurewordpress/project/action/deletearchives' );
								formdata.append( 'archive', archive.dataset.archive );
								formdata.append( 'userid', archive.dataset.userid );
								formdata.append( '_nonce', thisClass.ajaxNonce );
								thisClass.sendToServer( formdata );
						}
					} );
				} );
			} );
		}
		submitArchiveFiles() {
			const thisClass = this;
			var node = document.querySelector( '.submit-archive-files' ), json;
			if( node ) {
				node.addEventListener( 'click', function( e ) {
					json = JSON.parse( node.dataset.config );
					json.preConfirm = (login) => {
						var form = document.querySelector( '#the-raw-video-archive-upload' ), formData, data = {};
						formData = new FormData(form);
						// formData.forEach(function(value, key) {
						// 	if( key == '_wp_http_referer' ) {} else {
						// 		data[key] = value;
						// 	}
						// });
						formData.append( 'title', login );

						// + '?action=futurewordpress/project/action/submitarchives&title=' + data.title + '&month=' + data.month + '&year=' + data.year
						return fetch( thisClass.ajaxUrl, {
							method: "POST",
							// headers: {"Content-Type": "application/json; charset=utf-8"},
							body: formData // JSON.stringify( data )
						} ).then(response => {
							if (!response.ok) {
								throw new Error(response.statusText)
							}
							return response.json()
						}).catch(error => {
							Swal.showValidationMessage(
								thisClass.i18n.request_failed + `: ${error}`
							)
						})
					};
					json.allowOutsideClick = () => !Swal.isLoading();
					Swal.fire( json ).then((result) => {
						if (result.isConfirmed ) {
							var data = result.value?.data??'Request sent but doesn\'t update anything.';
							data = data?.message??data;
							Swal.fire({
								title: ( result.value.success ) ? 'Success' : 'Failed', icon: ( result.value.success ) ? 'success' : 'error',
								text: data,
							}).then((result) => {
								location.reload();
							});
						}
					})
				} );
			}
		}
		trackWpformAjax() {
			const thisClass = this;var form;
			// wpformsAjaxSubmitSuccess | wpformsAjaxSubmitActionRequired | wpformsAjaxSubmitFailed | wpformsAjaxSubmitError | wpformsAjaxSubmitCompleted
			form  = document.querySelector( 'form.wpforms-form' );
			if( form ) {
				form.addEventListener( 'wpformsAjaxSubmitSuccess', ( event ) => {
					// Show success Message
					Swal.fire( {
						title: thisClass.i18n.confirming,
						text: thisClass.i18n.give_your_old_password,
						icon: 'info',
					} ).then( (result) => {
						// if (result.isConfirmed ) {}
					} )
				} )
			}
		}
		handlePayCardError() {
			var interval;
			interval = setInterval(() => {
				document.querySelectorAll( '.wpforms-error-container:not([data-handled])' ).forEach( ( e ) => {
					e.dataset.handled = true;
					if( e.innerText.includes( 'Credit Card Payment Error' ) ) {
						var selector = '.wpforms-container-full .wpforms-form .wpforms-page-indicator.connector .wpforms-page-indicator-page', tabs = document.querySelectorAll( selector ), active = document.querySelector( selector + '.active' ),
						container = document.querySelectorAll( 'div.wpforms-container-full .wpforms-form .wpforms-field-container .wpforms-page' ), submit;
						if( active ) {
							active.classList.remove( 'active' );
							active.querySelector( '.wpforms-page-indicator-page-number' ).setAttribute( 'style', '' );
							active.querySelector( '.wpforms-page-indicator-page-triangle' ).setAttribute( 'style', '' );
							tabs[ ( tabs.length - 1 ) ].classList.add( 'active' );
							tabs[ ( tabs.length - 1 ) ].querySelector( '.wpforms-page-indicator-page-number' ).style.backgroundColor = '#72b239';
							tabs[ ( tabs.length - 1 ) ].querySelector( '.wpforms-page-indicator-page-triangle' ).style.borderTopColor = '#72b239';
						}
						else {console.log( 'error 2' );}
						if( container.length >= 1 ) {
							container.forEach( ( c ) => {
								c.style.display = 'none';
							} );
							container[ ( container.length - 1 ) ].style.display = 'block';
						}
						else {console.log( 'error 3' );}
						submit = document.querySelector( '.wpforms-submit-container' );
						if( submit ) {submit.style.display = 'block';}
					}
					else {console.log( 'This error is not payment issue detected.' );}
				} );
			}, 100 );
		}
	}
	new FutureWordPress_Frontend();
} )( jQuery );
