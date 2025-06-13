/**
 * WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import metadata from './block.json';

/**
 * Register blocks
 */
registerBlockType('ptre/properties-grid', {
    title: __('Properties Grid', 'ptre-plugin'),
    description: __('Display a grid of properties', 'ptre-plugin'),
    icon: 'grid-view',
    category: 'widgets',
    edit: () => (
        <div className="ptre-properties-grid-placeholder">
            <p>{__('Properties Grid will be displayed here.', 'ptre-plugin')}</p>
        </div>
    ),
    save: () => null // Dynamic block - rendered server-side
});

registerBlockType('ptre/featured-properties', {
    title: __('Featured Properties', 'ptre-plugin'),
    description: __('Display featured properties', 'ptre-plugin'),
    icon: 'star-filled',
    category: 'widgets',
    edit: () => (
        <div className="ptre-featured-properties-placeholder">
            <p>{__('Featured Properties will be displayed here.', 'ptre-plugin')}</p>
        </div>
    ),
    save: () => null // Dynamic block - rendered server-side
});