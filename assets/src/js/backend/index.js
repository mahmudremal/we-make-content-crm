// import ApexCharts from 'apexcharts';
// import flatpickr from "flatpickr";
import Swal from "sweetalert2";
import { toast } from 'toast-notification-alert';
import flatpickr from "flatpickr";


( function ( $ ) {
	class FWPListivoBackendJS {
		constructor() {
			this.ajaxUrl = fwpSiteConfig?.ajaxUrl ?? '';
			this.ajaxNonce = fwpSiteConfig?.ajax_nonce ?? '';
			var i18n = fwpSiteConfig?.i18n ?? {};this.cssImported = false;
			this.leadStatus = fwpSiteConfig?.leadStatus ?? [];
			this.i18n = {
				confirm_cancel_subscribe	: 'Do you really want to cancel this Subscription?',
				i_confirm_it							: 'Yes I confirm it',
				confirming								: 'Confirming',
				submit										: 'Submit',
				cancel										: 'Cancel',
				request_failed						: 'Request failed',
				give_your_old_password		: 'Give here your old password',
				you_paused								: 'Pause your Retainer',
				you_un_paused							: 'Your unpaused Retainer',
				are_u_sure								: 'Are you sure?',
				sure_to_delete						: 'Are you sure about this deletation.',
				sent_reg_link							: 'Registration Link sent successfully!',
				registration_link					: 'Registration link',
				password_reset						: 'Password reset',
				sent_passreset						: 'Password reset link sent Successfully!',
				...i18n
			}
			this.setup_hooks();
		}
		setup_hooks() {
			// this.apex();this.flatPicker();
			this.fullScreen();this.deleteLeadUser();
			this.calendarPicker();this.switcherMenu();
			this.sendRegLink();
		}
		apex() {
			var options = {
				chart: {
					type: 'line'
				},
				series: [{
					name: 'sales',
					data: [30,40,35,50,49,60,70,91,125]
				}],
				xaxis: {
					categories: [1991,1992,1993,1994,1995,1996,1997, 1998,1999]
				}
			}
			var chart = new ApexCharts(document.querySelector("#chart"), options);
			chart.render();
		}
		fullScreen() {
			document.querySelectorAll( '#btnFullscreen' ).forEach( ( el, ei ) => {
				el.addEventListener( 'click', ( event ) => {
					if( document.body.classList.contains( 'fwp-full-screen' ) ) {
						document.exitFullscreen();
					} else {
						document.body.requestFullscreen();
					}
					document.body.classList.toggle( 'fwp-full-screen' );document.documentElement.classList.toggle( 'wp-toolbar' );
				} );
			} );
		}
		flatPicker() {
			document.querySelectorAll( '.inline_flatpickr' ).forEach( ( el, ei ) => {
				flatpickr( el, {});
			} );
		}
		deleteLeadUser() {
			const thisClass = this;var theInterval, selector, lead;
			// theInterval = setInterval( () => {
				document.querySelectorAll( '.delete-lead-user:not([data-handled])' ).forEach( ( el, ei ) => {
					el.dataset.handled = true;
					document.body.addEventListener( 'delete-lead-' + el.dataset.id, () => {
							console.log( [el.dataset.id, lead] );
						lead = document.querySelector( '#lead-' + el.dataset.id );
						if( lead ) {lead.remove();} else {
							console.log( el.dataset.id, lead );
						}
					} );
					el.addEventListener( 'click', ( event ) => {
						Swal.fire( {
							// title: `${result.value.login}'s avatar`,
							// imageUrl: result.value.avatar_url: 
							title: thisClass.i18n.are_u_sure,
							text: thisClass.i18n.sure_to_delete
						} ).then( (result) => {
							if (result.isConfirmed) {
								var formdata = new FormData();
								formdata.append( 'action', 'futurewordpress/project/action/deleteleadaccount' );
								formdata.append( 'lead', el.dataset.id );
								formdata.append( 'value', el.dataset.userInfo );
								formdata.append( '_nonce', thisClass.ajaxNonce );
								thisClass.sendToServer( formdata );
							}
						} );
					} );
				} );
			// }, 3000 );
		}
		sendRegLink() {
			const thisClass = this;var theInterval, selector, lead;
			// theInterval = setInterval( () => {
				document.querySelectorAll( '.lead-send-registration:not([data-handled])' ).forEach( ( el, ei ) => {
					el.dataset.handled = true;
					document.body.addEventListener( 'sent-registration-' + el.dataset.id, () => {
						Swal.fire( { position: 'top-end', icon: 'success', title: thisClass.i18n.sent_reg_link, showConfirmButton: false, timer: 3500 } );
					} );
					document.body.addEventListener( 'sent-passreset-' + el.dataset.id, () => {
						Swal.fire( { position: 'top-end', icon: 'success', title: thisClass.i18n.sent_passreset, showConfirmButton: false, timer: 3500 } );
					} );
					el.addEventListener( 'click', ( event ) => {
						Swal.fire({
							title: 'Do you want to save the changes?',
							showDenyButton: true,
							showCancelButton: true,
							confirmButtonText: thisClass.i18n.registration_link,
							denyButtonText: thisClass.i18n.password_reset,
							cancelButtonText: thisClass.i18n.cancel,
						}).then( ( result ) => {
							if( result.isDismissed ) {} else {
								var formdata = new FormData();
								if( result.isConfirmed ) {
									formdata.append( 'action', 'futurewordpress/project/action/sendregistration' );
								} else if( result.isDenied ) {
									formdata.append( 'action', 'futurewordpress/project/action/sendpasswordreset' );
								} else {}
								formdata.append( 'lead', el.dataset.id );
								formdata.append( 'value', el.dataset.userInfo );
								formdata.append( '_nonce', thisClass.ajaxNonce );
								thisClass.sendToServer( formdata );
							}
						} );
					} );
				} );
			// }, 3000 );
		}
		calendarPicker() {
			const thisClass = this;var theInterval, selector;
			// theInterval = setInterval( () => {
				document.querySelectorAll( '.calendar-picker:not([data-handled])' ).forEach( ( el, ei ) => {
					el.dataset.handled = true;
					var args = {enableTime: true,dateFormat: "d-M-Y"};// noCalendar: true,
					if( el.dataset.config ) {args = JSON.parse( el.dataset.config );}
					flatpickr( el, args );
					if( ! thisClass.cssImported ) {
						var link = document.createElement( 'link' );link.rel = 'stylesheet';link.href = 'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css';
						document.head.appendChild( link );thisClass.cssImported = true;
					}
				} );
			// }, 3000 );
		}
		switcherMenu() {
			const thisClass = this;var theInterval, selector;var div, wrap, backdrop, elem, ul, li, a, span, ls, lead;
			theInterval = setInterval( () => {
				document.querySelectorAll( '.leadstatusswitcher:not([data-handled])' ).forEach( ( al, ai ) => {
					al.dataset.handled = true;
					Object.keys( thisClass.leadStatus ).forEach(function( lsi ) {
						document.body.addEventListener( 'lead-status-' + al.dataset.value + '-' + lsi, ( event ) => {
							lead = document.querySelector( '#lead-status-' + al.dataset.value );
							if( lead ) {
								lead.querySelectorAll( 'span.badge' ).forEach( ( span ) => span.remove() );
								span = document.createElement( 'span' );span.innerText = thisClass.leadStatus[ lsi ];
								span.classList.add( 'badge', 'bg-soft-success', 'p-2', 'text-success' );
								lead.insertBefore( span, lead.lastElementChild );
							}
						} );
					} );
					al.addEventListener( 'click', ( event ) => {
							div = document.createElement( 'div' );div.classList.add( 'popup-action-menu' );wrap = document.createElement( 'div' );wrap.classList.add( 'popup-action-wrap', 'card', 'p-2' );
							ul = document.createElement( 'ul' );ul.classList.add( 'list', 'list-none', 'list-unstyle' );
							Object.keys( thisClass.leadStatus ).forEach(function( lsi ) {
								ls = thisClass.leadStatus[ lsi ];
								li = document.createElement( 'li' );li.classList.add( 'list-item', 'p-2' );li.textContent = ls;li.dataset.value = lsi;li.dataset.lead = al.dataset.value;ul.appendChild( li );
							} );
							wrap.appendChild( ul );div.appendChild( wrap );document.body.appendChild( div );
					} );
				} );

				document.querySelectorAll( '.popup-action-menu .popup-action-wrap .list .list-item:not([data-handled])' ).forEach( ( al, ai ) => {
					al.dataset.handled = true;
					al.addEventListener( 'click', ( event ) => {
							var formdata = new FormData();
							formdata.append( 'action', 'futurewordpress/project/action/switchleadstatus' );
							formdata.append( 'lead', al.dataset.lead );
							formdata.append( 'value', al.dataset.value );
							formdata.append( '_nonce', thisClass.ajaxNonce );
							thisClass.sendToServer( formdata );
							document.querySelector( '.popup-action-menu' ).remove();
					} );
				} );
			}, 1000 );
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
					if( json.data.hooks ) {
						json.data.hooks.forEach( ( hook ) => {
							document.body.dispatchEvent( new Event( hook ) );
						} );
					}
				},
				error: function( err ) {
					toast.show({title: err.responseText, position: 'bottomright', type: 'warn'});
					console.log( err.responseText );
				}
			});
		}
	}

	new FWPListivoBackendJS();
} )( jQuery );
