<?php
/**
 * Formulaire newsletter — Promène Bébé.
 *
 * Discret, sans popup. Le service (MailerLite, Brevo, etc.) n'est PAS
 * encore arrêté : on expose un formulaire neutre dont l'action est
 * filtrable via `promenebebe_newsletter_action` et `promenebebe_newsletter_fields`.
 *
 * Quand le service sera choisi :
 *   - Définir l'URL d'endpoint du fournisseur dans le filtre `promenebebe_newsletter_action`
 *   - Compléter les noms de champs attendus par le fournisseur dans `promenebebe_newsletter_fields`
 *   - Ou installer le plugin officiel du fournisseur et remplacer ce template
 *
 * Tant que `promenebebe_newsletter_action` retourne une chaîne vide,
 * le formulaire affiche un message d'attente plutôt qu'une soumission HTTP.
 *
 * @package PromeneBebe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$action_url = (string) apply_filters( 'promenebebe_newsletter_action', '' );
$fields     = apply_filters( 'promenebebe_newsletter_fields', array(
    'email' => 'email',
) );
?>

<?php if ( '' === $action_url ) : ?>
    <p class="pb-newsletter__pending">
        <?php esc_html_e( 'L\'inscription à la lettre poussette sera disponible très bientôt. Le service est en cours de configuration.', 'promenebebe' ); ?>
    </p>
<?php else : ?>
    <form class="pb-newsletter__form"
          action="<?php echo esc_url( $action_url ); ?>"
          method="post"
          target="_blank"
          novalidate>
        <label class="pb-sr-only" for="pb-newsletter-email"><?php esc_html_e( 'Votre adresse e-mail', 'promenebebe' ); ?></label>
        <input class="pb-input"
               type="email"
               id="pb-newsletter-email"
               name="<?php echo esc_attr( $fields['email'] ); ?>"
               required
               autocomplete="email"
               placeholder="<?php esc_attr_e( 'vous@example.com', 'promenebebe' ); ?>">
        <?php
        /**
         * Hook : permet d'ajouter des champs cachés (consent, list_id, etc.)
         * spécifiques au fournisseur de newsletter quand il sera défini.
         */
        do_action( 'promenebebe_newsletter_hidden_fields' );
        ?>
        <button class="pb-btn pb-btn--alt" type="submit">
            <?php esc_html_e( 'S\'inscrire', 'promenebebe' ); ?>
        </button>
    </form>
    <p class="pb-newsletter__legal">
        <?php
        printf(
            /* translators: %s : lien vers la politique de confidentialité */
            esc_html__( 'En vous inscrivant, vous acceptez notre %s. Vous pouvez vous désabonner à tout moment.', 'promenebebe' ),
            '<a href="' . esc_url( home_url( '/politique-de-confidentialite/' ) ) . '">'
            . esc_html__( 'politique de confidentialité', 'promenebebe' )
            . '</a>'
        );
        ?>
    </p>
<?php endif; ?>
