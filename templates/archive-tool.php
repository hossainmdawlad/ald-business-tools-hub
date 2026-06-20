<?php
/**
 * Tools Archive / Category Template
 *
 * @package ALD_Business_Tools_Hub
 */

get_header();
?>

<main class="site-content bth-tools-archive" role="main">
    <div class="container">

        <div class="content-grid">

            <!-- Main Column -->
            <div class="main-column">

                <header class="bth-archive-header">
                    <?php
                    if ( is_tax( 'bth_tool_category' ) ) {
                        $term = get_queried_object();
                        echo '<h1 class="bth-archive-title">' . esc_html( $term->name ) . '</h1>';
                        if ( $term->description ) {
                            echo '<p class="bth-archive-desc">' . esc_html( $term->description ) . '</p>';
                        }
                    } else {
                        echo '<h1 class="bth-archive-title">' . esc_html__( 'Business Tools', 'ald-business-tools' ) . '</h1>';
                        echo '<p class="bth-archive-desc">' . esc_html__( 'Free online tools to help grow your business.', 'ald-business-tools' ) . '</p>';
                    }
                    ?>
                </header>

                <!-- Category Filter -->
                <div class="bth-category-filter">
                    <?php
                    $categories = get_terms( array(
                        'taxonomy'   => 'bth_tool_category',
                        'hide_empty' => true,
                    ) );
                    if ( $categories && ! is_wp_error( $categories ) ) :
                    ?>
                    <ul class="bth-cat-list">
                        <li><a href="<?php echo esc_url( get_post_type_archive_link( 'bth_tool' ) ); ?>" class="<?php echo ! is_tax() ? 'active' : ''; ?>"><?php esc_html_e( 'All', 'ald-business-tools' ); ?></a></li>
                        <?php foreach ( $categories as $cat ) : ?>
                            <li><a href="<?php echo esc_url( get_term_link( $cat ) ); ?>" class="<?php echo is_tax( 'bth_tool_category', $cat->term_id ) ? 'active' : ''; ?>"><?php echo esc_html( $cat->name ); ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                </div>

                <!-- Tools Grid -->
                <div class="bth-tools-grid bth-col-3">
                    <?php if ( have_posts() ) : ?>
                        <?php while ( have_posts() ) : the_post();
                            $tool_type = get_post_meta( get_the_ID(), '_bth_tool_type', true );
                            $tool_icon = get_post_meta( get_the_ID(), '_bth_tool_icon', true );
                            if ( ! $tool_icon ) $tool_icon = '🔧';
                        ?>
                        <div class="bth-tool-card">
                            <a href="<?php the_permalink(); ?>" class="bth-tool-card-link">
                                <span class="bth-tool-card-icon"><?php echo esc_html( $tool_icon ); ?></span>
                                <h3 class="bth-tool-card-title"><?php the_title(); ?></h3>
                                <?php if ( has_excerpt() ) : ?>
                                    <p class="bth-tool-card-desc"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 12 ) ); ?></p>
                                <?php endif; ?>
                            </a>
                        </div>
                        <?php endwhile; ?>

                        <div class="bth-pagination">
                            <?php the_posts_pagination( array(
                                'mid_size'  => 2,
                                'prev_text' => '←',
                                'next_text' => '→',
                            ) ); ?>
                        </div>

                    <?php else : ?>
                        <p class="bth-error"><?php esc_html_e( 'No tools found.', 'ald-business-tools' ); ?></p>
                    <?php endif; ?>
                </div>

            </div>

            <!-- Sidebar -->
            <aside class="sidebar-column">
                <?php get_sidebar(); ?>
            </aside>

        </div>

    </div>
</main>

<?php
get_footer();
