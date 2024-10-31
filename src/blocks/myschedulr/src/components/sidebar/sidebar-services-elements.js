import { __ } from '@wordpress/i18n';
import {
	// eslint-disable-next-line @wordpress/no-unsafe-wp-apis
	__experimentalText as Text,
	FormToggle,
	PanelBody,
	PanelRow,
} from '@wordpress/components';

export default function SidebarServicesElements( {
	attributes,
	setAttributes,
} ) {
	const onChangeBtnImage = () => {
		setAttributes( {
			serviceElementsBtns: {
				...attributes.serviceElementsBtns,
				image: ! attributes.serviceElementsBtns.image,
			},
		} );
	};
	const onChangeBtnTitle = () => {
		setAttributes( {
			serviceElementsBtns: {
				...attributes.serviceElementsBtns,
				title: ! attributes.serviceElementsBtns.title,
			},
		} );
	};
	const onChangeBtnShortDescription = () => {
		setAttributes( {
			serviceElementsBtns: {
				...attributes.serviceElementsBtns,
				shortDescription: ! attributes.serviceElementsBtns
					.shortDescription,
			},
		} );
	};
	const onChangeBtnLongDescription = () => {
		setAttributes( {
			serviceElementsBtns: {
				...attributes.serviceElementsBtns,
				longDescription: ! attributes.serviceElementsBtns
					.longDescription,
			},
		} );
	};

	return (
		<PanelBody title={ __( 'Service Elements', 'myschedulr' ) }>
			<PanelRow>
				<Text> { __( 'Image', 'myschedulr' ) } </Text>
				<FormToggle
					checked={ attributes.serviceElementsBtns.image }
					onChange={ onChangeBtnImage }
				/>
			</PanelRow>
			<PanelRow>
				<Text> { __( 'Title', 'myschedulr' ) } </Text>
				<FormToggle
					checked={ attributes.serviceElementsBtns.title }
					onChange={ onChangeBtnTitle }
				/>
			</PanelRow>
			<PanelRow>
				<Text>
					{ __( 'Short Description', 'myschedulr' ) }
				</Text>
				<FormToggle
					checked={ attributes.serviceElementsBtns.shortDescription }
					onChange={ onChangeBtnShortDescription }
				/>
			</PanelRow>
			<PanelRow>
				<Text>
					{ __( 'Long Description', 'myschedulr' ) }
				</Text>
				<FormToggle
					checked={ attributes.serviceElementsBtns.longDescription }
					onChange={ onChangeBtnLongDescription }
				/>
			</PanelRow>
		</PanelBody>
	);
}
