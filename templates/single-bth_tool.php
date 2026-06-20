<?php
/**
 * Single Tool Template
 *
 * Renders a tool with page-style layout: title, content, sidebar.
 * No article meta, no post-style elements.
 *
 * @package ALD_Business_Tools_Hub
 */

get_header();
?>

<main class="site-content bth-tool-single" role="main">
    <div class="container">

        <div class="content-grid">

            <!-- Main Column: Tool -->
            <div class="main-column">

                <?php while ( have_posts() ) : the_post(); ?>

                <article id="tool-<?php the_ID(); ?>" <?php post_class( 'bth-tool-article' ); ?>>

                    <!-- Tool Header -->
                    <header class="bth-tool-single-header">
                        <?php
                        $tool_icon = get_post_meta( get_the_ID(), '_bth_tool_icon', true );
                        if ( ! $tool_icon ) $tool_icon = '🔧';
                        ?>
                        <span class="bth-tool-single-icon"><?php echo esc_html( $tool_icon ); ?></span>
                        <h1 class="bth-tool-single-title"><?php the_title(); ?></h1>
                        <?php if ( has_excerpt() ) : ?>
                            <p class="bth-tool-single-desc"><?php echo esc_html( get_the_excerpt() ); ?></p>
                        <?php endif; ?>
                    </header>

                    <!-- Tool Body -->
                    <div class="bth-tool-single-body">
                        <?php
                        $tool_type = get_post_meta( get_the_ID(), '_bth_tool_type', true );

                        if ( $tool_type && $tool_type !== 'custom' ) {
                            // Load the tool template
                            $tool_file = BTH_PLUGIN_DIR . 'tools/' . $tool_type . '.php';
                            if ( file_exists( $tool_file ) ) {
                                include $tool_file;
                            } else {
                                echo '<p class="bth-error">' . esc_html__( 'Tool template not found.', 'ald-business-tools' ) . '</p>';
                            }
                        } else {
                            // Custom tool — show content
                            echo '<div class="bth-tool-custom-content">';
                            the_content();
                            echo '</div>';
                        }
                        ?>
                    </div>

                </article>

                <?php endwhile; ?>

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
