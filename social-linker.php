<?php
/**
 * Plugin Name: Social Linker
 * Plugin URI: https://example.com/social-linker
 * Description: Float social links box
 * Version: 1.0.0
 * Author: Yaroslav Popov ed.creater@gmail.com
 * Author URI: https://codesweet.ru
 * Text Domain: social-linker
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SOCIAL_LINKER_VERSION', '1.0.0' );
define( 'SOCIAL_LINKER_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'SOCIAL_LINKER_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'SOCIAL_LINKER_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'SOCIAL_LINKER_VITE_SERVER', 'https://localhost:3000' );

if ( file_exists( SOCIAL_LINKER_PLUGIN_DIR . 'vendor/autoload.php' ) ) {
	require_once SOCIAL_LINKER_PLUGIN_DIR . 'vendor/autoload.php';
} else {
	add_action(
		'admin_notices',
		function () {
			echo '<div class="error"><p>' .
			esc_html__( 'Social Linker requires Composer autoloader. Please run "composer install" in the plugin directory.', 'social-linker' ) .
			'</p></div>';
		}
	);
	return;
}

function social_linker_activate() {
	$default_settings = [
		'enabled'      => true,
		'position'     => 'right',
		'social_links' => [
			[
				'id'      => 'vk',
				'name'    => 'VK',
				'url'     => '',
				'icon'    => 'vk',
				'enabled' => true,
			],
			[
				'id'      => 'facebook',
				'name'    => 'Facebook',
				'url'     => '',
				'icon'    => 'facebook',
				'enabled' => true,
			],
			[
				'id'      => 'instagram',
				'name'    => 'Instagram',
				'url'     => '',
				'icon'    => 'instagram',
				'enabled' => true,
			],
			[
				'id'      => 'whatsapp',
				'name'    => 'WhatsApp',
				'url'     => '',
				'icon'    => 'whatsapp',
				'enabled' => true,
			],
			[
				'id'      => 'telegram',
				'name'    => 'Telegram',
				'url'     => '',
				'icon'    => 'telegram',
				'enabled' => true,
			],
		],
	];

	add_option( 'social_linker_settings', $default_settings );
}
register_activation_hook( __FILE__, 'social_linker_activate' );

function social_linker_deactivate() {
	// Deactivation hook.
}
register_deactivation_hook( __FILE__, 'social_linker_deactivate' );

function social_linker_uninstall() {
	delete_option( 'social_linker_settings' );
}
register_uninstall_hook( __FILE__, 'social_linker_uninstall' );

/**
 * Разрешает загрузку SVG файлов в медиа-библиотеку WordPress.
 *
 * @param array $mimes Массив разрешенных MIME-типов.
 * @return array Обновленный массив разрешенных MIME-типов.
 */
function social_linker_allow_svg_upload( $mimes ) {
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
}
add_filter( 'upload_mimes', 'social_linker_allow_svg_upload' );

/**
 * Исправляет проверку SVG файлов при загрузке.
 *
 * @param array $data Массив данных загрузки.
 * @param array $file Массив информации о файле.
 * @return array Обновленный массив данных загрузки.
 */
function social_linker_fix_svg_upload_check( $data, $file, $filename, $mimes ) {
	if ( isset( $data['ext'] ) && $data['ext'] === 'svg' ) {
		if ( $data['type'] === false ) {
			$data['type'] = 'image/svg+xml';
		}
	}
	return $data;
}
add_filter( 'wp_check_filetype_and_ext', 'social_linker_fix_svg_upload_check', 10, 4 );

// Initialize plugin.
function social_linker_init() {
	new SocialLinker\Admin\Settings();
	new SocialLinker\Frontend\Display();
}
add_action( 'plugins_loaded', 'social_linker_init' );
