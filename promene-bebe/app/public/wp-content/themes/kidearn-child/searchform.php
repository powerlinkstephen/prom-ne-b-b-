<?php
/**
 * Formulaire de recherche — Promène Bébé.
 *
 * @package PromeneBebe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$pb_search_id = 'pb-search-' . wp_unique_id();
?>
<form role="search" method="get" class="pb-searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <label class="pb-sr-only" for="<?php echo esc_attr( $pb_search_id ); ?>">
        <?php esc_html_e( 'Rechercher sur Promène Bébé', 'promenebebe' ); ?>
    </label>
    <input type="search"
           id="<?php echo esc_attr( $pb_search_id ); ?>"
           name="s"
           value="<?php echo esc_attr( get_search_query() ); ?>"
           placeholder="<?php esc_attr_e( 'Rechercher une poussette, un guide, un comparatif…', 'promenebebe' ); ?>"
           autocomplete="off">
    <button type="submit">
        <?php esc_html_e( 'Rechercher', 'promenebebe' ); ?>
    </button>
</form>
