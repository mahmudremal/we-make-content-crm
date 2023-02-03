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
				are_u_sure					: 'Are you sure?',
				sure_to_delete			: 'Are you sure about this deletation.',
				...i18n
			}
			this.setup_hooks();
		}
		setup_hooks() {
			// this.apex();this.flatPicker();
			this.fullScreen();this.deleteLeadUser();
			this.calendarPicker();
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
			const thisClass = this;var theInterval, selector;
			// theInterval = setInterval( () => {
				document.querySelectorAll( '.delete-lead-user:not([data-handled])' ).forEach( ( el, ei ) => {
					el.dataset.handled = true;
					el.addEventListener( 'click', ( event ) => {
						Swal.fire( {
							// title: `${result.value.login}'s avatar`,
							// imageUrl: result.value.avatar_url: 
							title: thisClass.i18n.are_u_sure,
							text: thisClass.i18n.sure_to_delete
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
					if( e.dataset.config ) {args = JSON.parse( e.dataset.config );}
					flatpickr( el, args );
				} );
			// }, 3000 );
		}
	}

	new FWPListivoBackendJS();
} )( jQuery );
