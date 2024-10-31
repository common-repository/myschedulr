import { InspectorControls } from '@wordpress/block-editor';

import SidebarLayout from './sidebar-layout';
import SidebarSettings from './sidebar-settings';
import SidebarBookingButton from './sidebar-booking-button';
import SidebarServicesElements from './sidebar-services-elements';

export default function Sidebar( { attributes, setAttributes } ) {
	return (
		<InspectorControls key="setting">
			<SidebarLayout
				attributes={ attributes }
				setAttributes={ setAttributes }
			/>
			<SidebarSettings
				attributes={ attributes }
				setAttributes={ setAttributes }
			/>
			<SidebarBookingButton
				attributes={ attributes }
				setAttributes={ setAttributes }
			/>
			<SidebarServicesElements
				attributes={ attributes }
				setAttributes={ setAttributes }
			/>
		</InspectorControls>
	);
}
