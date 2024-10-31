import { __ } from '@wordpress/i18n';
import { useEffect } from '@wordpress/element';
import {
	// eslint-disable-next-line @wordpress/no-unsafe-wp-apis
	__experimentalText as Text,
	Button,
	ButtonGroup,
	PanelBody,
	PanelRow,
} from '@wordpress/components';

export const gridFixer = ( attributes ) => {
	if ( attributes.layout === 'grid' ) {
		const gridLayout = jQuery( '.layout-grid' );
		if ( gridLayout.length ) {
			const layoutWidth = gridLayout.width();
			window.innerWidth > 992 && layoutWidth < 800
				? gridLayout.addClass( 'compressed' )
				: gridLayout.removeClass( 'compressed' );
		}
	}
};

export default function SidebarLayout( { attributes, setAttributes } ) {
	useEffect( () => {
		gridFixer( attributes );
	}, [ attributes.layout ] );

	return (
		<PanelBody title={ __( 'Layout', 'myschedulr' ) }>
			<PanelRow>
				<Button
					isPressed={
						attributes.layout === 'two-columns' ||
						attributes.layout === 'three-columns' ||
						attributes.layout === 'four-columns'
					}
					className={ 'nms-sb-layout-btn' }
					text={ __( 'Columns', 'myschedulr' ) }
					onClick={ () =>
						setAttributes( { layout: 'three-columns' } )
					}
				/>
				<Button
					isPressed={ attributes.layout === 'rows' }
					className={ 'nms-sb-layout-btn' }
					text={ __( 'Rows', 'myschedulr' ) }
					onClick={ () => setAttributes( { layout: 'rows' } ) }
				/>
			</PanelRow>
			<PanelRow>
				<Button
					isPressed={ attributes.layout === 'grid' }
					className={ 'nms-sb-layout-btn' }
					text={ __( 'Grid', 'myschedulr' ) }
					onClick={ () => setAttributes( { layout: 'grid' } ) }
				/>
			</PanelRow>
			<div
				hidden={
					attributes.layout === 'grid' || attributes.layout === 'rows'
				}
			>
				<PanelRow>
					<Text> { __( 'Columns', 'myschedulr' ) } </Text>
				</PanelRow>
				<PanelRow>
					<ButtonGroup className={ 'nms-sb-layout-cl-btn-container' }>
						<Button
							isPressed={ attributes.layout === 'two-columns' }
							className={ 'nms-sb-cl-btn' }
							text={ __( '2', 'myschedulr' ) }
							onClick={ () =>
								setAttributes( { layout: 'two-columns' } )
							}
						/>
						<Button
							isPressed={ attributes.layout === 'three-columns' }
							className={ 'nms-sb-cl-btn' }
							text={ __( '3', 'myschedulr' ) }
							onClick={ () =>
								setAttributes( { layout: 'three-columns' } )
							}
						/>
						<Button
							isPressed={ attributes.layout === 'four-columns' }
							className={ 'nms-sb-cl-btn' }
							text={ __( '4', 'myschedulr' ) }
							onClick={ () =>
								setAttributes( { layout: 'four-columns' } )
							}
						/>
					</ButtonGroup>
				</PanelRow>
			</div>
		</PanelBody>
	);
}
