/**
 * Frontend scripts for Social Linker plugin.
 */

import '../types/types';
import '../styles/frontend.scss';

/**
 * Frontend class for managing social links floating box.
 */
class SocialLinkerFrontend {
    private floatingBox: HTMLElement | null;
    private toggle: HTMLElement | null;

    /**
     * Constructor.
     */
    constructor() {
        this.floatingBox = document.querySelector('.social-linker');
        this.toggle = document.querySelector('.social-linker-toggle');

        if (this.floatingBox) {
            this.initEvents();
            this.handleResponsive();
        }
    }

    /**
     * Initialize events.
     */
    private initEvents(): void {
        if (this.toggle) {
            this.toggle.addEventListener('click', () => {
                this.floatingBox?.classList.toggle('active');
            });
        }

        document.addEventListener('click', (event: MouseEvent) => {
            const target = event.target as HTMLElement;
            if (!target.closest('.social-linker')) {
                this.floatingBox?.classList.remove('active');
            }
        });

        window.addEventListener('resize', () => {
            this.handleResponsive();
        });
    }

    /**
     * Handle responsive behavior.
     */
    private handleResponsive(): void {
        if (window.innerWidth <= 768) {
            this.floatingBox?.classList.add('mobile', 'active');
        } else {
            this.floatingBox?.classList.remove('mobile', 'active');
        }
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    new SocialLinkerFrontend();
});