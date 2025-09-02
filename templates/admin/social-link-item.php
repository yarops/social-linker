<?php
/**
 * Template for displaying a single social network in the admin panel.
 *
 * @var array $link Data of the social network.
 * @var string $id Identifier of the social network.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="social-link-item" data-id="<?php echo esc_attr( $id ); ?>">
	<div class="social-link-header">
		<h3><?php echo esc_html( $link['name'] ); ?></h3>
		<label class="social-link-toggle">
			<input type="checkbox" name="social_linker_settings[social_links][<?php echo esc_attr( $id ); ?>][enabled]" value="1" <?php checked( isset( $link['enabled'] ) ? $link['enabled'] : false, true ); ?>>
			<?php esc_html_e( 'Enable', 'social-linker' ); ?>
		</label>
	</div>
	
	<div class="social-link-content">
		<input type="hidden" name="social_linker_settings[social_links][<?php echo esc_attr( $id ); ?>][id]" value="<?php echo esc_attr( $id ); ?>">
		
		<div class="social-link-field">
			<label><?php esc_html_e( 'Name', 'social-linker' ); ?></label>
			<input type="text" name="social_linker_settings[social_links][<?php echo esc_attr( $id ); ?>][name]" value="<?php echo esc_attr( $link['name'] ); ?>">
		</div>
		
		<div class="social-link-field">
			<label><?php esc_html_e( 'URL', 'social-linker' ); ?></label>
			<input type="url" name="social_linker_settings[social_links][<?php echo esc_attr( $id ); ?>][url]" value="<?php echo esc_url( $link['url'] ); ?>" placeholder="https://">
		</div>
		
		<div class="social-link-field">
			<label><?php esc_html_e( 'Icon', 'social-linker' ); ?></label>
			<div class="social-icon-selector">
				<input type="hidden" name="social_linker_settings[social_links][<?php echo esc_attr( $id ); ?>][icon]" value="<?php echo esc_attr( $id ); ?>">
				<div class="social-icon-preview">
					<?php
					$icon_url = $this->get_icon_url( $id );
					if ( empty( $icon_url ) ) {
						$icon_url = SOCIAL_LINKER_PLUGIN_URL . 'assets/icons/link.svg';
					}

					?>
					<img src="<?php echo esc_url( $icon_url ); ?>" alt="<?php echo esc_attr( $link['name'] ); ?>" style="max-width: 32px; max-height: 32px;">
				</div>
				<?php print_r( $id . ' . svg' ); ?>
			</div>
		</div>
		
		<?php if ( strpos( $id, 'custom - ' ) === 0 ) : ?>
		<div class="social-link-remove">
			<button type="button" class="button button-link-delete remove-social-link"><?php esc_html_e( 'Remove', 'social - linker' ); ?></button>
		</div>
		<?php endif; ?>
	</div>
</div>
