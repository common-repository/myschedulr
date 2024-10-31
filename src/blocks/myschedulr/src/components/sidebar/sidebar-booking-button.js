import { __ } from '@wordpress/i18n';
import { useState } from '@wordpress/element';
import {
	// eslint-disable-next-line @wordpress/no-unsafe-wp-apis
	__experimentalText as Text,
	// eslint-disable-next-line @wordpress/no-unsafe-wp-apis
	__experimentalSpacer as Spacer,
	ColorIndicator,
	PanelBody,
	Popover,
	PanelRow,
	TextControl,
	CardBody,
	ColorPalette,
	Card,
} from '@wordpress/components';

export default function SidebarBookingButton( { attributes, setAttributes } ) {
	const [ hideColorPicker, setHideColorPicker ] = useState( true );

	const nmsShowColorPickerModal = () => {
		setHideColorPicker( ! hideColorPicker );
	};
	const nmsTriggerColorPicker = () => {
		const nmsColorPickerButton = document.querySelectorAll(
			'.nms-hide-cp-link div:nth-child(2) button'
		)[ 1 ];
		nmsColorPickerButton.click();
	};
	const paletteColors = [
		{ name: 'primary/background', color: '#0076DF' },
		{ name: 'primary', color: '#0076DF1A' },
		{ name: 'dark gray', color: '#262E39' },
		{ name: 'light gray', color: '#C4C4C4' },
		{ name: 'white solid', color: '#FFFFFF' },
	];
	const onChangeBGColor = ( hexColor ) => {
		setAttributes( { bg_color: hexColor } );
	};

	return (
		<PanelBody title={ __( 'Booking Button', 'myschedulr' ) }>
			<PanelRow>
				<Text> { __( 'Button Text', 'myschedulr' ) } </Text>
				<TextControl
					className={ 'nms-input-size' }
					value={ attributes.buttonCtaText }
					onChange={ ( value ) => {
						setAttributes( { buttonCtaText: value } );
					} }
				/>
			</PanelRow>
			<Spacer marginBottom={ 4 } />
			<PanelRow>
				<Text> { __( 'Button Color', 'myschedulr' ) } </Text>
			</PanelRow>
			<PanelRow>
				<div className={ 'nms-color-container' }>
					<ColorIndicator
						className={ 'nms-color-btn' }
						colorValue={ attributes.bg_color }
						circleOption={ true }
						onClick={ nmsShowColorPickerModal }
						onKeyDown={ nmsShowColorPickerModal }
					/>
					<Text
						className={ 'ms4wp-mr-2' }
						onClick={ nmsShowColorPickerModal }
						onKeyDown={ nmsShowColorPickerModal }
					>
						{ attributes.bg_color }
					</Text>
					<Popover
						hidden={ hideColorPicker }
						onClose={ nmsShowColorPickerModal }
						position={ 'middle left' }
					>
						<Card
							isRounded={ false }
							isBorderless={ true }
							hidden={ hideColorPicker }
						>
							<CardBody size={ 'small' }>
								{ wp.ms4wpWPver < '5.9' && (
									<>
										<div
											hidden={ hideColorPicker }
											role={ 'button' }
											className={ 'nms-color-viewer' }
											style={ {
												backgroundColor:
													attributes.bg_color,
											} }
											onClick={ nmsTriggerColorPicker }
											onKeyDown={ nmsTriggerColorPicker }
											tabIndex={ -10 }
										>
											<Text className={ 'ms4wp-mr-2' }>
												{ attributes.bg_color }
											</Text>
										</div>
										<Spacer marginBottom={ 4 } />
									</>
								) }
								<ColorPalette
									hidden={ hideColorPicker }
									className={ 'nms-hide-cp-link' }
									colors={ paletteColors }
									value={ attributes.bg_color }
									onChange={ onChangeBGColor }
								/>
							</CardBody>
						</Card>
					</Popover>
				</div>
			</PanelRow>
		</PanelBody>
	);
}
