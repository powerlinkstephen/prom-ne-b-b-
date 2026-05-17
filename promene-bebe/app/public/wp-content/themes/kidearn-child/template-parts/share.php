<?php
/**
 * Boutons de partage social — Promène Bébé.
 *
 * Boutons "no-tracking" (URL d'intent directe sans JS tiers). Réseaux ciblés
 * par le CDC : Facebook, Pinterest, WhatsApp.
 *
 * @package PromeneBebe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$share_url   = rawurlencode( get_permalink() );
$share_title = rawurlencode( wp_strip_all_tags( get_the_title() ) );
$share_image = '';
if ( has_post_thumbnail() ) {
    $img_src = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );
    if ( ! empty( $img_src[0] ) ) {
        $share_image = rawurlencode( $img_src[0] );
    }
}

$networks = array(
    'facebook' => array(
        'label' => __( 'Partager sur Facebook', 'promenebebe' ),
        'url'   => 'https://www.facebook.com/sharer/sharer.php?u=' . $share_url,
        'icon'  => 'M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.879v-6.987H7.898V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.77-1.63 1.562V12h2.773l-.443 2.892h-2.33v6.987C18.343 21.128 22 16.991 22 12Z',
    ),
    'pinterest' => array(
        'label' => __( 'Épingler sur Pinterest', 'promenebebe' ),
        'url'   => 'https://pinterest.com/pin/create/button/?url=' . $share_url
                  . '&media=' . $share_image
                  . '&description=' . $share_title,
        'icon'  => 'M12 2C6.477 2 2 6.477 2 12c0 4.236 2.636 7.855 6.356 9.312-.088-.79-.167-2.002.035-2.864.182-.78 1.176-4.97 1.176-4.97s-.3-.6-.3-1.49c0-1.394.81-2.435 1.816-2.435.857 0 1.27.643 1.27 1.413 0 .86-.547 2.146-.83 3.34-.236 1 .5 1.815 1.487 1.815 1.785 0 3.16-1.883 3.16-4.6 0-2.405-1.728-4.087-4.196-4.087-2.858 0-4.535 2.144-4.535 4.36 0 .863.332 1.788.748 2.292.082.1.094.187.069.288-.075.314-.243.99-.276 1.128-.043.183-.142.222-.328.134-1.224-.57-1.989-2.359-1.989-3.798 0-3.09 2.243-5.927 6.469-5.927 3.395 0 6.034 2.42 6.034 5.654 0 3.374-2.127 6.09-5.082 6.09-.992 0-1.924-.515-2.243-1.124l-.61 2.327c-.221.853-.819 1.92-1.219 2.572.918.284 1.892.438 2.904.438 5.522 0 10-4.477 10-10S17.523 2 12 2Z',
    ),
    'whatsapp' => array(
        'label' => __( 'Partager sur WhatsApp', 'promenebebe' ),
        'url'   => 'https://api.whatsapp.com/send?text=' . $share_title . '%20' . $share_url,
        'icon'  => 'M20.52 3.48A11.86 11.86 0 0 0 12.06 0C5.54 0 .23 5.31.23 11.83c0 2.09.55 4.13 1.6 5.93L0 24l6.4-1.68a11.83 11.83 0 0 0 5.66 1.44h.01c6.52 0 11.83-5.31 11.83-11.83 0-3.16-1.23-6.13-3.38-8.45ZM12.06 21.7h-.01a9.85 9.85 0 0 1-5.02-1.37l-.36-.21-3.8 1 1.02-3.7-.24-.38a9.86 9.86 0 0 1-1.5-5.21c0-5.43 4.42-9.85 9.85-9.85 2.63 0 5.1 1.02 6.96 2.88a9.78 9.78 0 0 1 2.88 6.97c0 5.43-4.42 9.85-9.85 9.85Zm5.4-7.39c-.3-.15-1.74-.86-2.01-.96-.27-.1-.46-.15-.65.15-.2.3-.75.96-.92 1.15-.17.2-.34.22-.63.07-.3-.15-1.25-.46-2.38-1.46a8.87 8.87 0 0 1-1.64-2.04c-.17-.3-.02-.46.13-.6.13-.13.3-.34.45-.51.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.07-.15-.65-1.56-.9-2.14-.24-.57-.48-.49-.65-.5l-.55-.01c-.2 0-.5.07-.76.37-.27.3-1.02 1-1.02 2.43 0 1.43 1.05 2.81 1.2 3 .15.2 2.07 3.16 5.02 4.43.7.3 1.25.48 1.68.62.7.22 1.34.19 1.85.12.56-.08 1.74-.71 1.98-1.39.25-.68.25-1.27.17-1.39-.07-.12-.27-.2-.56-.34Z',
    ),
);
?>
<aside class="pb-share" aria-label="<?php esc_attr_e( 'Partager cet article', 'promenebebe' ); ?>">
    <p class="pb-share__label"><?php esc_html_e( 'Partager', 'promenebebe' ); ?></p>
    <ul class="pb-share__list">
        <?php foreach ( $networks as $key => $net ) : ?>
            <li>
                <a class="pb-share__btn pb-share__btn--<?php echo esc_attr( $key ); ?>"
                   href="<?php echo esc_url( $net['url'] ); ?>"
                   target="_blank"
                   rel="noopener nofollow"
                   aria-label="<?php echo esc_attr( $net['label'] ); ?>">
                    <svg aria-hidden="true" viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                        <path d="<?php echo esc_attr( $net['icon'] ); ?>"></path>
                    </svg>
                    <span class="pb-sr-only"><?php echo esc_html( $net['label'] ); ?></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</aside>
