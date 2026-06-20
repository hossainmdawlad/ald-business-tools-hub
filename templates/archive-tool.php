<?php
/**
 * Tools Archive / Grid Template
 *
 * @var WP_Query $tools
 * @var array    $atts
 */

$columns = absint( $atts['columns'] ?? 3 );
$col_class = 'bth-col-' . $columns;
?>
<div class="bth-tools-grid <?php echo esc_attr( $col_class ); ?>">
    <?php while ( $tools->have_posts() ) : $tools->the_post();
        $tool_type = get_post_meta( get_the_ID(), '_bth_tool_type', true );
        $tool_icon = get_post_meta( get_the_ID(), '_bth_tool_icon', true );
        if ( ! $tool_icon ) $tool_icon = '🔧';
        $tool_desc = get_the_excerpt();
    ?>
    <div class="bth-tool-card">
        <a href="<?php the_permalink(); ?>" class="bth-tool-card-link">
            <span class="bth-tool-card-icon"><?php echo esc_html( $tool_icon ); ?></span>
            <h3 class="bth-tool-card-title"><?php the_title(); ?></h3>
            <?php if ( $tool_desc ) : ?>
                <p class="bth-tool-card-desc"><?php echo esc_html( wp_trim_words( $tool_desc, 15 ) ); ?></p>
            <?php endif; ?>
        </a>
        <?php
        $categories = get_the_terms( get_the_ID(), 'bth_tool_category' );
        if ( $categories && ! is_wp_error( $categories ) ) :
        ?>
        <div class="bth-tool-card-cats">
            <?php foreach ( $categories as $cat ) : ?>
                <span class="bth-cat-badge"><?php echo esc_html( $cat->name ); ?></span>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
    <?php endwhile; wp_reset_postdata(); ?>
</div>
