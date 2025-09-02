<?php
namespace SocialLinker\Core;

/**
 * Class for detecting development mode.
 */
class DevMode {
	/**
	 * Cache for development mode state.
	 *
	 * @var bool|null
	 */
	private static $is_dev_mode = null;

	/**
	 * Check if development mode is active.
	 *
	 * @param string $script_path Path to script file to check.
	 * @return bool True if development mode is active.
	 */
	public static function is_active( $script_path = 'frontend.ts' ) {
		// Return cached value if available.
		if ( null !== self::$is_dev_mode ) {
			return self::$is_dev_mode;
		}

		// Default to production mode.
		self::$is_dev_mode = false;

		// Full path to the script.
		$full_script_path = SOCIAL_LINKER_PLUGIN_DIR . 'src/scripts/' . $script_path;

		// Only attempt connection if source files exist.
		if ( file_exists( $full_script_path ) ) {
			$vite_server_url = SOCIAL_LINKER_VITE_SERVER . '/@vite/client';

			$response = wp_remote_get(
				$vite_server_url,
				[
					'timeout'   => 1, // Short timeout to not slow down the site.
					'sslverify' => false, // Local dev often uses self-signed certs.
				]
			);

			// If Vite server responds, we're in dev mode.
			self::$is_dev_mode = ! is_wp_error( $response ) && wp_remote_retrieve_response_code( $response ) === 200;
		}

		return self::$is_dev_mode;
	}

	/**
	 * Get version string with timestamp for development mode.
	 *
	 * @return string Version string.
	 */
	public static function get_version() {
		if ( self::is_active() ) {
			return SOCIAL_LINKER_VERSION . '-' . time();
		}

		return SOCIAL_LINKER_VERSION;
	}
}
