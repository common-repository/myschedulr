/**
 * This file handles block frontend interactions.
 *
 * @package
 */
( ( $ ) => {
	$( () => {
		// tabs content interactions
		const tabs = $( '.nms-tabs-nav .tab' );
		const tabsItems = $( '.nms-cards__item' );
		const emptyMessage = $( '.message' );
		tabs.on( 'click', ( e ) => {
			const currentTab = $( e.target );
			// switch active tab
			tabs.removeClass( 'active' );
			currentTab.addClass( 'active' );
			// switch active tab content
			tabsItems.addClass( 'hidden' );
			const currentTabItems = $( '.nms-cards__item' ).filter(
				( index, item ) => {
					return (
						$( item ).data( 'category' ) ===
						currentTab.data( 'category' )
					);
				}
			);
			// show empty message
			if ( currentTabItems.length > 0 ) {
				currentTabItems.removeClass( 'hidden' );
				emptyMessage.addClass( 'hidden' );
			} else {
				emptyMessage.removeClass( 'hidden' );
			}
		} );
		// MODAL interactions
		const mModal = $( '#nms-modal-dialog' );
		const eventsCtaBtns = $( '.nms-btn-card__btn' );
		const modalIframe = mModal.find( '#nsm-modal-iframe' );
		eventsCtaBtns.on( 'click', ( e ) => {
			const event = $( e.target );
			modalIframe.attr( 'src', event.data( 'url' ) );
		} );
		MicroModal.init( {
			onShow: () => {
				modalIframe.off( 'load' ).load( () => {
					$( window ).on( 'message', ( e ) => {
						if ( e.originalEvent.data.type === 'close' ) {
							MicroModal.close( 'nms-modal-dialog' );
						}
					} );
				} );
			},
			onClose: () => {
				modalIframe.attr( 'src', '' );
			},
		} );
		//fix grid inside narrow containers
		const gridLayout = $( '.layout-grid' );
		if ( gridLayout.length ) {
			const layoutWidth = gridLayout.width();
			$( window ).resize( verifyLayout );
			function verifyLayout() {
				window.innerWidth > 992 && layoutWidth < 800
					? gridLayout.addClass( 'compressed' )
					: gridLayout.removeClass( 'compressed' );
			}
			verifyLayout();
		}
	} );
} )( jQuery );
