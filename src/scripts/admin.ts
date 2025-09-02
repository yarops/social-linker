/**
 * Admin scripts for Social Linker plugin.
 */

import '../types/types';
import '../styles/admin.scss';

declare const jQuery: any;
declare global {
    interface Window {
        wp: {
            template: (id: string) => (data: any) => string;
            media: (options: any) => any;
        };
    }
}

/**
 * Admin class for managing social links.
 */
class SocialLinkerAdmin {
    private container: JQuery;

    /**
     * Constructor.
     */
    constructor() {
        this.container = jQuery('.social-links-container');

        if (this.container.length) {
            this.initEvents();
            this.initSortable();
        }
    }

    /**
     * Initialize events.
     */
    private initEvents(): void {
        jQuery('.add-custom-network').on('click', () => this.addCustomNetwork());
        this.container.on('click', '.remove-social-link', (e) => this.removeNetwork(e));
    }

    /**
     * Initialize sortable functionality.
     */
    private initSortable(): void {
        if (jQuery.fn.sortable) {
            this.container.sortable({
                items: '.social-link-item',
                handle: '.social-link-header',
                cursor: 'move',
                opacity: 0.7,
                revert: true
            });
        }
    }

    /**
     * Add custom network.
     */
    private addCustomNetwork(): void {
        const id = 'custom-' + Date.now();
        const name = 'Custom Network';

        if (typeof window.wp === 'undefined' || typeof window.wp.template !== 'function') {
            console.error('WordPress template API is not available. Make sure wp-util is loaded.');
            alert('Error: Cannot add new network. WordPress template API is not available.');
            return;
        }

        const template = window.wp.template('social-link-item');
        const html = template({
            id: id,
            name: name
        });

        jQuery(html).insertBefore('.social-link-actions');
    }

    /**
     * Remove network.
     * 
     * @param {JQuery.ClickEvent} e - Click event.
     */
    private removeNetwork(e: JQuery.ClickEvent): void {
        const item = jQuery(e.currentTarget).closest('.social-link-item');

        if (confirm('Are you sure you want to delete this social network?')) {
            item.fadeOut(300, function () {
                jQuery(this).remove();
            });
        }
    }
}

// Initialize on page load
jQuery(document).ready(() => {
    new SocialLinkerAdmin();
});