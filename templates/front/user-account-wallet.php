<?php

declare( strict_types=1 );
defined( 'ABSPATH' ) || exit;
/**
 * Variables used in this file.
 * @var array $args Array of meta data.
 */
?>

<?php if ( ! empty( $args['token'] ) ) : ?>
    <div id="PaylineWidget" data-token="<?php echo esc_attr( $args['token'] ); ?>" data-template="tab"></div>

    <script>Payline.Api.init();</script>
<?php else : ?>    
    <p>Impossible de récuperer les infos du wallet</p>
<?php endif; ?>
