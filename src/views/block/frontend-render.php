<div class="wp-block-newfold-myschedulr">
    <div class="nms-wpblock">
        <h3 class="nms-block-title">
            <?php echo esc_attr( $attributes['sectionTitle'] ) ?>
        </h3>
        <div class="nms-tabs-wrapper">
            <?php if (count($event_categories)) : ?>
                <ul class="nms-tabs-nav">
                    <?php foreach ( $event_categories ?? [] as $key => $category ) : ?>
                        <li class="tab <?php echo esc_attr( $key == 0 ? 'active' : '' ); ?>"
                            data-category="<?php echo esc_attr( $category->id ) ?>">
                                <?php echo esc_attr( $category->name ) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <div class="message">
                    <?php echo __( 'Events with categories are required to be shown here.', 'myschedulr' ) ?>
                </div>
            <?php endif; ?>

            <?php if (count($events)) : ?>
            <div class="nms-tabs-content">
                <div class="nms-cards layout-<?php echo esc_attr($attributes['layout'] ?? 'three-columns') ?>">
                    <?php
                    foreach ( $event_categories ?? [] as $key => $event_category ) :
                        $category_events = array_filter( $events,
                            function ( $event ) use ( $event_category ) {
                                return $event->category_id === $event_category->id;
                            } );

                        foreach ( $category_events ?? [] as $event ) : ?>
                            <div data-category="<?php echo esc_attr($event->category_id); ?>"
                                 class="nms-cards__item <?php echo esc_attr($key !== 0 ? 'hidden' : ''); ?>"
                                 style="text-align: <?php echo esc_attr($attributes['alignment'] ?? 'left'); ?>">

                                <?php if ($block_elements->image) : ?>
                                    <div class="nms-card__image">
                                        <img src="<?php echo esc_url($event->image_url ?? 'https://via.placeholder.com/150'); ?>"
                                             alt="<?php echo esc_attr($event->name); ?>">
                                    </div>
                                <?php endif; ?>
                                <div class="nms-card__content">
                                    <?php if ($block_elements->title) : ?>
                                        <div class="nms-card__title"><?php echo __( $event->name, 'myschedulr' ); ?></div>
                                    <?php endif; ?>
                                    <?php if ($block_elements->shortDescription) : ?>
                                        <p class="nms-card__subtitle"><?php echo esc_attr( $event->tagline ); ?></p>
                                    <?php endif; ?>
                                    <?php if ($block_elements->longDescription) : ?>
                                        <p class="nms-card__description"><?php echo esc_attr( $event->description ); ?></p>
                                    <?php endif; ?>
                                    <p class="nms-card__duration">
                                                <span>
                                                    <?php echo esc_attr( $event->duration ); ?>
                                                    <?php echo esc_attr( 'minutes' ) ?>
                                                </span>
                                    </p>
                                    <p class="nms-card__price">
                                        <span>
                                            <?php echo esc_attr($event->price_description ); ?>
                                        </span>
                                    </p>
                                    <p>
                                        <button class="nms-btn-card__btn"
                                                data-event-id="<?php echo esc_attr( $event->id ); ?>"
                                                data-micromodal-trigger="nms-modal-dialog"
                                                data-url="<?php echo esc_url(MS4WP_APP_URL . 'bookings-scheduler/index?site=' . $siteId . '&serviceId=' . $event->id ); ?>"
                                                style="background-color: <?php echo esc_attr( $attributes['bg_color'] ); ?>">
                                            <?php echo esc_attr( $attributes['buttonCtaText'] ?? 'Book Now' ); ?>
                                        </button>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                    <div class="message hidden">
                        <?php echo esc_attr( $attributes['noEventsMessage'] ?? 'No events found' ) ?>
                    </div>
                </div>
            </div>
            <?php else: ?>
                <div class="message">
                    <?php echo __( 'Please create events and assign to a category.', 'myschedulr' ) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
