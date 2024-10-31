import { __ } from '@wordpress/i18n';
import {
	getEventCategories,
	getScheduledEvents,
	getGroupServices,
} from './api';
import { BlockControls, useBlockProps } from '@wordpress/block-editor';
import { ReactComponent as LogoIcon } from '../../../../assets/images/icon-black.svg';
import { ReactComponent as IconRows } from '../../../../assets/images/icon_rows.svg';
import { ReactComponent as IconColumns } from '../../../../assets/images/icon_columns.svg';
import { ReactComponent as IconGrid } from '../../../../assets/images/icon_grid.svg';
import { ToolbarDropdownMenu } from '@wordpress/components';
import { useEffect, useState } from '@wordpress/element';
import BlockContent from './components/block-content';
import Sidebar from './components/sidebar/sidebar';
import { gridFixer } from './components/sidebar/sidebar-layout';
import './styles/editor.scss';

// eslint-disable-next-line import/no-extraneous-dependencies
import {
	alignNone,
	positionCenter,
	positionLeft,
	positionRight,
} from '@wordpress/icons';

// The edit function describes the structure of your block in the context of the
// editor. This represents what the editor will render when the block is used.
export default function Edit( { attributes, setAttributes } ) {
	const [ isLoading, setIsLoading ] = useState( true );

	async function fetchData() {
		let [
			bookableGroups = [],
			eventCategories = [],
			events = [],
		] = await Promise.all( [
			getGroupServices( attributes.siteId ),
			getEventCategories( attributes.siteId ),
			getScheduledEvents( attributes.siteId ),
		] );

		const uncategorizedEvents = events.filter(
			( event ) => event.category_id === null
		);

		if ( bookableGroups.services.length > 0 ) {
			const serviceIds = bookableGroups.services.map(
				( service ) => service.service_id
			);
			events = events.filter( ( event ) => {
				return (
					event.service_type === 1 ||
					( event.service_type === 2 &&
						serviceIds.includes( event.id ) )
				);
			} );
		}

		if ( uncategorizedEvents.length ) {
			eventCategories.push( {
				id: 'uncategorized',
				connected_business_id: null,
				name: 'Uncategorized',
			} );

			events = events.map( ( event ) => {
				if ( event.category_id === null ) {
					event.category_id = 'uncategorized';
				}
				return event;
			} );
		}

		setAttributes( { eventCategories } );
		setAttributes( { events } );
		setIsLoading( false );
	}

	useEffect( () => {
		if ( attributes.siteId ) {
			fetchData().catch( ( e ) => console.log( e ) );
		}
	}, [ attributes.siteId ] );

	if ( wp.ms4wpSiteId ) {
		setAttributes( { siteId: wp.ms4wpSiteId } );
	}

	return (
		<div { ...useBlockProps() }>
			<BlockControls group="block">
				<ToolbarDropdownMenu
					icon={ LogoIcon }
					label={ __( 'Layout', 'myschedulr' ) }
					controls={ [
						{
							title: __( 'Columns', 'myschedulr' ),
							icon: IconColumns,
							onClick: () =>
								setAttributes( { layout: 'three-columns' } ),
						},
						{
							title: __( 'Rows', 'myschedulr' ),
							icon: IconRows,
							onClick: () => setAttributes( { layout: 'rows' } ),
						},
						{
							title: __( 'Grid', 'myschedulr' ),
							icon: IconGrid,
							onClick: () => setAttributes( { layout: 'grid' } ),
						},
					] }
				/>
				<ToolbarDropdownMenu
					icon={ alignNone }
					label={ __(
						'Select a direction',
						'myschedulr'
					) }
					controls={ [
						{
							title: __( 'Align Left', 'myschedulr' ),
							icon: positionLeft,
							onClick: () =>
								setAttributes( { alignment: 'left' } ),
						},
						{
							title: __(
								'Align Center',
								'myschedulr'
							),
							icon: positionCenter,
							onClick: () =>
								setAttributes( { alignment: 'center' } ),
						},
						{
							title: __( 'Align Right', 'myschedulr' ),
							icon: positionRight,
							onClick: () =>
								setAttributes( { alignment: 'right' } ),
						},
					] }
				/>
			</BlockControls>
			{ attributes.siteId ? (
				<BlockContent
					attributes={ attributes }
					setAttributes={ setAttributes }
					isLoading={ isLoading }
				/>
			) : (
				<div className={ 'message' }>
					<p>
						<a href="/wp-admin/admin.php?page=myschedulr">
							{ __( 'Click here', 'myschedulr' ) }
						</a>
						{ __(
							' to link your MySchedulr account before use this block.',
							'myschedulr'
						) }
					</p>
				</div>
			) }
			<Sidebar
				attributes={ attributes }
				setAttributes={ setAttributes }
			/>
		</div>
	);
}
