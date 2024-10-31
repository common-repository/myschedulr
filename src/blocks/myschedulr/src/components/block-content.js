import { __ } from '@wordpress/i18n';
import Tabs from './tabs';
import { ReactComponent as LoadingIcon } from '../../../../../assets/images/loading.svg';
import { useEffect } from 'react';
import { gridFixer } from './sidebar/sidebar-layout';

export default function BlockContent( { attributes, isLoading } ) {
	const renderImage = ( imageUrl, name ) => {
		if ( attributes.serviceElementsBtns.image ) {
			return (
				<div className="nms-card__image">
					<img
						src={ imageUrl ?? 'https://via.placeholder.com/150' }
						alt={ name }
					/>
				</div>
			);
		}
		return <div className={ 'ms4wp-mt-7' }></div>;
	};
	const renderTitle = ( name ) => {
		if ( attributes.serviceElementsBtns.title ) {
			return <div className="nms-card__title">{ name }</div>;
		}
		return <div className={ 'ms4wp-mt-4' }></div>;
	};
	const renderShortDescription = ( tagline ) => {
		if ( attributes.serviceElementsBtns.shortDescription ) {
			return <p className="nms-card__subtitle">{ tagline }</p>;
		}
		return <div className={ 'ms4wp-mt-4' }></div>;
	};
	const renderLongDescription = ( description ) => {
		if ( attributes.serviceElementsBtns.longDescription ) {
			return <p className="nms-card__description">{ description }</p>;
		}
		return <div className={ 'ms4wp-mt-4' }></div>;
	};

	useEffect( () => {
		if ( ! isLoading ) {
			gridFixer( attributes );
		}
	}, [ isLoading ] );

	return isLoading ? (
		<div className={ 'loading' }>
			<LoadingIcon />
		</div>
	) : (
		<div className="nms-wpblock">
			<h3 className="nms-block-title">{ attributes.sectionTitle }</h3>
			{ attributes.eventCategories.length ? (
				<Tabs layout={ attributes.layout }>
					{ attributes.eventCategories.map( ( category ) => {
						const events = attributes.events.filter(
							( event ) => event.category_id === category.id
						);

						if ( events.length ) {
							return (
								<div label={ category.name }>
									{ events.map( ( event, index ) => {
										return (
											<div
												className="nms-cards__item"
												key={ index }
												style={ {
													textAlign:
														attributes.alignment,
												} }
											>
												{ renderImage(
													event.image_url,
													event.name
												) }
												<div className="nms-card__content">
													{ renderTitle(
														event.name
													) }
													{ renderShortDescription(
														event.tagline
													) }
													{ renderLongDescription(
														event.description
													) }

													<div className="nms-card__duration">
														<span>
															{ event.duration }
															{ __(
																' minutes',
																'myschedulr'
															) }
														</span>
													</div>
													<p className="nms-card__price">
														<span>
															{
																event.price_description
															}
														</span>
													</p>
													<p>
														<button
															className="nms-btn-card__btn"
															style={ {
																backgroundColor:
																	attributes.bg_color,
															} }
														>
															{
																attributes.buttonCtaText
															}
														</button>
													</p>
												</div>
											</div>
										);
									} ) }
								</div>
							);
						}
						return (
							<div key={ events.id }>
								<div className={ 'message' }>
									{ attributes.noEventsMsg }
								</div>
							</div>
						);
					} ) }
				</Tabs>
			) : (
				<div className={ 'message' }>
					<p>
						{ __(
							'No bookings created yet.',
							'myschedulr'
						) }
						<br />
						<a href="/wp-admin/admin.php?page=myschedulr">
							{ __( 'Click here', 'myschedulr' ) }
						</a>
						{ __(
							' to create bookings in your MySchedulr account.',
							'myschedulr'
						) }
					</p>
				</div>
			) }
		</div>
	);
}
