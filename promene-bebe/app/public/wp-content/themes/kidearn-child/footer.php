<?php
/**
 * Footer — Promène Bébé.
 *
 * Surcharge complète du footer parent. Inclut une zone newsletter
 * discrète, une navigation secondaire, et la barre légale.
 *
 * @package PromeneBebe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
</main><!-- #pb-main -->

<footer class="pb-footer" role="contentinfo">
    <div class="pb-container">

        <div class="pb-footer__grid">
            <div class="pb-footer__brand-col">
                <a class="pb-footer__brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <img src="<?php echo esc_url( promenebebe_logo_url() ); ?>"
                         alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
                         width="240" height="72" decoding="async" loading="lazy">
                </a>
                <p class="pb-footer__tagline">
                    <?php esc_html_e( 'Le blog d\'affiliation francophone dédié aux poussettes pour bébé. Comparatifs, guides d\'achat et conseils choisis avec soin.', 'promenebebe' ); ?>
                </p>
            </div>

            <nav class="pb-footer__nav" aria-label="<?php esc_attr_e( 'Navigation secondaire', 'promenebebe' ); ?>">
                <h2 class="pb-footer__title"><?php esc_html_e( 'Explorer', 'promenebebe' ); ?></h2>
                <?php
                if ( has_nav_menu( 'promenebebe_footer' ) ) {
                    wp_nav_menu( array(
                        'theme_location' => 'promenebebe_footer',
                        'container'      => false,
                        'menu_class'     => 'pb-footer__menu',
                        'depth'          => 1,
                        'fallback_cb'    => false,
                    ) );
                } else {
                    ?>
                    <ul class="pb-footer__menu">
                        <li><a href="<?php echo esc_url( home_url( '/comparatifs/' ) ); ?>"><?php esc_html_e( 'Comparatifs', 'promenebebe' ); ?></a></li>
                        <li><a href="<?php echo esc_url( home_url( '/guides-dachat/' ) ); ?>"><?php esc_html_e( 'Guides d\'achat', 'promenebebe' ); ?></a></li>
                        <li><a href="<?php echo esc_url( home_url( '/category/' ) ); ?>"><?php esc_html_e( 'Toutes les catégories', 'promenebebe' ); ?></a></li>
                    </ul>
                    <?php
                }
                ?>
            </nav>

            <div class="pb-footer__newsletter">
                <h2 class="pb-footer__title"><?php esc_html_e( 'Notre lettre poussette', 'promenebebe' ); ?></h2>
                <p class="pb-footer__desc">
                    <?php esc_html_e( 'Un e-mail par mois, des comparatifs et guides utiles. Zéro spam, désabonnement en un clic.', 'promenebebe' ); ?>
                </p>
                <?php get_template_part( 'template-parts/newsletter' ); ?>
            </div>
        </div>

        <div class="pb-footer__bottom">
            <p>&copy; <?php echo esc_html( date_i18n( 'Y' ) ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?>. <?php esc_html_e( 'Tous droits réservés.', 'promenebebe' ); ?></p>
            <ul class="pb-footer__legal">
                <li><a href="<?php echo esc_url( home_url( '/mentions-legales/' ) ); ?>"><?php esc_html_e( 'Mentions légales', 'promenebebe' ); ?></a></li>
                <li><a href="<?php echo esc_url( home_url( '/politique-de-confidentialite/' ) ); ?>"><?php esc_html_e( 'Politique de confidentialité', 'promenebebe' ); ?></a></li>
                <li><a href="<?php echo esc_url( home_url( '/divulgation-affiliation/' ) ); ?>"><?php esc_html_e( 'Divulgation d\'affiliation', 'promenebebe' ); ?></a></li>
            </ul>
        </div>

    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
