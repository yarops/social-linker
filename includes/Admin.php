<?php

namespace SocialLinker;

/**
 * Класс для административной части плагина.
 */
class Admin {

	/**
	 * Конструктор класса.
	 */
	public function __construct() {
		// Добавляем пункт меню в админке
		add_action( 'admin_menu', [ $this, 'add_admin_menu' ] );

		// Регистрируем настройки
		add_action( 'admin_init', [ $this, 'register_settings' ] );

		// Добавляем ссылку на настройки на странице плагинов
		add_filter( 'plugin_action_links_' . SOCIAL_LINKER_PLUGIN_BASENAME, [ $this, 'add_settings_link' ] );

		// Подключаем скрипты и стили для админки
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_assets' ] );
	}

	/**
	 * Добавляет пункт меню в админке.
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
	 * Регистрирует настройки плагина.
	 */
	public function register_settings() {
		register_setting( 'social_linker_settings', 'social_linker_settings', [ $this, 'sanitize_settings' ] );
	}

	/**
	 * Валидирует и очищает настройки перед сохранением.
	 *
	 * @param array $input Входные данные формы.
	 * @return array Очищенные данные.
	 */
	public function sanitize_settings( $input ) {
		$sanitized = [];

		// Основные настройки
		$sanitized['enabled']  = isset( $input['enabled'] ) ? true : false;
		$sanitized['position'] = isset( $input['position'] ) ? sanitize_text_field( $input['position'] ) : 'right';

		// Социальные сети
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
	 * Добавляет ссылку на настройки на странице плагинов.
	 *
	 * @param array $links Массив ссылок.
	 * @return array Обновленный массив ссылок.
	 */
	public function add_settings_link( $links ) {
		$settings_link = '<a href="admin.php?page=social-linker">' . __( 'Settings', 'social-linker' ) . '</a>';
		array_unshift( $links, $settings_link );
		return $links;
	}

	/**
	 * Подключает скрипты и стили для админки.
	 *
	 * @param string $hook Текущая страница админки.
	 */
	public function enqueue_admin_assets( $hook ) {
		if ( 'toplevel_page_social-linker' !== $hook ) {
			return;
		}

		// Стили для админки
		wp_enqueue_style(
			'social-linker-admin',
			SOCIAL_LINKER_PLUGIN_URL . 'assets/styles/admin.css',
			[],
			SOCIAL_LINKER_VERSION
		);

		// Скрипты для админки
		wp_enqueue_script(
			'social-linker-admin',
			SOCIAL_LINKER_PLUGIN_URL . 'assets/scripts/admin.js',
			[ 'jquery', 'jquery-ui-sortable' ],
			SOCIAL_LINKER_VERSION,
			true
		);

		// Добавляем медиа-загрузчик WordPress
		wp_enqueue_media();
	}

	/**
	 * Отображает страницу настроек.
	 */
	public function render_settings_page() {
		// Получаем текущие настройки
		$settings = get_option( 'social_linker_settings', [] );

		// Подключаем шаблон страницы настроек
		include SOCIAL_LINKER_PLUGIN_DIR . 'templates/admin/admin-settings.php';
	}
}
