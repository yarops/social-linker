<?php
namespace SocialLinker\Admin;

use SocialLinker\Core\DevMode;

/**
 * Admin class.
 */
class Settings {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'admin_menu', [ $this, 'add_admin_menu' ] );
		add_action( 'admin_init', [ $this, 'register_settings' ] );
		add_filter( 'plugin_action_links_' . SOCIAL_LINKER_PLUGIN_BASENAME, [ $this, 'register_settings' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_assets' ] );
	}

	/**
	 * Adds admin menu.
	 */
	public function add_admin_menu() {
		add_menu_page(
			__( 'Social Linker', 'social-linker' ),
			__( 'Social Linker', 'social-linker' ),
			'manage_options',
			'social-linker',
			[ $this, 'render_settings_page' ],
			'dashicons-share',
			100
		);
	}

	/**
	 * Registers plugin settings.
	 */
	public function register_settings() {
		register_setting( 'social_linker_settings', 'social_linker_settings', [ $this, 'sanitize_settings' ] );
	}

	/**
	 * Validates and sanitizes settings before saving.
	 *
	 * @param array $input Form data.
	 * @return array Sanitized data.
	 */
	public function sanitize_settings( $input ) {
		$sanitized = [];

		$sanitized['enabled']      = isset( $input['enabled'] ) ? true : false;
		$sanitized['position']     = isset( $input['position'] ) ? sanitize_text_field( $input['position'] ) : 'right';
		$sanitized['social_links'] = [];

		if ( isset( $input['social_links'] ) && is_array( $input['social_links'] ) ) {
			foreach ( $input['social_links'] as $key => $link ) {
				$sanitized['social_links'][ $key ] = [
					'id'      => sanitize_text_field( $link['id'] ),
					'name'    => sanitize_text_field( $link['name'] ),
					'url'     => esc_url_raw( $link['url'] ),
					'icon'    => sanitize_text_field( $link['icon'] ),
					'enabled' => isset( $link['enabled'] ) ? true : false,
				];
			}
		}

		return $sanitized;
	}

	/**
	 * Adds settings link on plugins page.
	 *
	 * @param array $links Links array.
	 * @return array Updated links array.
	 */
	public function add_settings_link( $links ) {
		$settings_link = '<a href="admin.php?page=social-linker">' . __( 'Settings', 'social-linker' ) . '</a>';
		array_unshift( $links, $settings_link );
		return $links;
	}

	/**
	 * Gets the icon URL for a social network.
	 *
	 * @param string $social_id Social network ID.
	 * @return string|false Icon URL or false if not found.
	 */
	public function get_icon_url( $social_id ) {
		// Check in active theme directory first.
		$theme_icon_path = get_template_directory() . '/assets/icons/' . $social_id . '.svg';
		$theme_icon_url  = get_template_directory_uri() . '/assets/icons/' . $social_id . '.svg';

		if ( file_exists( $theme_icon_path ) ) {
			return $theme_icon_url;
		}

		// Then check in plugin directory.
		$plugin_icon_path = SOCIAL_LINKER_PLUGIN_DIR . 'assets/icons/' . $social_id . '.svg';
		$plugin_icon_url  = SOCIAL_LINKER_PLUGIN_URL . 'assets/icons/' . $social_id . '.svg';

		if ( file_exists( $plugin_icon_path ) ) {
			return $plugin_icon_url;
		}

		return false;
	}

	/**
	 * Enqueues admin assets.
	 *
	 * @param string $hook Current admin page.
	 */
	public function enqueue_admin_assets( $hook ) {
		if ( 'toplevel_page_social-linker' !== $hook ) {
			return;
		}

		$dev_mode = DevMode::is_active( 'admin.ts' );

		if ( $dev_mode ) {
			wp_enqueue_script(
				'vite-client',
				SOCIAL_LINKER_VITE_SERVER . '/@vite/client',
				[],
				DevMode::get_version(),
				true
			);

			add_filter(
				'script_loader_tag',
				function ( $tag, $handle ) {
					if ( 'vite-client' === $handle ) {
						$tag = str_replace( ' src', ' type="module" src', $tag );
					}
					return $tag;
				},
				10,
				2
			);

			wp_enqueue_script(
				'social-linker-admin',
				SOCIAL_LINKER_VITE_SERVER . '/src/scripts/admin.ts',
				[ 'jquery', 'jquery-ui-sortable', 'wp-util' ],
				DevMode::get_version(),
				true
			);

			add_filter(
				'script_loader_tag',
				function ( $tag, $handle ) {
					if ( 'social-linker-admin' === $handle ) {
						$tag = str_replace( ' src', ' type="module" src', $tag );
					}
					return $tag;
				},
				10,
				2
			);

		} else {
			wp_enqueue_style(
				'social-linker-admin',
				SOCIAL_LINKER_PLUGIN_URL . 'assets/css/admin.css',
				[],
				SOCIAL_LINKER_VERSION
			);

			wp_enqueue_script(
				'social-linker-admin',
				SOCIAL_LINKER_PLUGIN_URL . 'assets/js/admin.js',
				[ 'jquery', 'jquery-ui-sortable', 'wp-util' ],
				SOCIAL_LINKER_VERSION,
				true
			);
		}
	}

	/**
	 * Renders settings page.
	 */
	public function render_settings_page() {
		$settings = get_option( 'social_linker_settings', [] );

		// Include settings page template.
		include SOCIAL_LINKER_PLUGIN_DIR . 'templates/admin/admin-settings.php';
	}
}
