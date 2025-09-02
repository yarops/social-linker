<?php
/**
 * Шаблон страницы настроек плагина Social Linker.
 *
 * @var array $settings Текущие настройки плагина.
 */

// Если файл вызван напрямую, прерываем выполнение
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Значения по умолчанию
$settings = wp_parse_args(
	$settings,
	[
		'enabled'      => true,
		'position'     => 'right',
		'social_links' => [],
	]
);
?>

<div class="wrap social-linker-settings">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	
	<?php if ( isset( $_GET['settings-updated'] ) ) : ?>
		<div class="notice notice-success is-dismissible">
			<p><?php esc_html_e( 'Settings saved.', 'social-linker' ); ?></p>
		</div>
	<?php endif; ?>
	
	<form method="post" action="options.php">
		<?php settings_fields( 'social_linker_settings' ); ?>
		
		<div class="social-linker-main-settings">
			<h2><?php esc_html_e( 'General Settings', 'social-linker' ); ?></h2>
			
			<table class="form-table">
				<tr>
					<th scope="row">
						<label for="social_linker_enabled"><?php esc_html_e( 'Enable Social Linker', 'social-linker' ); ?></label>
					</th>
					<td>
						<input type="checkbox" id="social_linker_enabled" name="social_linker_settings[enabled]" value="1" <?php checked( $settings['enabled'], true ); ?>>
						<p class="description"><?php esc_html_e( 'Enable or disable the floating social links.', 'social-linker' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="social_linker_position"><?php esc_html_e( 'Position', 'social-linker' ); ?></label>
					</th>
					<td>
						<select id="social_linker_position" name="social_linker_settings[position]">
							<option value="left" <?php selected( $settings['position'], 'left' ); ?>><?php esc_html_e( 'Left', 'social-linker' ); ?></option>
							<option value="right" <?php selected( $settings['position'], 'right' ); ?>><?php esc_html_e( 'Right', 'social-linker' ); ?></option>
						</select>
						<p class="description"><?php esc_html_e( 'Choose the position of the floating social links.', 'social-linker' ); ?></p>
					</td>
				</tr>
			</table>
		</div>
		
		<div class="social-linker-social-links">
			<h2><?php esc_html_e( 'Social Links', 'social-linker' ); ?></h2>
			
			<div class="social-links-container">
				<?php
				// Предопределенные социальные сети
				$predefined_networks = [
					'vk'        => [
						'id'      => 'vk',
						'name'    => 'ВКонтакте',
						'url'     => '',
						'icon'    => 'vk',
						'enabled' => true,
					],
					'facebook'  => [
						'id'      => 'facebook',
						'name'    => 'Facebook',
						'url'     => '',
						'icon'    => 'facebook',
						'enabled' => true,
					],
					'instagram' => [
						'id'      => 'instagram',
						'name'    => 'Instagram',
						'url'     => '',
						'icon'    => 'instagram',
						'enabled' => true,
					],
					'whatsapp'  => [
						'id'      => 'whatsapp',
						'name'    => 'WhatsApp',
						'url'     => '',
						'icon'    => 'whatsapp',
						'enabled' => true,
					],
					'telegram'  => [
						'id'      => 'telegram',
						'name'    => 'Telegram',
						'url'     => '',
						'icon'    => 'telegram',
						'enabled' => true,
					],
				];

				// Объединяем предопределенные сети с настройками
				$social_links = [];

				if ( ! empty( $settings['social_links'] ) ) {
					foreach ( $settings['social_links'] as $link ) {
						if ( isset( $link['id'] ) ) {
							$social_links[ $link['id'] ] = $link;
						}
					}
				}

				// Добавляем недостающие предопределенные сети
				foreach ( $predefined_networks as $id => $network ) {
					if ( ! isset( $social_links[ $id ] ) ) {
						$social_links[ $id ] = $network;
					}
				}

				// Выводим все социальные сети
				foreach ( $social_links as $id => $link ) {
					include SOCIAL_LINKER_PLUGIN_DIR . 'templates/admin/social-link-item.php';
				}
				?>
				
				<div class="social-link-actions">
					<button type="button" class="button button-secondary add-custom-network"><?php esc_html_e( 'Add Custom Network', 'social-linker' ); ?></button>
				</div>
			</div>
		</div>
		
		<?php submit_button(); ?>
	</form>
</div>

<!-- Template for a new social network -->
<script type="text/template" id="tmpl-social-link-item">
	<div class="social-link-item" data-id="{{data.id}}">
		<div class="social-link-header">
			<h3>{{data.name}}</h3>
			<label class="social-link-toggle">
				<input type="checkbox" name="social_linker_settings[social_links][{{data.id}}][enabled]" value="1" checked>
				<?php esc_html_e( 'Enable', 'social-linker' ); ?>
			</label>
		</div>
		
		<div class="social-link-content">
			<input type="hidden" name="social_linker_settings[social_links][{{data.id}}][id]" value="{{data.id}}">
			
			<div class="social-link-field">
				<label><?php esc_html_e( 'Name', 'social-linker' ); ?></label>
				<input type="text" name="social_linker_settings[social_links][{{data.id}}][name]" value="{{data.name}}">
			</div>
			
			<div class="social-link-field">
				<label><?php esc_html_e( 'URL', 'social-linker' ); ?></label>
				<input type="url" name="social_linker_settings[social_links][{{data.id}}][url]" value="" placeholder="https://">
			</div>
			
			<div class="social-link-field">
				<label><?php esc_html_e( 'Icon', 'social-linker' ); ?></label>
				<div class="social-icon-selector">
					<input type="hidden" name="social_linker_settings[social_links][{{data.id}}][icon]" value="{{data.id}}">
					<div class="social-icon-preview">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
							<path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm0 22c-5.523 0-10-4.477-10-10S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm0-15c-2.757 0-5 2.243-5 5s2.243 5 5 5 5-2.243 5-5-2.243-5-5-5zm0 8c-1.654 0-3-1.346-3-3s1.346-3 3-3 3 1.346 3 3-1.346 3-3 3z"/>
						</svg>
					</div>
					<p class="description">
						<?php esc_html_e( 'Icon is loaded automatically based on network ID', 'social-linker' ); ?>
					</p>
				</div>
			</div>
			
			<div class="social-link-remove">
				<button type="button" class="button button-link-delete remove-social-link"><?php esc_html_e( 'Remove', 'social-linker' ); ?></button>
			</div>
		</div>
	</div>
</script>