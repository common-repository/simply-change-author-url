<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://profiles.wordpress.org/dev_vahid/
 * @since      1.0.0
 *
 * @package    Simply_Change_Author_Url
 * @subpackage Simply_Change_Author_Url/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Simply_Change_Author_Url
 * @subpackage Simply_Change_Author_Url/includes
 * @author     vahid <hello@vahidmohammadi.me>
 */
class Simply_Change_Author_Url
{
    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $plugin_name The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $version The current version of the plugin.
     */
    protected $version;

    /**
     * The Author Base Url.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $newAuthorBase The Author Base Url.
     */
    protected $newAuthorBase;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        $this->version = SIMPLY_CHANGE_AUTHOR_URL_VERSION;
        $this->plugin_name = 'simply-change-author-url';
        /**
         * Author base
         */
        $this->newAuthorBase = 'user';
    }

    /**
     * add require hooks and filters
     *
     * @since    1.0.0
     */
    public function run()
    {
        add_action('init', [$this, 'set_author_base'], 1, 0);
        add_action('init', [$this, 'change_author_base']);
        add_action('init', [$this, 'author_rewrite_rule']);
        add_action('init', [$this, 'flush_if_changed'], 99, 0);
        add_filter('body_class', [$this, 'filter_body_class'], 99, 1);
        add_filter('author_link', [$this, 'customize_author_link'], 10, 2);
        add_filter('rest_endpoints', [$this, 'remove_default_user_endpoints'], PHP_INT_MAX, 1);
        add_filter('wpseo_canonical', [$this, 'yoast_canonical']);
        add_filter('wpseo_next_rel_link', [$this, 'yoast_canonical']);
        add_filter('wpseo_prev_rel_link', [$this, 'yoast_canonical']);
    }

    /**
     * change author_base
     *
     * @since    1.0.0
     */
    public function change_author_base()
    {
        //change author/username base to users/userID
        global $wp_rewrite;
        // Change the value of the author permalink base
        $wp_rewrite->author_base = $this->newAuthorBase;
    }

    /**
     * add required rewrite rules to handle requests
     *
     * @since    1.0.0
     */
    public function author_rewrite_rule()
    {
        add_rewrite_rule("^{$this->newAuthorBase}\/(\d+)\/?$", 'index.php?author=$matches[1]', 'top');
        add_rewrite_rule("^{$this->newAuthorBase}\/(\d+)\/page\/(\d+)\/?", 'index.php?author=$matches[1]&paged=$matches[2]', 'top');
    }

    /**
     * flush wordpress rewrite rules after plugin activation
     *
     * @since    1.0.0
     */
    public function flush_if_changed()
    {
        if (get_transient('simply_change_author_url_changed')) {
            delete_transient('simply_change_author_url_changed');
            flush_rewrite_rules();
        }
    }

    /**
     * return new author url instead of wordpress default url
     *
     * @param $link
     * @param $authorID
     *
     * @return string
     */
    public function customize_author_link($link, $authorID)
    {
        return get_home_url() . '/' . $this->newAuthorBase . '/' . $authorID . '/';
    }

    /**
     * removes the default wordpress user endpoints to avoid leaking the
     * usernames.
     *
     * @param $endpoints
     *
     * @return mixed
     *
     * @since    1.1.0
     */
    public function remove_default_user_endpoints($endpoints)
    {
        unset($endpoints['/wp/v2/users']);
        unset($endpoints['/wp/v2/users/(?P[\\d]+)']);
        unset($endpoints['/wp/v2/users/me']);

        return $endpoints;
    }

    /**
     * change yoast canonical urls in author page
     *
     * @param $canonical_url
     *
     * @return string|string[]
     *
     * @since    1.1.0
     */
    public function yoast_canonical($canonical_url)
    {
        if (!is_admin() && is_author()) {
            $id = get_the_author_meta('ID');
            $nickname = sanitize_title(get_the_author_meta('nickname', $id));

            $canonical_url = str_ireplace('author', $this->newAuthorBase, $canonical_url);
            $canonical_url = str_ireplace($nickname, $id, $canonical_url);
        }

        return $canonical_url;
    }

    /**
     * allows filtering author base, but applying a filter to it via `init` hook.
     *
     * @since    1.1.2
     */
    public function set_author_base()
    {
        /**
         * Change author base via this filter
         */
        $this->newAuthorBase = apply_filters('simply_change_author_url_author_base', 'user');
    }

    /**
     * removes the username from body classes.
     * @param  string[] $classes
     *
     * @return string[]
     *
     * @since    1.1.2
     */
    public function filter_body_class($classes)
    {
        if (is_author()) {
            $username = 'author-' . get_the_author_meta('login');
            $key = array_search($username, $classes);
            if ($key) {
                unset($classes[$key]);
            }
        }

        return $classes;
    }
}
