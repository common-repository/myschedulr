/**
 * Registers a new block provided a unique name and an object defining its behavior.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import { ReactComponent as LogoIcon } from '../../../../assets/images/icon-black.svg';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * All files containing `style` keyword are bundled together. The code used
 * gets applied both to the front of your site and to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './styles/style.scss';
import './styles/index.scss';

/**
 * Internal dependencies
 */
import Edit from './edit';
import save from './save';

/**
 * Every block starts by registering a new block type definition.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
registerBlockType( 'newfold/myschedulr', {
	/**
	 * Searchable keywords
	 */
	keywords: [
		__( 'bookings', 'myschedulr' ),
		__( 'scheduling', 'myschedulr' ),
		__( 'appointments', 'myschedulr' ),
		__( 'events', 'myschedulr' ),
		__( 'promotions', 'myschedulr' ),
		__( 'newfold', 'myschedulr' ),
	],
	/**
	 * Block custom icon
	 */
	icon: {
		src: LogoIcon,
	},
	/**
	 * Attributes of the Block
	 */
	attributes: {
		siteId: {
			type: 'string',
			default: '',
		},
		alignment: {
			type: 'string',
			default: 'left',
		},
		bg_color: {
			type: 'string',
			default: '#0076DF',
		},
		layout: {
			enum: [
				'two-columns',
				'three-columns',
				'four-columns',
				'rows',
				'grid',
			],
			default: 'three-columns',
		},
		buttonCtaText: {
			type: 'string',
			default: __( 'Book Now', 'myschedulr' ),
		},
		events: {
			type: 'array',
			default: [],
		},
		eventCategories: {
			type: 'array',
			default: [],
		},
		serviceElementsBtns: {
			type: 'object',
			default: {
				image: true,
				title: true,
				shortDescription: true,
				longDescription: true,
			},
		},
		noEventsMsg: {
			type: 'string',
			default: __(
				'No events for this category.',
				'myschedulr'
			),
		},
		sectionTitle: {
			type: 'string',
			default: __( 'Services', 'myschedulr' ),
		},
	},
	/**
	 * Block edit code
	 */
	edit: Edit,

	/**
	 * Block save code
	 */
	save,
} );
