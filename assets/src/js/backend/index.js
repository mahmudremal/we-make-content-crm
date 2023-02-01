import videojs from 'video.js';

( function ( $ ) {
	class FWPListivoBackendJS {
		constructor() {
			this.mediaUploader();
			this.ajaxComplete();
			this.videojs();
		}
		mediaUploader() {
			// on upload button click
			$( 'body' ).on( 'click', '.fwplistivo-upload', function( event ) {
				event.preventDefault(); // prevent default link click and page refresh
				const mediaConfig = ( this.dataset.config ) ? JSON.parse( this.dataset.config ) : {
					title: 'Insert image', // modal window title
					library : {
						// uploadedTo : wp.media.view.settings.post.id, // attach to the current post?
						type : 'image'
					},
					button: {
						text: 'Use this image' // button label text
					},
					multiple: false
				};
				// console.log( mediaConfig );
				const button = $(this)
				const imageId = button.next().next().val();
				const customUploader = wp.media( mediaConfig ).on( 'select', function() { // it also has "open" and "close" events
					const attachment = customUploader.state().get( 'selection' ).first().toJSON();
					button.removeClass( 'button' ).html( '<img src="' + attachment.url + '">'); // add image instead of "Upload Image"
					button.next().show(); // show "Remove image" link
					button.next().next().val( attachment.id ); // Populate the hidden field with image ID
				})
				// already selected images
				customUploader.on( 'open', function() {
					if( imageId ) {
						const selection = customUploader.state().get( 'selection' )
						attachment = wp.media.attachment( imageId );
						attachment.fetch();
						selection.add( attachment ? [attachment] : [] );
					}
				})
		
				customUploader.open()
			});
			// on remove button click
			$( 'body' ).on( 'click', '.fwplistivo-remove', function( event ) {
				event.preventDefault();
				const button = $(this);
				button.next().val( '' ); // emptying the hidden field
				button.hide().prev().addClass( 'button' ).html( 'Upload image' ); // replace the image with text
			});
		}
		ajaxComplete() {
			let numberOfTags = 0;
			let newNumberOfTags = 0;

			// when there are some terms are already created
			if( ! $( '#the-list' ).children( 'tr' ).first().hasClass( 'no-items' ) ) {
				numberOfTags = $( '#the-list' ).children( 'tr' ).length;
			}

			// after a term has been added via AJAX	
			$(document).ajaxComplete( function( event, xhr, settings ){

				newNumberOfTags = $( '#the-list' ).children('tr').length;
				if( parseInt( newNumberOfTags ) > parseInt( numberOfTags ) ) {
					// refresh the actual number of tags variable
					numberOfTags = newNumberOfTags;
			
					// empty custom fields right here
					$( '.fwplistivo-remove' ).each( function(){
						// empty hidden field
						$(this).next().val('');
						// hide remove image button
						$(this).hide().prev().addClass( 'button' ).text( 'Upload image' );
					});
				}
			});
		}
    videojs() {
      const thisClass = this;var theInterval, selector, players, css, js, csses, jses;
      thisClass.videoPlayers = [];thisClass.videoRecorders = [];
      selector = 'fwp-videojs-playing-field';
      theInterval = setInterval( () => {
				players = document.querySelectorAll( '.' + selector + ':not([data-handled])' );
				players.forEach( ( e, i ) => {
					if( ! e.id ) {e.id = selector + '-' + i;}e.dataset.handled = true;
					thisClass.videoPlayers.push( { id: e.id, i: i, player: videojs( e.id )} );
				} );
			}, 2000 );
      // document.querySelectorAll( 'fwp-videojs-record-field' ).forEach( ( e, i ) => {
      //   if( ! e.id ) {e.id = 'fwp-videojs-record-field-' + i;}
      //   thisClass.videoRecorders.push( { id: e.id, i: i, recorder: videojs( e.id )} );
      // } );
      csses = [ '//cdnjs.cloudflare.com/ajax/libs/video.js/7.5.5/video-js.min.css' ];
      csses.forEach( ( src ) => {
        css = document.createElement( 'link' );css.rel = 'stylesheet';css.href = src;document.head.appendChild( css );
      } );
    }
	}

	new FWPListivoBackendJS();
} )( jQuery );
