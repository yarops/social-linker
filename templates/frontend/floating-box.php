<?php
/**
 * Links box template frontend.
 *
 * @var array $active_links Links array.
 * @var SocialLinker\Frontend\Display $this Display instance.
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="social-linker social-linker--position-<?php echo esc_attr( isset( $this->settings['position'] ) ? $this->settings['position'] : 'right' ); ?>">
	<div class="social-linker-toggle">
		<span class="social-linker-toggle-icon">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
				<path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92s2.92-1.31 2.92-2.92c0-1.61-1.31-2.92-2.92-2.92zM18 4c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zM6 13c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm12 7.02c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1z"/>
			</svg>
		</span>
	</div>
	
	<div class="social-linker-links">
		<?php foreach ( $active_links as $s_link ) : ?>
		<a 
			href="<?php echo esc_url( $s_link['url'] ); ?>" 
			target="_blank" 
			rel="noopener noreferrer" 
			class="social-linker-link social-linker-<?php echo esc_attr( $s_link['id'] ); ?>" 
			title="<?php echo esc_attr( $s_link['name'] ); ?>"
		>
			<?php echo wp_kses( $this->get_icon( $s_link ), 'post' ); ?>
			<span class="social-linker-label"><?php echo esc_html( $s_link['name'] ); ?></span>
		</a>
		<?php endforeach; ?>
	</div>
</div>
