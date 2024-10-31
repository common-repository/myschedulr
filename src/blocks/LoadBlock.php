<?php

/**
 * Class MyPlugin_Blocks
 */

namespace MySchedulr\Blocks;

use MySchedulr\Services\PublicApiService;

class LoadBlock
{
    private static $instance;
    private $apiService;

    public function __construct()
    {
        $this->apiService = new PublicApiService();
    }

    public static function getInstance(): LoadBlock
    {
        if (self::$instance === null) {
            self::$instance = new LoadBlock();
        }

        return self::$instance;
    }

    public function addHooks(): void
    {
        add_action('init', [$this, 'ms4wpRegisterBlock' ]);
        add_action('admin_enqueue_scripts', [$this, 'ms4wpEnqueueAdminAssets' ]);
        add_action('wp_enqueue_scripts', [$this, 'ms4wpEnqueueFrontendAssets']);
        $this->registerBlockAssets();
        add_action('wp_footer', [$this, 'ms4wpRenderModalMarkup' ]);
    }

    public function ms4wpRegisterBlock(): void
    {
        register_block_type(__DIR__ . '/myschedulr', [
            'attributes'      => $this->getBlockAttributes(),
            'render_callback' => [$this, 'ms4wpRenderFrontendCallback' ],
        ]);
    }

    public function ms4wpRenderModalMarkup(): void
    {
        if (has_block('newfold/myschedulr')) {
            include MS4WP_PLUGIN_DIR . 'src/views/block/modal.php';
        }
    }

    public function ms4wpRenderFrontendCallback($attributes)
    {
        $block_elements = (object)$attributes['serviceElementsBtns'];
        $siteId = $this->apiService->siteId;

        try {
            $this->apiService->getBlockDataFromApi();

            [$event_categories, $events] = [$this->apiService->getEventCategories(), $this->apiService->getEvents()];

            if (count($events)) {
                $uncategorized_events = array_filter($events, function ($event) {
                    return is_null($event->category_id);
                });

                if (count($uncategorized_events)) {
                    $event_categories[] = (object) [
                        'id'                    => 'uncategorized',
                        'name'                  => 'Uncategorized',
                        'connected_business_id' => null,
                    ];

                    $events = array_map(function ($event) {
                        if (is_null($event->category_id)) {
                            $event->category_id = 'uncategorized';
                        }
                        return $event;
                    }, $events);
                }
            }
        } catch (\Throwable $e) {
            $errorMessage = $e->getMessage();
        }

        ob_start();

        isset($errorMessage)
            ? include MS4WP_PLUGIN_DIR . 'src/views/block/no-data-message.php'
            : include MS4WP_PLUGIN_DIR . 'src/views/block/frontend-render.php';

        return ob_get_clean();
    }

    private function registerBlockAssets(): void
    {
        wp_register_style(
            'ms4wp-block-style',
            MS4WP_PLUGIN_URL . 'assets/js/block/style-ms-block.css',
            null,
            MS4WP_PLUGIN_VERSION
        );

        wp_register_script(
            'ms4wp-frontend-script',
            MS4WP_PLUGIN_URL . 'assets/js/block/nsm-frontend.js',
            ['jquery', 'ms4wp-dep-micromodal'],
            MS4WP_PLUGIN_VERSION,
            true
        );

        wp_register_script(
            'ms4wp-dep-micromodal',
            MS4WP_PLUGIN_URL . 'assets/js/dependencies/micromodal.min.js',
            ['jquery'],
            MS4WP_PLUGIN_VERSION,
            true
        );
    }

    public function ms4wpEnqueueAdminAssets(): void
    {
        if ($this->isInGutenbergEditor()) {
            $this->printSiteIdScript('newfold-myschedulr-editor-script');
        }
    }

    public function ms4wpEnqueueFrontendAssets(): void
    {
        if (has_block('newfold/myschedulr')) {
            wp_enqueue_style('ms4wp-block-style');
            wp_enqueue_script('ms4wp-frontend-script');
            wp_enqueue_script('ms4wp-dep-micromodal');
        }
    }

    private function printSiteIdScript($handler): void
    {
        if ($this->apiService->hasLinkedAccount()) {
            wp_add_inline_script(
                $handler,
                sprintf('wp.ms4wpSiteId = %s;', $this->apiService->siteId)
                . sprintf('wp.ms4wpBaseURL = "%s";', $this->apiService->baseUrl)
                . sprintf('wp.ms4wpWPver = "%s";', get_bloginfo( 'version' ))
            );
        }
    }

    /**
     * IMPORTANT: This needs to reflect the same attributes defined for the
     * registerBlockType javascript function in order to work properly.
     * Without this, the block will be rendered with default values in the user site frontend.
     */
    private function getBlockAttributes(): array
    {
        return [
            'layout' => [
                'enum' => [
                    'two-columns',
                    'three-columns',
                    'four-columns',
                    'rows',
                    'grid',
                ],
                'default' => 'three-columns',
            ],
            'sectionTitle' => [
                'type'    => 'string',
                'default' => __('Services', 'myschedulr'),
            ],
            'alignment' => [
                'type'    => 'string',
                'default' => 'left',
            ],
            'bg_color' => [
                'type'    => 'string',
                'default' => '#0076DF',
            ],
            'buttonCtaText' => [
                'type'    => 'string',
                'default' => __('Book Now', 'myschedulr'),
            ],
            'events' => [
                'type'    => 'array',
                'default' => [],
            ],
            'eventCategories' => [
                'type'    => 'array',
                'default' => [],
            ],
            'serviceElementsBtns' => [
                'type'    => 'object',
                'default' => [
                    'image'            => true,
                    'title'            => true,
                    'shortDescription' => true,
                    'longDescription'  => true,
                ],
            ],
        ];
    }

    private function isInGutenbergEditor(): bool
    {
        $current_screen = get_current_screen();
        return (method_exists( $current_screen, 'is_block_editor' ) && $current_screen->is_block_editor())
               || (function_exists( 'is_gutenberg_page' ) && is_gutenberg_page());
    }
}
