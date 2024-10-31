import { __ } from '@wordpress/i18n';
import {
	// eslint-disable-next-line @wordpress/no-unsafe-wp-apis
	__experimentalText as Text,
	CustomSelectControl,
	PanelBody,
	PanelRow,
	TextControl,
} from '@wordpress/components';

export default function SidebarSettings( { attributes, setAttributes } ) {
	const settingsOptions = [
		{
			key: 'left',
			name: __( 'Left', 'myschedulr' ),
		},
		{
			key: 'center',
			name: __( 'Center', 'myschedulr' ),
		},
		{
			key: 'right',
			name: __( 'Right', 'myschedulr' ),
		},
	];

	return (
		<PanelBody title={ __( 'Settings', 'myschedulr' ) }>
			<PanelRow>
				<Text>{ __( 'Main Title', 'myschedulr' ) }</Text>
				<TextControl
					className={ 'nms-input-size' }
					value={ attributes.sectionTitle }
					onChange={ ( value ) => {
						setAttributes( { sectionTitle: value } );
					} }
				/>
			</PanelRow>
			<PanelRow>
				<Text> { __( 'Alignment', 'myschedulr' ) } </Text>
				<CustomSelectControl
					className="nms-sb-align-select"
					options={ settingsOptions }
					value={ settingsOptions.find(
						( option ) => option.key === attributes.alignment
					) }
					onChange={ ( { selectedItem } ) => {
						setAttributes( { alignment: selectedItem.key } );
					} }
				/>
			</PanelRow>
		</PanelBody>
	);
}
