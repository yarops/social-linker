/**
 * TypeScript declarations for Social Linker plugin.
 */

/**
 * Social Link interface.
 */
export interface SocialLink {
    id: string;
    name: string;
    url: string;
    icon: string;
    enabled: boolean;
    custom_icon?: string;
}

/**
 * WordPress media frame interface.
 */
export interface WPMediaFrame {
    on(event: string, callback: Function): void;
    open(): void;
    state(): {
        get(key: string): {
            first(): {
                toJSON(): any;
            };
        };
    };
}

/**
 * WordPress global interface.
 */
export interface WP {
    media(options: any): WPMediaFrame;
    template(id: string): (data: any) => string;
}

/**
 * Social Linker data interface.
 */
export interface SocialLinkerData {
    position: 'left' | 'right';
}

// Augment global Window interface
declare global {
    interface Window {
        wp: WP;
        socialLinkerData: SocialLinkerData;
    }
}

// Ensure this file is treated as a module
export { };