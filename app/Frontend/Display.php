<?php
namespace SocialLinker\Frontend;

use SocialLinker\Core\DevMode;

/**
 * Frontend class.
 */
class Display {
	/**
	 * Plugin settings.
	 *
	 * @var array
	 */
	private $settings;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->settings = get_option( 'social_linker_settings', [] );

		if ( ! isset( $this->settings['enabled'] ) || ! $this->settings['enabled'] ) {
			return;
		}

		add_action( 'wp_footer', [ $this, 'render_floating_box' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );
	}

	/**
	 * Enqueue assets.
	 */
	public function enqueue_assets() {
		$dev_mode = DevMode::is_active( 'frontend.ts' );

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
				'social-linker-frontend',
				SOCIAL_LINKER_VITE_SERVER . '/src/scripts/frontend.ts',
				[ 'jquery' ],
				DevMode::get_version(),
				true
			);

			add_filter(
				'script_loader_tag',
				function ( $tag, $handle ) {
					if ( 'social-linker-frontend' === $handle ) {
						$tag = str_replace( ' src', ' type="module" src', $tag );
					}
					return $tag;
				},
				10,
				2
			);

		} else {
			wp_enqueue_style(
				'social-linker-frontend',
				SOCIAL_LINKER_PLUGIN_URL . 'assets/styles/frontend.css',
				[],
				SOCIAL_LINKER_VERSION
			);

			wp_enqueue_script(
				'social-linker-frontend',
				SOCIAL_LINKER_PLUGIN_URL . 'assets/scripts/frontend.js',
				[ 'jquery' ],
				SOCIAL_LINKER_VERSION,
				true
			);
		}

		wp_localize_script(
			'social-linker-frontend',
			'socialLinkerData',
			[
				'position' => isset( $this->settings['position'] ) ? $this->settings['position'] : 'right',
			]
		);
	}

	/**
	 * Render floating box.
	 */
	public function render_floating_box() {
		if ( empty( $this->settings['social_links'] ) ) {
			return;
		}

		$active_links = array_filter(
			$this->settings['social_links'],
			function ( $link ) {
				return isset( $link['enabled'] ) && $link['enabled'] && ! empty( $link['url'] );
			}
		);

		if ( empty( $active_links ) ) {
			return;
		}

		include SOCIAL_LINKER_PLUGIN_DIR . 'templates/frontend/floating-box.php';
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
	 * Returns SVG icon for social network.
	 *
	 * @param array $link Social network data.
	 * @return string HTML icon code.
	 */
	public function get_icon( $link ) {
		$icon_url = $this->get_icon_url( $link['id'] );

		if ( empty( $icon_url ) ) {
			$icon_url = SOCIAL_LINKER_PLUGIN_URL . 'assets/icons/link.svg';
		}

		return '<img src="' . esc_url( $icon_url ) . '" alt="' . esc_attr( $link['name'] ) . '" class="social-linker-icon">';
	}
}
