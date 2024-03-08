<?php
/*
Plugin Name: Related Posts
Description: Display related posts under each post based on category.
Version: 1.0
Author: Web Relaxer - Shamim
*/

class WR_Related_Posts {
    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
        add_action('the_content', array($this, 'display_related_posts'));
    }

    public function enqueue_styles() {
        // Enqueue stylesheets
        wp_enqueue_style('related-posts-style', plugin_dir_url(__FILE__) . 'related-posts.css');
    }

    public function display_related_posts($content) {
        // Logic to display related posts
        if (is_single()) {
            $related_posts = $this->get_related_posts();
            if ($related_posts) {
                $content .= '<div class="related-posts">';
                $content .= '<h3>Related Posts</h3>';
                $content .= '<div class="related-posts-grid">';
                foreach ($related_posts as $post) {
                    $content .= '<div class="related-post">';
                    $content .= '<a href="' . get_permalink($post->ID) . '">';
                    $content .= get_the_post_thumbnail($post->ID, 'thumbnail');
                    $content .= '<span class="related-post-title">' . esc_html($post->post_title) . '</span>';
                    $content .= '</a>';
                    $content .= '</div>';
                }
                $content .= '</div>'; // close related-posts-grid
                $content .= '</div>'; // close related-posts
            }
        }
        return $content;
    }

    public function get_related_posts() {
        // Logic to retrieve related posts
        global $post;
        $related_posts = array();

        if ($post) {
            $categories = wp_get_post_categories($post->ID);
            $args = array(
                'post__not_in' => array($post->ID),
                'posts_per_page' => 5,
                'category__in' => $categories,
                'orderby' => 'rand'
            );
            $related_posts_query = new WP_Query($args);
            if ($related_posts_query->have_posts()) {
                while ($related_posts_query->have_posts()) {
                    $related_posts_query->the_post();
                    $related_posts[] = $post;
                }
            }
            wp_reset_postdata();
        }

        return $related_posts;
    }
}

new WR_Related_Posts();
