<?php
/**
 * Single Tool Template
 *
 * @var WP_Post $tool
 * @var string  $tool_type
 */

$tool_type = get_post_meta( $tool->ID, '_bth_tool_type', true );
if ( ! $tool_type ) {
    $tool_type = 'custom';
}

$tool_icon = get_post_meta( $tool->ID, '_bth_tool_icon', true );
if ( ! $tool_icon ) {
    $tool_icon = '🔧';
}
?>
<div class="bth-tool-wrapper" data-tool-type="<?php echo esc_attr( $tool_type ); ?>">
    <div class="bth-tool-header">
        <span class="bth-tool-icon"><?php echo esc_html( $tool_icon ); ?></span>
        <h2 class="bth-tool-title"><?php echo esc_html( $tool->post_title ); ?></h2>
    </div>

    <?php if ( $tool->post_excerpt ) : ?>
        <p class="bth-tool-description"><?php echo esc_html( $tool->post_excerpt ); ?></p>
    <?php endif; ?>

    <div class="bth-tool-body">
        <?php
        switch ( $tool_type ) {
            case 'currency-converter':
                include BTH_PLUGIN_DIR . 'tools/currency-converter.php';
                break;
            case 'profit-calculator':
                include BTH_PLUGIN_DIR . 'tools/profit-calculator.php';
                break;
            case 'qr-generator':
                include BTH_PLUGIN_DIR . 'tools/qr-generator.php';
                break;
            case 'meta-tag-generator':
                include BTH_PLUGIN_DIR . 'tools/meta-tag-generator.php';
                break;
            case 'invoice-generator':
                include BTH_PLUGIN_DIR . 'tools/invoice-generator.php';
                break;
            case 'color-palette':
                include BTH_PLUGIN_DIR . 'tools/color-palette.php';
                break;
            case 'image-compressor':
                include BTH_PLUGIN_DIR . 'tools/image-compressor.php';
                break;
            case 'image-resizer':
                include BTH_PLUGIN_DIR . 'tools/image-resizer.php';
                break;
            case 'image-converter':
                include BTH_PLUGIN_DIR . 'tools/image-converter.php';
                break;
            case 'background-remover':
                include BTH_PLUGIN_DIR . 'tools/background-remover.php';
                break;
            case 'utm-builder':
                include BTH_PLUGIN_DIR . 'tools/utm-builder.php';
                break;
            default:
                // Custom tool — just show the content
                echo '<div class="bth-tool-custom-content">';
                echo apply_filters( 'the_content', $tool->post_content );
                echo '</div>';
                break;
        }
        ?>
    </div>
</div>
