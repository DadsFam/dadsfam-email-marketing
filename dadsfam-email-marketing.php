<?php
/**
 * Plugin Name: DadsFam Email Marketing
 * Plugin URI: https://www.dadsfam.co.za
 * Description: Professional email marketing plugin - subscribers, campaigns, WooCommerce import, custom social links, test emails, attachments, changelog.
 * Version: 3.3.0
 * Author: DadsFam
 * Author URI: https://www.dadsfam.co.za
 * License: GPL v2 or later
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'DFEM_VERSION',        '3.3.0' );
define( 'DFEM_LICENSE_SERVER', 'https://www.dadsfam.co.za/wp-json/dfem-licenses/v1/verify' );
define( 'DFEM_TRACK_OPEN',     'open' );
define( 'DFEM_TRACK_CLICK',    'click' );

/* =========================================================
   CACHE-BUSTER — fires very early, prevents SpeedyCache,
   WP Rocket, W3 Total Cache, etc. from caching admin pages
   or admin-ajax (which causes false "Session expired")
   ========================================================= */
add_action('plugins_loaded', 'dfem_set_donotcache_constants', 1);
function dfem_set_donotcache_constants() {
    $is_dfem_admin = is_admin() && isset($_GET['page']) && strpos((string)$_GET['page'], 'dfem-') === 0;
    $is_heartbeat  = wp_doing_ajax() && isset($_REQUEST['action']) && $_REQUEST['action'] === 'heartbeat';
    $is_dfem_post  = isset($_REQUEST['action']) && strpos((string)$_REQUEST['action'], 'dfem_') === 0;
    if ( $is_dfem_admin || $is_heartbeat || $is_dfem_post ) {
        if ( ! defined( 'DONOTCACHEPAGE' ) )   define( 'DONOTCACHEPAGE',   true );
        if ( ! defined( 'DONOTCACHEOBJECT' ) ) define( 'DONOTCACHEOBJECT', true );
        if ( ! defined( 'DONOTCACHEDB' ) )     define( 'DONOTCACHEDB',     true );
    }
}

// Force no-cache headers for all DFEM admin pages — fires before any output,
// overrides SpeedyCache LBC and any other cache plugin that may have set stale headers
add_action('admin_init', 'dfem_force_nocache_headers', 1);
function dfem_force_nocache_headers() {
    if ( empty( $_GET['page'] ) ) return;
    $page = (string) $_GET['page'];
    if ( strpos( $page, 'dfem-' ) !== 0 && strpos( $page, 'dflm-' ) !== 0 ) return;
    header( 'Cache-Control: no-store, no-cache, must-revalidate, max-age=0' );
    header( 'Pragma: no-cache' );
    header( 'Expires: Thu, 01 Jan 1970 00:00:00 GMT' );
}

/* =========================================================
   CHANGELOG DATA
   ========================================================= */
function dfem_get_changelog() {
    return [
        [
            'version' => '3.3.0',
            'date'    => '2026-05-07',
            'label'   => 'major',
            'changes' => [
                '🚀 Premium: Campaign Scheduling — schedule campaigns to send at a future date and time automatically via WP Cron',
                '🚀 Premium: Open & Click Tracking — track who opened your emails and which links they clicked, with per-campaign stats',
                '✅ Free: CSV Import — bulk import subscribers from a CSV file with auto column mapping and duplicate detection',
                '🚀 Premium: Excel/CSV Export — export your full subscriber list to a formatted Excel-compatible file',
                '🛠 Fixed: Settings save "link has expired" error — caused by nested HTML form tags (License form inside main settings form). Settings page completely restructured to render only the active tab per page load — no nested forms possible',
                '✅ New: Export tab added to Settings for subscriber CSV/Excel export',
            ],
        ],
        [
            'version' => '3.2.3',
            'date'    => '2026-05-04',
            'label'   => 'new',
            'changes' => [
                '✅ Added License tab to Settings — activate your own DadsFam Premium key to unlock premium features',
                '✅ Premium badge displays in header when a valid key is active',
                '✅ License verification calls dadsfam.co.za and caches result for 7 days',
            ],
        ],
        [
            'version' => '3.2.2',
            'date'    => '2026-05-04',
            'label'   => 'fix',
            'changes' => [
                '🛠 Fixed campaign bulk delete — nested HTML form tags caused "Delete Selected" to single-delete the first campaign instead of all checked ones',
                '🛠 Individual campaign delete button now uses a separate form outside the bulk form, preventing HTML nesting conflicts',
            ],
        ],
        [
            'version' => '3.2.1',
            'date'    => '2026-05-04',
            'label'   => 'fix',
            'changes' => [
                '🛠 Edit Draft now includes Attachments section — upload, preview and remove files just like New Campaign',
                '🛠 Edit Draft now includes Send Test Email — test before sending to subscribers',
                '🛠 Campaigns list no longer breaks on older installs — group_id column is checked before JOIN query',
                '🛠 Edit Draft form now uses enctype="multipart/form-data" so file uploads work correctly',
            ],
        ],
        [
            'version' => '3.2.0',
            'date'    => '2026-05-04',
            'label'   => 'new',
            'changes' => [
                '✅ Edit Draft now includes Group selector — send saved drafts to a specific group or all subscribers',
                '✅ Campaigns list now paginates (20 per page) with page navigation',
                '✅ Changelog tab added to Settings — tracks all versions and updates',
                '✅ Campaigns page shows pagination with page numbers, prev/next controls',
            ],
        ],
        [
            'version' => '3.1.3',
            'date'    => '2025-06-15',
            'label'   => 'fix',
            'changes' => [
                '🛠 Fixed bulk assign group alert CSS class typo (dfim → dfem)',
                '🛠 Improved single-delete form nesting to avoid HTML validation errors',
                '🛠 from_email and from_name filters now only apply during plugin sends',
            ],
        ],
        [
            'version' => '3.1.0',
            'date'    => '2025-05-20',
            'label'   => 'feature',
            'changes' => [
                '✅ Added bulk group assignment for subscribers',
                '✅ Added group filter dropdown to subscriber list',
                '✅ Added "Unassigned" filter option for subscribers with no group',
                '✅ Subscriber list now paginates (20 per page)',
                '✅ Send campaign to specific group (not just all subscribers)',
            ],
        ],
        [
            'version' => '3.0.0',
            'date'    => '2025-04-10',
            'label'   => 'major',
            'changes' => [
                '🚀 Major redesign — new card-based UI with gradient header',
                '✅ Added logo uploader via WP Media Library',
                '✅ Added custom social/footer links (any label + URL)',
                '✅ Added email attachments support in campaigns',
                '✅ Added test email send before publishing campaign',
                '✅ Added draft save/edit/send workflow',
                '✅ Added campaign bulk delete',
                '✅ Added email preview (rendered HTML)',
                '✅ Added personalisation tags: {{first_name}}, {{last_name}}, {{email}}, {{business}}',
                '✅ Added WooCommerce customer import with group assignment',
            ],
        ],
        [
            'version' => '2.0.0',
            'date'    => '2025-02-01',
            'label'   => 'major',
            'changes' => [
                '🚀 Added subscriber groups',
                '✅ Added unsubscribe shortcode and auto-page creation on activation',
                '✅ Added bulk delete for subscribers',
                '✅ Added subscriber search and status filter',
            ],
        ],
        [
            'version' => '1.0.0',
            'date'    => '2025-01-01',
            'label'   => 'initial',
            'changes' => [
                '🚀 Initial release',
                '✅ Subscriber management (add, edit, delete)',
                '✅ Basic campaign send to all subscribers',
                '✅ From name / from email settings',
                '✅ HTML email template',
            ],
        ],
    ];
}

/* =========================================================
   FREEMIUM LICENSE SYSTEM
   ========================================================= */
function dfem_get_license_key() {
    return trim( get_option( 'dfem_license_key', '' ) );
}

function dfem_is_premium() {
    $status = get_transient( 'dfem_license_status' );
    if ( $status === 'valid'   ) return true;
    if ( $status === 'invalid' ) return false;

    $key = dfem_get_license_key();
    if ( empty( $key ) ) {
        set_transient( 'dfem_license_status', 'invalid', WEEK_IN_SECONDS );
        return false;
    }

    // Detect loopback: if license server is THIS site, query DB directly
    $server_host  = parse_url( DFEM_LICENSE_SERVER, PHP_URL_HOST );
    $current_host = parse_url( home_url(), PHP_URL_HOST );
    $is_loopback  = $server_host && $current_host && (
        $server_host === $current_host ||
        'www.' . $current_host === $server_host ||
        $current_host === 'www.' . $server_host
    );

    if ( $is_loopback ) {
        // Owner site: verify key directly in local DB — no HTTP calls ever
        global $wpdb;
        $prefix  = $wpdb->prefix;
        $license = $wpdb->get_row( $wpdb->prepare(
            "SELECT status FROM {$prefix}dflm_licenses WHERE license_key=%s AND status='active' LIMIT 1",
            $key
        ));
        $valid  = ! empty( $license );
        $status = $valid ? 'valid' : 'invalid';
        set_transient( 'dfem_license_status', $status, WEEK_IN_SECONDS );
        update_option( 'dfem_license_status_cache', $status );
        return $valid;
    }

    // External site: background cron verification (never block page load)
    if ( ! wp_next_scheduled( 'dfem_bg_license_check' ) ) {
        wp_schedule_single_event( time() + 30, 'dfem_bg_license_check' );
    }
    $cached = get_option( 'dfem_license_status_cache', 'invalid' );
    return $cached === 'valid';
}

// Background license check — runs via WP Cron, safe from page-load context
add_action( 'dfem_bg_license_check', 'dfem_bg_verify_license' );
function dfem_bg_verify_license() {
    dfem_verify_license( dfem_get_license_key() );
}

function dfem_verify_license( $key ) {
    if ( empty( $key ) ) {
        set_transient( 'dfem_license_status', 'invalid', WEEK_IN_SECONDS );
        return false;
    }
    $response = wp_remote_post( DFEM_LICENSE_SERVER, [
        'timeout'   => 8,
        'blocking'  => true,
        'sslverify' => true,
        'body'      => [
            'license_key' => sanitize_text_field( $key ),
            'site_url'    => home_url(),
            'plugin_ver'  => DFEM_VERSION,
        ],
    ]);
    if ( is_wp_error( $response ) ) {
        $existing = get_option( 'dfem_license_status_cache', 'invalid' );
        set_transient( 'dfem_license_status', $existing, DAY_IN_SECONDS );
        return $existing === 'valid';
    }
    $body   = json_decode( wp_remote_retrieve_body( $response ), true );
    $valid  = ! empty( $body['valid'] ) && $body['valid'] === true;
    $status = $valid ? 'valid' : 'invalid';
    set_transient( 'dfem_license_status', $status, WEEK_IN_SECONDS );
    update_option( 'dfem_license_status_cache', $status );
    return $valid;
}

/* =========================================================
   ACTIVATION + DEACTIVATION + CRON + TRACKING
   ========================================================= */
register_activation_hook( __FILE__, 'dfem_activate' );
function dfem_activate() {
    global $wpdb;
    $charset = $wpdb->get_charset_collate();
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    dbDelta("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}dfem_subscribers (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        email VARCHAR(200) NOT NULL UNIQUE,
        first_name VARCHAR(100) DEFAULT '',
        last_name VARCHAR(100) DEFAULT '',
        business_name VARCHAR(200) DEFAULT '',
        group_id BIGINT UNSIGNED DEFAULT 0,
        status ENUM('subscribed','unsubscribed') DEFAULT 'subscribed',
        token VARCHAR(64) DEFAULT '',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $charset;");

    dbDelta("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}dfem_groups (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        name VARCHAR(200) NOT NULL UNIQUE,
        description LONGTEXT DEFAULT '',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $charset;");

    dbDelta("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}dfem_campaigns (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        subject VARCHAR(300) NOT NULL,
        body LONGTEXT NOT NULL,
        group_id BIGINT UNSIGNED DEFAULT 0,
        sent_to INT DEFAULT 0,
        opens INT DEFAULT 0,
        clicks INT DEFAULT 0,
        tracking_enabled TINYINT DEFAULT 0,
        status ENUM('draft','scheduled','sent') DEFAULT 'draft',
        scheduled_at DATETIME DEFAULT NULL,
        sent_at DATETIME DEFAULT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $charset;");

    dbDelta("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}dfem_tracking (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        campaign_id BIGINT UNSIGNED NOT NULL,
        subscriber_id BIGINT UNSIGNED DEFAULT 0,
        email VARCHAR(200) DEFAULT '',
        type ENUM('open','click') NOT NULL,
        url VARCHAR(1000) DEFAULT '',
        ip_address VARCHAR(45) DEFAULT '',
        user_agent VARCHAR(500) DEFAULT '',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY campaign_id (campaign_id)
    ) $charset;");

    // Add missing columns on upgrade
    $cols = $wpdb->get_col("SHOW COLUMNS FROM {$wpdb->prefix}dfem_campaigns");
    $add_map = [
        'group_id'         => "ADD COLUMN group_id BIGINT UNSIGNED DEFAULT 0 AFTER body",
        'opens'            => "ADD COLUMN opens INT DEFAULT 0 AFTER sent_to",
        'clicks'           => "ADD COLUMN clicks INT DEFAULT 0 AFTER opens",
        'tracking_enabled' => "ADD COLUMN tracking_enabled TINYINT DEFAULT 0 AFTER clicks",
        'scheduled_at'     => "ADD COLUMN scheduled_at DATETIME DEFAULT NULL AFTER status",
    ];
    foreach ($add_map as $col => $sql) {
        if (!in_array($col, $cols)) {
            $wpdb->query("ALTER TABLE {$wpdb->prefix}dfem_campaigns $sql");
        }
    }

    if (!get_option('dfem_settings')) {
        update_option('dfem_settings', [
            'from_name'     => get_bloginfo('name'),
            'from_email'    => get_bloginfo('admin_email'),
            'footer_text'   => '© ' . date('Y') . ' ' . get_bloginfo('name') . '. All rights reserved.',
            'logo_media_id' => 0,
            'primary_color' => '#0066cc',
            'social_links'  => [],
        ]);
    }

    if (!get_option('dfem_unsub_page_id')) {
        $page_id = wp_insert_post([
            'post_title'   => 'Unsubscribe',
            'post_name'    => 'email-unsubscribe',
            'post_content' => '[dfem_unsubscribe]',
            'post_status'  => 'publish',
            'post_type'    => 'page',
        ]);
        update_option('dfem_unsub_page_id', $page_id);
    }

    if (!wp_next_scheduled('dfem_process_scheduled')) {
        wp_schedule_event(time(), 'dfem_five_min', 'dfem_process_scheduled');
    }
}

register_deactivation_hook(__FILE__, function() { wp_clear_scheduled_hook('dfem_process_scheduled'); });

add_filter('cron_schedules', function($s) {
    $s['dfem_five_min'] = ['interval' => 300, 'display' => 'Every 5 Min (DFEM)'];
    return $s;
});

add_action('dfem_process_scheduled', 'dfem_run_scheduled_campaigns');
function dfem_run_scheduled_campaigns() {
    global $wpdb;
    $due = $wpdb->get_results("SELECT id FROM {$wpdb->prefix}dfem_campaigns WHERE status='scheduled' AND scheduled_at IS NOT NULL AND scheduled_at <= NOW()");
    foreach ($due as $row) { dfem_dispatch_campaign($row->id); }
}

// Shared send helper — used by manual send AND cron
function dfem_dispatch_campaign($campaign_id) {
    global $wpdb, $dfem_sending;
    $camp = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}dfem_campaigns WHERE id=%d", (int)$campaign_id));
    if (!$camp || $camp->status === 'sent') return 0;
    $settings   = get_option('dfem_settings', []);
    $from_name  = $settings['from_name']  ?? get_bloginfo('name');
    $from_email = $settings['from_email'] ?? get_bloginfo('admin_email');
    $where      = "WHERE status='subscribed'" . ($camp->group_id ? $wpdb->prepare(" AND group_id=%d", $camp->group_id) : '');
    $subs       = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}dfem_subscribers $where");
    if (empty($subs)) return 0;
    $attach_key  = 'dfem_attachments_draft_' . $camp->id . '_' . get_current_user_id();
    $files       = array_column(get_transient($attach_key) ?: [], 'path');
    $sent = 0;
    $dfem_sending = true;
    foreach ($subs as $sub) {
        $body = str_replace(['{{first_name}}','{{last_name}}','{{email}}','{{business}}'],
            [esc_html($sub->first_name),esc_html($sub->last_name),esc_html($sub->email),esc_html($sub->business_name)],
            $camp->body);
        if (dfem_is_premium() && $camp->tracking_enabled) {
            $body = dfem_wrap_tracking_links($body, $camp->id, $sub->token);
        }
        $html = dfem_build_email($camp->subject, $body, dfem_unsub_url($sub->email, $sub->token), $camp->id, $sub->token);
        $hdrs = ['Content-Type: text/html; charset=UTF-8', "From: $from_name <$from_email>"];
        if (wp_mail($sub->email, $camp->subject, $html, $hdrs, $files)) $sent++;
    }
    $dfem_sending = false;
    $wpdb->update("{$wpdb->prefix}dfem_campaigns", ['status'=>'sent','sent_to'=>$sent,'sent_at'=>current_time('mysql')], ['id'=>$camp->id], ['%s','%d','%s'], ['%d']);
    delete_transient($attach_key);
    return $sent;
}

// Tracking URL handler (open pixel + click redirect) — front-end only
add_action('init', 'dfem_handle_tracking_request');
function dfem_handle_tracking_request() {
    // Never run during admin or AJAX requests
    if ( is_admin() || wp_doing_ajax() || wp_doing_cron() ) return;
    if ( !isset($_GET['dfem_track']) ) return;
    $type    = sanitize_text_field($_GET['dfem_track']);
    $camp_id = (int)($_GET['c'] ?? 0);
    $token   = sanitize_text_field($_GET['s'] ?? '');
    if (!$camp_id || !$token) return;
    global $wpdb;
    $sub = $wpdb->get_row($wpdb->prepare("SELECT id,email FROM {$wpdb->prefix}dfem_subscribers WHERE token=%s", $token));
    $ip  = sanitize_text_field($_SERVER['REMOTE_ADDR'] ?? '');
    $ua  = sanitize_text_field(substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 500));
    if ($type === 'open') {
        if ($sub) {
            $wpdb->insert("{$wpdb->prefix}dfem_tracking", ['campaign_id'=>$camp_id,'subscriber_id'=>$sub->id,'email'=>$sub->email,'type'=>'open','url'=>'','ip_address'=>$ip,'user_agent'=>$ua],['%d','%d','%s','%s','%s','%s','%s']);
            $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}dfem_campaigns SET opens=opens+1 WHERE id=%d", $camp_id));
        }
        header('Content-Type: image/gif');
        header('Cache-Control: no-store,no-cache,must-revalidate');
        echo base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
        exit;
    } elseif ($type === 'click') {
        $url = esc_url_raw(base64_decode(sanitize_text_field($_GET['u'] ?? '')));
        if ($sub && $url) {
            $wpdb->insert("{$wpdb->prefix}dfem_tracking", ['campaign_id'=>$camp_id,'subscriber_id'=>$sub->id,'email'=>$sub->email,'type'=>'click','url'=>substr($url,0,1000),'ip_address'=>$ip,'user_agent'=>$ua],['%d','%d','%s','%s','%s','%s','%s']);
            $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}dfem_campaigns SET clicks=clicks+1 WHERE id=%d", $camp_id));
        }
        wp_redirect($url ?: home_url()); exit;
    }
}

// CSV Export via admin_post — fires BEFORE any HTML output, safe to send headers
add_action('admin_post_dfem_export_csv', 'dfem_handle_csv_export');
function dfem_handle_csv_export() {
    if ( !current_user_can('manage_options') ) wp_die('Unauthorized');
    if ( !isset($_POST['_wpnonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wpnonce'])), 'dfem_export') ) {
        wp_die('Security check failed. Please go back and try again.');
    }
    if ( !dfem_is_premium() ) {
        wp_redirect(admin_url('admin.php?page=dfem-settings&tab=export&notice=premium'));
        exit;
    }
    global $wpdb;
    $export_gid = (int)($_POST['export_group_id'] ?? 0);
    $where      = $export_gid ? $wpdb->prepare("WHERE s.group_id=%d", $export_gid) : "";
    $subs       = $wpdb->get_results("SELECT s.*, g.name as group_name FROM {$wpdb->prefix}dfem_subscribers s LEFT JOIN {$wpdb->prefix}dfem_groups g ON g.id=s.group_id $where ORDER BY s.created_at DESC");
    $filename   = 'dfem-subscribers-' . date('Y-m-d') . '.csv';
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Pragma: no-cache');
    echo "\xEF\xBB\xBF"; // UTF-8 BOM — makes Excel open correctly
    $out = fopen('php://output', 'w');
    fputcsv($out, ['First Name', 'Last Name', 'Email', 'Business Name', 'Group', 'Status', 'Date Added']);
    foreach ($subs as $s) {
        fputcsv($out, [
            $s->first_name,
            $s->last_name,
            $s->email,
            $s->business_name,
            $s->group_name ?: '',
            $s->status,
            date('d M Y', strtotime($s->created_at)),
        ]);
    }
    fclose($out);
    exit;
}

function dfem_wrap_tracking_links($body, $campaign_id, $sub_token) {
    return preg_replace_callback('/<a(\s[^>]*)?href=["\']([^"\']+)["\']([^>]*)>/i', function($m) use ($campaign_id, $sub_token) {
        $url = $m[2];
        if (strpos($url,'dfem_track')!==false || strpos($url,'email-unsubscribe')!==false) return $m[0];
        $track = add_query_arg(['dfem_track'=>'click','c'=>$campaign_id,'s'=>$sub_token,'u'=>base64_encode($url)], home_url('/'));
        return str_replace($url, esc_url($track), $m[0]);
    }, $body);
}

/* =========================================================
   ENQUEUE MEDIA UPLOADER ON SETTINGS PAGE
   ========================================================= */
add_action('admin_enqueue_scripts', 'dfem_enqueue_scripts');
function dfem_enqueue_scripts( $hook ) {
    if ( strpos($hook, 'dfem') === false ) return;
    wp_enqueue_media();
}

/* =========================================================
   FORCE FROM EMAIL
   ========================================================= */
$dfem_sending = false;

add_filter('wp_mail_from', function($email) {
    global $dfem_sending;
    if ( $dfem_sending ) {
        $settings = get_option('dfem_settings',[]);
        $from = $settings['from_email'] ?? '';
        if ( is_email($from) ) return $from;
    }
    return $email;
});

add_filter('wp_mail_from_name', function($name) {
    global $dfem_sending;
    if ( $dfem_sending ) {
        $settings = get_option('dfem_settings',[]);
        $n = $settings['from_name'] ?? '';
        if ( $n ) return $n;
    }
    return $name;
});

/* =========================================================
   ADMIN MENU
   ========================================================= */
add_action('admin_menu', 'dfem_admin_menu');
function dfem_admin_menu() {
    add_menu_page('DadsFam Email Marketing','Email Marketing','manage_options','dfem-dashboard','dfem_page_dashboard','dashicons-email-alt',26);
    add_submenu_page('dfem-dashboard','Dashboard',    'Dashboard',    'manage_options','dfem-dashboard',    'dfem_page_dashboard');
    add_submenu_page('dfem-dashboard','Subscribers',  'Subscribers',  'manage_options','dfem-subscribers',  'dfem_page_subscribers');
    add_submenu_page('dfem-dashboard','Groups',       'Groups',       'manage_options','dfem-groups',       'dfem_page_groups');
    add_submenu_page('dfem-dashboard','Campaigns',    'Campaigns',    'manage_options','dfem-campaigns',    'dfem_page_campaigns');
    add_submenu_page('dfem-dashboard','New Campaign', 'New Campaign', 'manage_options','dfem-new-campaign', 'dfem_page_new_campaign');
    add_submenu_page('dfem-dashboard','📊 Tracking',  '📊 Tracking',  'manage_options','dfem-tracking',     'dfem_page_tracking');
    add_submenu_page('dfem-dashboard','Settings',     'Settings',     'manage_options','dfem-settings',     'dfem_page_settings');
}

/* =========================================================
   SHARED STYLES
   ========================================================= */
function dfem_styles() { ?>
<style>
:root{--df-blue:#0066cc;--df-dark:#004a99;--df-green:#00A878;--df-red:#e63946;--df-bg:#f0f4f8;--df-white:#fff;--df-border:#dde3ea;--df-text:#1a2332;--df-muted:#6b7a8d;}
.dfem-wrap{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;background:var(--df-bg);min-height:100vh;margin:0 -20px;padding:0;}
.dfem-header{background:linear-gradient(135deg,var(--df-blue) 0%,var(--df-dark) 100%);padding:28px 40px;display:flex;align-items:center;justify-content:space-between;box-shadow:0 4px 15px rgba(0,102,204,.25);}
.dfem-header h1{color:#fff;margin:0;font-size:1.7em;font-weight:700;display:flex;align-items:center;gap:10px;}
.dfem-header h1 span{font-size:.85em;opacity:.8;font-weight:400;}
.dfem-badge{background:rgba(255,255,255,.2);color:#fff;padding:5px 13px;border-radius:20px;font-size:.8em;font-weight:600;}
.dfem-body{padding:28px 40px;}
.dfem-footer{background:var(--df-dark);color:rgba(255,255,255,.65);padding:18px 40px;text-align:center;font-size:.84em;margin-top:40px;}
.dfem-footer a{color:rgba(255,255,255,.85);text-decoration:none;}
.dfem-stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(190px,1fr));gap:18px;margin-bottom:28px;}
.dfem-stat{background:#fff;border-radius:12px;padding:22px;box-shadow:0 2px 10px rgba(0,0,0,.06);border-top:4px solid var(--df-blue);transition:transform .2s;}
.dfem-stat:hover{transform:translateY(-3px);}
.dfem-stat .n{font-size:2.4em;font-weight:700;color:var(--df-blue);line-height:1;}
.dfem-stat .l{color:var(--df-muted);font-size:.88em;margin-top:5px;}
.dfem-card{background:#fff;border-radius:12px;padding:28px;box-shadow:0 2px 10px rgba(0,0,0,.06);margin-bottom:22px;}
.dfem-card h2{color:var(--df-text);margin:0 0 20px;padding-bottom:14px;border-bottom:2px solid var(--df-border);font-size:1.15em;display:flex;align-items:center;gap:8px;}
.dfem-btn{display:inline-flex;align-items:center;gap:5px;padding:9px 18px;border-radius:8px;font-weight:600;font-size:.88em;cursor:pointer;text-decoration:none;border:none;transition:all .2s;line-height:1.4;}
.dfem-btn-primary{background:var(--df-blue);color:#fff!important;}
.dfem-btn-primary:hover{background:var(--df-dark);transform:translateY(-1px);box-shadow:0 4px 10px rgba(0,102,204,.3);}
.dfem-btn-success{background:var(--df-green);color:#fff!important;}
.dfem-btn-success:hover{background:#008a62;transform:translateY(-1px);}
.dfem-btn-danger{background:var(--df-red);color:#fff!important;}
.dfem-btn-danger:hover{background:var(--df-blue)!important;transform:translateY(-1px);}
.dfem-btn-secondary{background:#f0f4f8;color:var(--df-text)!important;border:1px solid var(--df-border);}
.dfem-btn-secondary:hover{background:#e2e8f0;}
.dfem-btn-warning{background:#f59e0b;color:#fff!important;}
.dfem-btn-warning:hover{background:#d97706;}
.dfem-btn-sm{padding:5px 11px;font-size:.8em;}
.dfem-table-wrap{overflow-x:auto;}
.dfem-table{width:100%;border-collapse:collapse;font-size:.9em;}
.dfem-table thead tr{background:linear-gradient(135deg,var(--df-blue),var(--df-dark));}
.dfem-table thead th{color:#fff;padding:13px 15px;text-align:left;font-weight:600;}
.dfem-table tbody tr{border-bottom:1px solid var(--df-border);transition:background .12s;}
.dfem-table tbody tr:hover{background:#f8faff;}
.dfem-table tbody td{padding:13px 15px;color:var(--df-text);vertical-align:middle;}
.dfem-badge-green{background:#d4f7ed;color:#005c34;padding:3px 10px;border-radius:20px;font-size:.78em;font-weight:700;}
.dfem-badge-red{background:#fde8ea;color:#9b1c28;padding:3px 10px;border-radius:20px;font-size:.78em;font-weight:700;}
.dfem-badge-blue{background:#dceeff;color:#003d7a;padding:3px 10px;border-radius:20px;font-size:.78em;font-weight:700;}
.dfem-badge-gray{background:#f0f0f0;color:#555;padding:3px 10px;border-radius:20px;font-size:.78em;font-weight:700;}
.dfem-badge-purple{background:#f0e6ff;color:#5b21b6;padding:3px 10px;border-radius:20px;font-size:.78em;font-weight:700;}
.dfem-form-row{margin-bottom:18px;}
.dfem-form-row label{display:block;font-weight:600;margin-bottom:5px;color:var(--df-text);font-size:.9em;}
.dfem-form-row input[type=text],.dfem-form-row input[type=email],.dfem-form-row input[type=url],.dfem-form-row input[type=color],.dfem-form-row textarea,.dfem-form-row select{width:100%;padding:9px 13px;border:2px solid var(--df-border);border-radius:8px;font-size:.93em;transition:border-color .2s;box-sizing:border-box;background:#fff;font-family:inherit;}
.dfem-form-row input:focus,.dfem-form-row textarea:focus,.dfem-form-row select:focus{outline:none;border-color:var(--df-blue);box-shadow:0 0 0 3px rgba(0,102,204,.1);}
.dfem-form-row input[type=color]{padding:3px;height:40px;}
.dfem-grid2{display:grid;grid-template-columns:1fr 1fr;gap:18px;}
.dfem-alert{padding:13px 17px;border-radius:8px;margin-bottom:18px;font-weight:600;}
.dfem-alert-success{background:#d4f7ed;color:#005c34;border-left:4px solid var(--df-green);}
.dfem-alert-error{background:#fde8ea;color:#7d1120;border-left:4px solid var(--df-red);}
.dfem-alert-info{background:#dceeff;color:#003d7a;border-left:4px solid var(--df-blue);}
.dfem-preview-wrap{border:2px solid var(--df-border);border-radius:12px;overflow:hidden;}
.social-row{display:flex;gap:10px;align-items:center;padding:10px;background:#f8faff;border-radius:8px;margin-bottom:8px;border:1px solid var(--df-border);}
.social-row input{flex:1;margin:0!important;}
.attachment-item{display:flex;align-items:center;gap:10px;padding:10px 14px;background:#f8faff;border:1px solid var(--df-border);border-radius:8px;margin-bottom:8px;}
.attachment-item span{flex:1;font-size:.9em;}
.dfem-logo-wrap{display:flex;align-items:center;gap:16px;flex-wrap:wrap;}
.dfem-logo-preview{width:140px;height:70px;object-fit:contain;border:2px solid var(--df-border);border-radius:8px;background:#f8faff;display:block;}
.dfem-logo-placeholder{width:140px;height:70px;border:2px dashed var(--df-border);border-radius:8px;background:#f8faff;display:flex;align-items:center;justify-content:center;color:var(--df-muted);font-size:.8em;text-align:center;}
/* Settings tabs */
.dfem-tabs{display:flex;gap:0;margin-bottom:0;border-bottom:2px solid var(--df-border);}
.dfem-tab{padding:11px 24px;cursor:pointer;font-weight:600;font-size:.92em;border-radius:10px 10px 0 0;border:2px solid transparent;border-bottom:none;color:var(--df-muted);background:transparent;text-decoration:none;display:inline-block;transition:all .18s;margin-bottom:-2px;}
.dfem-tab:hover{background:#f0f4f8;color:var(--df-text);}
.dfem-tab.active{background:#fff;border-color:var(--df-border);border-bottom-color:#fff;color:var(--df-blue);font-weight:700;}
.dfem-tab-panel{display:none;}
.dfem-tab-panel.active{display:block;}
/* Changelog */
.dfem-cl-entry{border-left:4px solid var(--df-blue);padding:16px 20px;background:#f8faff;border-radius:0 10px 10px 0;margin-bottom:18px;}
.dfem-cl-entry.major{border-color:#7b2cbf;background:#faf6ff;}
.dfem-cl-entry.new{border-color:var(--df-green);background:#f4fdf9;}
.dfem-cl-entry.fix{border-color:#f59e0b;background:#fffbf0;}
.dfem-cl-entry.initial{border-color:var(--df-muted);background:#f8faff;}
.dfem-cl-version{display:flex;align-items:center;gap:12px;margin-bottom:10px;}
.dfem-cl-version strong{font-size:1.05em;color:var(--df-text);}
.dfem-cl-version .date{color:var(--df-muted);font-size:.84em;}
.dfem-cl-changes{margin:0;padding-left:20px;line-height:2;color:var(--df-text);font-size:.92em;}
/* Pagination */
.dfem-badge-scheduled{background:#fef3c7;color:#92400e;padding:3px 10px;border-radius:20px;font-size:.78em;font-weight:700;}
.dfem-badge-gold{background:linear-gradient(135deg,#fef3c7,#fde68a);color:#92400e;padding:3px 10px;border-radius:20px;font-size:.78em;font-weight:700;border:1px solid #f59e0b;}
.dfem-progress{background:#e8edf2;border-radius:20px;height:8px;overflow:hidden;margin-top:6px;}
.dfem-progress-bar{background:linear-gradient(90deg,var(--df-blue),var(--df-green));height:100%;border-radius:20px;transition:width .4s;}
.dfem-stat-mini{display:inline-flex;align-items:center;gap:6px;padding:6px 12px;background:#f8faff;border-radius:8px;font-size:.82em;font-weight:600;color:var(--df-muted);}
.dfem-schedule-box{background:#fffbeb;border:2px dashed #f59e0b;border-radius:10px;padding:18px;margin-top:16px;}
.dfem-tracking-row{display:grid;grid-template-columns:1fr 80px 80px 100px;gap:10px;align-items:center;padding:12px 0;border-bottom:1px solid var(--df-border);font-size:.9em;}
.dfem-tracking-row:last-child{border-bottom:none;}
input[type=datetime-local]{font-family:inherit;}

.dfem-page-current{padding:5px 12px;background:var(--df-blue);color:#fff;border-radius:8px;font-weight:700;font-size:.85em;}
.dfem-page-info{text-align:center;color:var(--df-muted);font-size:.85em;margin-top:8px;}
@media(max-width:768px){.dfem-grid2{grid-template-columns:1fr;}.dfem-body,.dfem-header{padding:18px;}.dfem-tabs{flex-wrap:wrap;}}
</style>
<?php }

/* =========================================================
   HEADER / FOOTER
   ========================================================= */
function dfem_header( $sub = '' ) {
    dfem_styles(); ?>
    <div class="dfem-wrap">
    <div class="dfem-header">
        <h1>📧 DadsFam Email Marketing<?php if($sub) echo " <span>/ $sub</span>"; ?></h1>
        <div style="display:flex;align-items:center;gap:10px;">
            <?php if ( dfem_is_premium() ): ?>
            <span class="dfem-badge" style="background:linear-gradient(135deg,#f59e0b,#d97706);">⭐ Premium</span>
            <?php endif; ?>
            <span class="dfem-badge">v<?php echo DFEM_VERSION; ?></span>
        </div>
    </div>
    <div class="dfem-body">
<?php }

function dfem_footer() { ?>
    </div>
    <div class="dfem-footer">
        <strong>DadsFam Email Marketing</strong> v<?php echo DFEM_VERSION; ?> &nbsp;·&nbsp;
        <a href="https://www.dadsfam.co.za" target="_blank">dadsfam.co.za</a>
    </div>
    </div>
<?php }

/* =========================================================
   PAGINATION HELPER
   ========================================================= */
function dfem_pagination( $paged, $total_pages, $base_url ) {
    if ( $total_pages <= 1 ) return;
    echo '<div class="dfem-pagination">';
    if ($paged > 1) echo '<a href="'.esc_url(add_query_arg('paged',$paged-1,$base_url)).'" class="dfem-btn dfem-btn-secondary dfem-btn-sm">← Prev</a>';
    // Show up to 7 page links with ellipsis
    $start = max(1, $paged - 3);
    $end   = min($total_pages, $paged + 3);
    if ($start > 1) { echo '<a href="'.esc_url(add_query_arg('paged',1,$base_url)).'" class="dfem-btn dfem-btn-secondary dfem-btn-sm">1</a>'; if ($start > 2) echo '<span style="color:var(--df-muted);padding:0 4px">…</span>'; }
    for ($i = $start; $i <= $end; $i++) {
        if ($i === $paged) echo '<span class="dfem-page-current">'.$i.'</span>';
        else echo '<a href="'.esc_url(add_query_arg('paged',$i,$base_url)).'" class="dfem-btn dfem-btn-secondary dfem-btn-sm">'.$i.'</a>';
    }
    if ($end < $total_pages) { if ($end < $total_pages - 1) echo '<span style="color:var(--df-muted);padding:0 4px">…</span>'; echo '<a href="'.esc_url(add_query_arg('paged',$total_pages,$base_url)).'" class="dfem-btn dfem-btn-secondary dfem-btn-sm">'.$total_pages.'</a>'; }
    if ($paged < $total_pages) echo '<a href="'.esc_url(add_query_arg('paged',$paged+1,$base_url)).'" class="dfem-btn dfem-btn-secondary dfem-btn-sm">Next →</a>';
    echo '</div>';
}

/* =========================================================
   DASHBOARD
   ========================================================= */
function dfem_page_dashboard() {
    global $wpdb;
    $active = (int)$wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}dfem_subscribers WHERE status='subscribed'");
    $unsub  = (int)$wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}dfem_subscribers WHERE status='unsubscribed'");
    $sent   = (int)$wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}dfem_campaigns WHERE status='sent'");
    $drafts = (int)$wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}dfem_campaigns WHERE status='draft'");
    $recent = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}dfem_campaigns ORDER BY created_at DESC LIMIT 5");
    dfem_header('Dashboard'); ?>
    <div class="dfem-stats">
        <div class="dfem-stat"><div class="n"><?php echo $active; ?></div><div class="l">✅ Active Subscribers</div></div>
        <div class="dfem-stat" style="border-top-color:#e63946"><div class="n" style="color:#e63946"><?php echo $unsub; ?></div><div class="l">🚫 Unsubscribed</div></div>
        <div class="dfem-stat" style="border-top-color:#00A878"><div class="n" style="color:#00A878"><?php echo $sent; ?></div><div class="l">📤 Campaigns Sent</div></div>
        <div class="dfem-stat" style="border-top-color:#7b2cbf"><div class="n" style="color:#7b2cbf"><?php echo $drafts; ?></div><div class="l">📝 Drafts</div></div>
    </div>
    <div class="dfem-card">
        <h2>⚡ Quick Actions</h2>
        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <a href="?page=dfem-new-campaign" class="dfem-btn dfem-btn-primary">✏️ New Campaign</a>
            <a href="?page=dfem-subscribers" class="dfem-btn dfem-btn-success">👥 Manage Subscribers</a>
            <a href="?page=dfem-groups" class="dfem-btn dfem-btn-secondary">📋 Groups</a>
            <a href="?page=dfem-campaigns" class="dfem-btn dfem-btn-secondary">📤 Campaigns</a>
            <a href="?page=dfem-settings" class="dfem-btn dfem-btn-secondary">⚙️ Settings</a>
        </div>
    </div>

    <div class="dfem-card">
        <h2>🛒 WooCommerce Integration</h2>
        <?php if(function_exists('wc_get_orders')): ?>
        <div class="dfem-alert dfem-alert-success">✅ <strong>WooCommerce is Active</strong> — You can import customers directly from WooCommerce on the <a href="?page=dfem-subscribers">Subscribers page</a>.</div>
        <?php else: ?>
        <div class="dfem-alert dfem-alert-info" style="background:#f0f4f8;color:var(--df-muted);border-left-color:var(--df-border);">⚪ <strong>WooCommerce Not Installed</strong> — The WooCommerce import feature is optional and only appears when WooCommerce is active. All other features work normally.</div>
        <?php endif; ?>
    </div>

    <div class="dfem-grid2">
    <div class="dfem-card">
        <h2>📖 How to Use</h2>
        <ol style="margin:0;padding-left:20px;line-height:2.2;color:var(--df-text);font-size:.92em;">
            <li><strong>Settings → General</strong> — Set your sender name and from email address.</li>
            <li><strong>Settings → Branding</strong> — Upload your logo, pick your brand colour, add social links.</li>
            <li><strong>Settings → ⭐ License</strong> — Enter a Premium Key to unlock scheduling, tracking &amp; export.</li>
            <li><strong>Groups</strong> — Create groups to segment your audience (e.g. VIP, Newsletter, Leads).</li>
            <li><strong>Subscribers</strong> — Add manually, import from WooCommerce, or upload a CSV file.</li>
            <li><strong>New Campaign</strong> — Write your email. Use <code>{{first_name}}</code> <code>{{last_name}}</code> <code>{{email}}</code> <code>{{business}}</code> to personalise every email automatically.</li>
            <li><strong>Always Send a Test first</strong> — Use the "Send Test" button before sending to your full list.</li>
            <li><strong>Send Now or Schedule (Premium)</strong> — Send immediately, or schedule to auto-send at a future date and time.</li>
            <li><strong>📊 Tracking (Premium)</strong> — Tick "Enable Tracking" when composing to track email opens and link clicks per campaign.</li>
            <li><strong>Campaigns</strong> — View sent history with open/click stats, edit drafts, or delete records.</li>
            <li><strong>Settings → Export (Premium)</strong> — Download your subscriber list as an Excel-compatible CSV file.</li>
        </ol>
        <div style="margin-top:18px;padding:14px 18px;background:#f8faff;border-radius:10px;border-left:4px solid var(--df-blue);">
            <strong>💡 Pro Tips:</strong>
            <ul style="margin:8px 0 0;padding-left:18px;line-height:2;color:var(--df-muted);font-size:.9em;">
                <li>Use <strong>Groups</strong> for targeted sends — targeted emails get far higher open rates than mass blasts.</li>
                <li>Use <code>{{first_name}}</code> in your <strong>subject line</strong> too — it dramatically improves open rates.</li>
                <li>Always send a <strong>test email</strong> first — check it on mobile, check all links, check personalisation tags.</li>
                <li>The <strong>unsubscribe link</strong> is added automatically to every email footer — never add it manually.</li>
                <li>If emails land in spam, make sure the <strong>From Email</strong> in Settings matches your actual domain.</li>
            </ul>
        </div>
    </div>
    <div class="dfem-card">
        <h2>💬 Support & Contact</h2>
        <p style="color:var(--df-muted);font-size:.92em;margin:0 0 14px">Need help with the plugin or have a question? Reach out to the DadsFam team:</p>
        <p style="margin:0 0 10px"><strong>📧 Email Support:</strong><br>
            <a href="mailto:socials@dadsfam.co.za" style="color:var(--df-blue);font-weight:600;font-size:1em;">socials@dadsfam.co.za</a>
        </p>
        <p style="margin:0 0 10px"><strong>🌐 Website:</strong><br>
            <a href="https://www.dadsfam.co.za" target="_blank" style="color:var(--df-blue);font-weight:600;">www.dadsfam.co.za</a>
        </p>
        <p style="margin:14px 0 0;padding:12px;background:#f8faff;border-radius:8px;border-left:3px solid var(--df-blue);font-size:.88em;color:var(--df-muted)">
            💡 <strong>Tip:</strong> Always send a test email before sending your campaign to all subscribers!
        </p>
    </div>
    </div>
    <div class="dfem-card">
        <h2>📋 Recent Campaigns</h2>
        <?php if($recent): ?>
        <div class="dfem-table-wrap"><table class="dfem-table">
        <thead><tr><th>Subject</th><th>Status</th><th>Sent To</th><th>Date</th></tr></thead>
        <tbody><?php foreach($recent as $c): ?>
        <tr>
            <td><strong><?php echo esc_html($c->subject); ?></strong></td>
            <td><?php echo $c->status==='sent'?'<span class="dfem-badge-green">📤 Sent</span>':'<span class="dfem-badge-gray">📝 Draft</span>'; ?></td>
            <td><?php echo $c->sent_to?$c->sent_to.' recipients':'—'; ?></td>
            <td><?php echo date('d M Y',strtotime($c->created_at)); ?></td>
        </tr>
        <?php endforeach; ?></tbody>
        </table></div>
        <?php else: ?><p style="color:var(--df-muted)">No campaigns yet. <a href="?page=dfem-new-campaign">Create your first →</a></p><?php endif; ?>
    </div>
    <?php dfem_footer();
}

/* =========================================================
   SUBSCRIBERS
   ========================================================= */
function dfem_page_subscribers() {
    global $wpdb;
    $table = $wpdb->prefix.'dfem_subscribers';
    $msg   = '';

    if ( isset($_POST['dfem_action']) && check_admin_referer('dfem_sub_action') ) {
        $act = sanitize_text_field($_POST['dfem_action']);

        if ( in_array($act,['add','edit']) ) {
            $email = sanitize_email($_POST['email']);
            $fname = sanitize_text_field($_POST['first_name']);
            $lname = sanitize_text_field($_POST['last_name']);
            $biz   = sanitize_text_field($_POST['business_name']);
            $stat  = sanitize_text_field($_POST['status']);
            $gid   = (int)($_POST['group_id']??0);
            if ( ! is_email($email) ) {
                $msg = '<div class="dfem-alert dfem-alert-error">❌ Invalid email.</div>';
            } elseif ( $act === 'add' ) {
                if ( $wpdb->get_var($wpdb->prepare("SELECT id FROM $table WHERE email=%s",$email)) ) {
                    $msg = '<div class="dfem-alert dfem-alert-error">❌ Email already exists.</div>';
                } else {
                    $token = wp_generate_password(32,false);
                    $wpdb->insert($table,['email'=>$email,'first_name'=>$fname,'last_name'=>$lname,'business_name'=>$biz,'group_id'=>$gid,'status'=>$stat,'token'=>$token],['%s','%s','%s','%s','%d','%s','%s']);
                    $msg = '<div class="dfem-alert dfem-alert-success">✅ Subscriber added!</div>';
                }
            } else {
                $id = (int)$_POST['sub_id'];
                $wpdb->update($table,['email'=>$email,'first_name'=>$fname,'last_name'=>$lname,'business_name'=>$biz,'group_id'=>$gid,'status'=>$stat],['id'=>$id],['%s','%s','%s','%s','%d','%s'],['%d']);
                $msg = '<div class="dfem-alert dfem-alert-success">✅ Updated!</div>';
            }
        }
        if ( $act === 'delete' ) { $wpdb->delete($table,['id'=>(int)$_POST['sub_id']],['%d']); $msg='<div class="dfem-alert dfem-alert-success">✅ Deleted.</div>'; }
        if ( $act === 'bulk_delete' && !empty($_POST['bulk_ids']) ) {
            $ids=array_map('intval',(array)$_POST['bulk_ids']);
            $ph=implode(',',array_fill(0,count($ids),'%d'));
            $wpdb->query(vsprintf("DELETE FROM $table WHERE id IN ($ph)", $ids));
            $msg='<div class="dfem-alert dfem-alert-success">✅ Deleted '.count($ids).' subscriber(s).</div>';
        }
        if ( $act === 'bulk_assign_group' && !empty($_POST['bulk_ids']) ) {
            $ids   = array_map('intval',(array)$_POST['bulk_ids']);
            $gid   = (int)($_POST['bulk_group_id']??0);
            foreach($ids as $sid){
                $wpdb->update($table,['group_id'=>$gid],['id'=>$sid],['%d'],['%d']);
            }
            $gname = $gid ? $wpdb->get_var($wpdb->prepare("SELECT name FROM {$wpdb->prefix}dfem_groups WHERE id=%d",$gid)) : 'None';
            $msg='<div class="dfem-alert dfem-alert-success">✅ Assigned '.count($ids).' subscriber(s) to group <strong>'.esc_html($gname).'</strong>.</div>';
        }
        if ( $act === 'import_woo' ) {
            if ( ! function_exists('wc_get_orders') ) {
                $msg = '<div class="dfem-alert dfem-alert-error">❌ WooCommerce not active.</div>';
            } else {
                $import_gid = (int)($_POST['import_group_id']??0);
                $added=0;$skipped=0;
                $users = get_users(['role__in'=>['customer','subscriber'],'number'=>-1]);
                foreach($users as $u){
                    $email=sanitize_email($u->user_email);
                    if(!is_email($email)){$skipped++;continue;}
                    if($wpdb->get_var($wpdb->prepare("SELECT id FROM $table WHERE email=%s",$email))){$skipped++;continue;}
                    $wpdb->insert($table,[
                        'email'=>$email,'first_name'=>get_user_meta($u->ID,'billing_first_name',true)?:$u->first_name,
                        'last_name'=>get_user_meta($u->ID,'billing_last_name',true)?:$u->last_name,
                        'business_name'=>get_user_meta($u->ID,'billing_company',true),
                        'group_id'=>$import_gid,'status'=>'subscribed','token'=>wp_generate_password(32,false)
                    ],['%s','%s','%s','%s','%d','%s','%s']);
                    $added++;
                }
                $orders=wc_get_orders(['limit'=>-1,'status'=>['completed','processing']]);
                foreach($orders as $order){
                    $email=sanitize_email($order->get_billing_email());
                    if(!is_email($email)){$skipped++;continue;}
                    if($wpdb->get_var($wpdb->prepare("SELECT id FROM $table WHERE email=%s",$email))){$skipped++;continue;}
                    $wpdb->insert($table,[
                        'email'=>$email,'first_name'=>$order->get_billing_first_name(),
                        'last_name'=>$order->get_billing_last_name(),'business_name'=>$order->get_billing_company(),
                        'group_id'=>$import_gid,'status'=>'subscribed','token'=>wp_generate_password(32,false)
                    ],['%s','%s','%s','%s','%d','%s','%s']);
                    $added++;
                }
                $gname = $import_gid ? $wpdb->get_var($wpdb->prepare("SELECT name FROM {$wpdb->prefix}dfem_groups WHERE id=%d",$import_gid)) : null;
                $gtext = $gname ? " into group <strong>".esc_html($gname)."</strong>" : "";
                $msg="<div class='dfem-alert dfem-alert-success'>✅ Imported <strong>$added</strong> customers{$gtext}. Skipped <strong>$skipped</strong> (duplicates/invalid).</div>";
            }
        }
        if ( $act === 'import_csv' ) {
            if ( empty($_FILES['csv_file']['tmp_name']) ) {
                $msg = '<div class="dfem-alert dfem-alert-error">❌ No file uploaded. Please choose a CSV file.</div>';
            } else {
                $csv_gid = (int)($_POST['csv_group_id']??0);
                $handle  = fopen($_FILES['csv_file']['tmp_name'], 'r');
                $headers = array_map('strtolower', array_map('trim', fgetcsv($handle)));
                // Map column indexes
                $col_map = [];
                $field_names = ['email','first_name','firstname','first name','last_name','lastname','last name','business_name','business','company','name'];
                foreach ($headers as $i => $h) {
                    $h = str_replace([' ','_'], '', strtolower($h));
                    if (in_array($h, ['email','emailaddress'])) $col_map['email'] = $i;
                    elseif (in_array($h, ['firstname','first_name','first'])) $col_map['first_name'] = $i;
                    elseif (in_array($h, ['lastname','last_name','last','surname'])) $col_map['last_name'] = $i;
                    elseif (in_array($h, ['businessname','business_name','business','company','companyname'])) $col_map['business_name'] = $i;
                }
                if (!isset($col_map['email'])) {
                    $msg = '<div class="dfem-alert dfem-alert-error">❌ Could not find an "Email" column in your CSV. Please ensure the first row has column headers including "Email".</div>';
                } else {
                    $added=0; $skipped=0; $invalid=0;
                    while (($row = fgetcsv($handle)) !== false) {
                        $email = isset($row[$col_map['email']]) ? sanitize_email(trim($row[$col_map['email']])) : '';
                        if (!is_email($email)) { $invalid++; continue; }
                        if ($wpdb->get_var($wpdb->prepare("SELECT id FROM $table WHERE email=%s", $email))) { $skipped++; continue; }
                        $wpdb->insert($table, [
                            'email'         => $email,
                            'first_name'    => sanitize_text_field($row[$col_map['first_name'] ?? -1] ?? ''),
                            'last_name'     => sanitize_text_field($row[$col_map['last_name']  ?? -1] ?? ''),
                            'business_name' => sanitize_text_field($row[$col_map['business_name'] ?? -1] ?? ''),
                            'group_id'      => $csv_gid,
                            'status'        => 'subscribed',
                            'token'         => wp_generate_password(32, false),
                        ], ['%s','%s','%s','%s','%d','%s','%s']);
                        $added++;
                    }
                    fclose($handle);
                    $msg = "<div class='dfem-alert dfem-alert-success'>✅ CSV import complete: <strong>$added</strong> imported, <strong>$skipped</strong> duplicates skipped, <strong>$invalid</strong> invalid emails ignored.</div>";
                }
            }
        }
    } // end if dfem_action POST

    $editing = isset($_GET['edit'])?$wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}dfem_subscribers WHERE id=%d",(int)$_GET['edit'])):null;
    $search  = isset($_GET['s'])?sanitize_text_field($_GET['s']):'';
    $filter  = isset($_GET['filter'])?sanitize_text_field($_GET['filter']):'all';
    $group_filter = isset($_GET['group_filter'])?(int)$_GET['group_filter']:0;
    $paged   = max(1,(int)($_GET['paged']??1));
    $per_page = 20;
    $where   = "WHERE 1=1";
    if($search) $where.=$wpdb->prepare(" AND (s.email LIKE %s OR s.first_name LIKE %s OR s.last_name LIKE %s OR s.business_name LIKE %s)","%$search%","%$search%","%$search%","%$search%");
    if($filter==='subscribed')   $where.=" AND s.status='subscribed'";
    if($filter==='unsubscribed') $where.=" AND s.status='unsubscribed'";
    if($group_filter > 0) $where .= $wpdb->prepare(" AND s.group_id=%d", $group_filter);
    if($group_filter === -1) $where .= " AND (s.group_id=0 OR s.group_id IS NULL)";
    $total_subs  = (int)$wpdb->get_var("SELECT COUNT(*) FROM $table s LEFT JOIN {$wpdb->prefix}dfem_groups g ON g.id=s.group_id $where");
    $total_pages = max(1,ceil($total_subs/$per_page));
    $offset = ($paged-1)*$per_page;
    $subs=$wpdb->get_results("SELECT s.*, g.name as group_name FROM $table s LEFT JOIN {$wpdb->prefix}dfem_groups g ON g.id=s.group_id $where ORDER BY s.created_at DESC LIMIT $per_page OFFSET $offset");

    dfem_header('Subscribers'); echo $msg; ?>
    <div class="dfem-card">
        <h2><?php echo $editing?'✏️ Edit Subscriber':'➕ Add Subscriber'; ?></h2>
        <form method="post">
            <?php wp_nonce_field('dfem_sub_action'); ?>
            <input type="hidden" name="dfem_action" value="<?php echo $editing?'edit':'add'; ?>">
            <?php if($editing): ?><input type="hidden" name="sub_id" value="<?php echo $editing->id; ?>"><?php endif; ?>
            <div class="dfem-grid2">
                <div class="dfem-form-row"><label>Email <span style="color:red">*</span></label><input type="email" name="email" required value="<?php echo $editing?esc_attr($editing->email):''; ?>" placeholder="email@example.com"></div>
                <div class="dfem-form-row"><label>Status</label><select name="status"><option value="subscribed" <?php selected($editing?$editing->status:'subscribed','subscribed'); ?>>✅ Subscribed</option><option value="unsubscribed" <?php selected($editing?$editing->status:'','unsubscribed'); ?>>🚫 Unsubscribed</option></select></div>
                <div class="dfem-form-row"><label>First Name</label><input type="text" name="first_name" value="<?php echo $editing?esc_attr($editing->first_name):''; ?>" placeholder="John"></div>
                <div class="dfem-form-row"><label>Last Name</label><input type="text" name="last_name" value="<?php echo $editing?esc_attr($editing->last_name):''; ?>" placeholder="Smith"></div>
                <div class="dfem-form-row"><label>Business Name</label><input type="text" name="business_name" value="<?php echo $editing?esc_attr($editing->business_name):''; ?>" placeholder="Acme Ltd"></div>
                <div class="dfem-form-row"><label>Group</label><select name="group_id"><?php $groups=$wpdb->get_results("SELECT * FROM {$wpdb->prefix}dfem_groups ORDER BY name"); ?><option value="0">— None —</option><?php foreach($groups as $g): ?><option value="<?php echo $g->id; ?>" <?php selected($editing?$editing->group_id:0,$g->id); ?>><?php echo esc_html($g->name); ?></option><?php endforeach; ?></select></div>
            </div>
            <div style="display:flex;gap:10px;">
                <button type="submit" class="dfem-btn dfem-btn-primary"><?php echo $editing?'💾 Save':'➕ Add Subscriber'; ?></button>
                <?php if($editing): ?><a href="?page=dfem-subscribers" class="dfem-btn dfem-btn-secondary">✕ Cancel</a><?php endif; ?>
            </div>
        </form>
    </div>

    <?php if(function_exists('wc_get_orders')): ?>
    <div class="dfem-card">
        <h2>🛒 Import from WooCommerce</h2>
        <p style="color:var(--df-muted);margin:0 0 14px">Imports all WooCommerce customer accounts and guest order emails — name, surname, and business included. Duplicates skipped automatically.</p>
        <form method="post" onsubmit="return confirm('Import all WooCommerce customers? Duplicates will be skipped.');">
            <?php wp_nonce_field('dfem_sub_action'); ?>
            <input type="hidden" name="dfem_action" value="import_woo">
            <div class="dfem-form-row" style="max-width:360px">
                <label>Assign to Group <em style="font-weight:400;color:var(--df-muted)">(optional)</em></label>
                <?php $all_groups=$wpdb->get_results("SELECT * FROM {$wpdb->prefix}dfem_groups ORDER BY name"); ?>
                <select name="import_group_id" style="width:100%;padding:9px 13px;border:2px solid var(--df-border);border-radius:8px;font-size:.93em;">
                    <option value="0">— No Group —</option>
                    <?php foreach($all_groups as $g): ?>
                    <option value="<?php echo $g->id; ?>"><?php echo esc_html($g->name); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="dfem-btn dfem-btn-success">🛒 Import WooCommerce Customers</button>
        </form>
    </div>
    <?php endif; ?>

    <div class="dfem-card">
        <h2>📄 Import from CSV</h2>
        <p style="color:var(--df-muted);margin:0 0 14px;font-size:.92em">Upload a CSV file to bulk import subscribers. First row must be column headers. Required: <strong>Email</strong>. Optional: <strong>First Name</strong>, <strong>Last Name</strong>, <strong>Business Name</strong>. Column order doesn't matter — auto-detected by header name.</p>
        <form method="post" enctype="multipart/form-data">
            <?php wp_nonce_field('dfem_sub_action'); ?>
            <input type="hidden" name="dfem_action" value="import_csv">
            <div class="dfem-grid2">
                <div class="dfem-form-row">
                    <label>CSV File <span style="color:red">*</span></label>
                    <input type="file" name="csv_file" accept=".csv,text/csv" required style="padding:8px;border:2px dashed var(--df-border);border-radius:8px;background:#f8faff;width:100%;box-sizing:border-box;">
                    <small style="color:var(--df-muted)">Max: <?php echo ini_get('upload_max_filesize'); ?>. UTF-8 encoding recommended.</small>
                </div>
                <div class="dfem-form-row">
                    <label>Assign to Group <em style="font-weight:400;color:var(--df-muted)">(optional)</em></label>
                    <?php $csv_groups=$wpdb->get_results("SELECT * FROM {$wpdb->prefix}dfem_groups ORDER BY name"); ?>
                    <select name="csv_group_id" style="width:100%;padding:9px 13px;border:2px solid var(--df-border);border-radius:8px;font-size:.93em;">
                        <option value="0">— No Group —</option>
                        <?php foreach($csv_groups as $g): ?><option value="<?php echo $g->id; ?>"><?php echo esc_html($g->name); ?></option><?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div style="background:#f8faff;padding:12px 16px;border-radius:8px;border:1px solid var(--df-border);margin-bottom:14px;font-size:.86em;color:var(--df-muted)">
                📋 <strong style="color:var(--df-text)">Example CSV format (headers in row 1):</strong><br>
                <code style="font-size:.9em">Email, First Name, Last Name, Business Name</code><br>
                <code style="font-size:.9em">jane@example.com, Jane, Smith, Acme Ltd</code>
            </div>
            <button type="submit" class="dfem-btn dfem-btn-primary">📄 Import CSV</button>
        </form>
    </div>

    <div class="dfem-card">
        <h2>👥 Subscriber List <span style="font-size:.72em;font-weight:400;color:var(--df-muted)">(<?php echo $total_subs; ?> total · page <?php echo $paged; ?> of <?php echo $total_pages; ?>)</span></h2>
        <form method="get" style="display:flex;gap:10px;margin-bottom:18px;flex-wrap:wrap;align-items:center;">
            <input type="hidden" name="page" value="dfem-subscribers">
            <input type="text" name="s" value="<?php echo esc_attr($search); ?>" placeholder="🔍 Search..." style="padding:9px 13px;border:2px solid var(--df-border);border-radius:8px;flex:1;min-width:180px;">
            <select name="filter" style="padding:9px 13px;border:2px solid var(--df-border);border-radius:8px;font-weight:600;">
                <option value="all" <?php selected($filter,'all'); ?>>All</option>
                <option value="subscribed" <?php selected($filter,'subscribed'); ?>>✅ Subscribed</option>
                <option value="unsubscribed" <?php selected($filter,'unsubscribed'); ?>>🚫 Unsubscribed</option>
            </select>
            <select name="group_filter" style="padding:9px 13px;border:2px solid var(--df-border);border-radius:8px;font-weight:600;">
                <option value="0" <?php selected($group_filter,0); ?>>All Groups</option>
                <option value="-1" <?php selected($group_filter,-1); ?>>— Unassigned</option>
                <?php $all_groups=$wpdb->get_results("SELECT * FROM {$wpdb->prefix}dfem_groups ORDER BY name");
                foreach($all_groups as $g): ?>
                <option value="<?php echo $g->id; ?>" <?php selected($group_filter,(int)$g->id); ?>>📍 <?php echo esc_html($g->name); ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="dfem-btn dfem-btn-primary">Filter</button>
            <?php if($search||$filter!=='all'||$group_filter!=0): ?><a href="?page=dfem-subscribers" class="dfem-btn dfem-btn-secondary">Clear</a><?php endif; ?>
        </form>

        <?php
        $bulk_groups = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}dfem_groups ORDER BY name");
        $sub_nonce = wp_create_nonce('dfem_sub_action');
        ?>
        <form method="post" id="dfem-sub-bulk-form" action="">
            <?php wp_nonce_field('dfem_sub_action'); ?>
            <input type="hidden" name="dfem_action" id="dfem_bulk_action_field" value="bulk_delete">
            <input type="hidden" name="bulk_group_id" id="dfem_bulk_group_id_input" value="0">
            <div class="dfem-table-wrap"><table class="dfem-table">
                <thead><tr>
                    <th><input type="checkbox" id="dfem_select_all_subs" style="cursor:pointer;"></th>
                    <th>Email</th><th>Name</th><th>Business</th><th>Group</th><th>Status</th><th>Added</th><th>Actions</th>
                </tr></thead>
                <tbody><?php if($subs): foreach($subs as $s): ?>
                <tr>
                    <td><input type="checkbox" class="sc" name="bulk_ids[]" value="<?php echo $s->id; ?>"></td>
                    <td><strong><?php echo esc_html($s->email); ?></strong></td>
                    <td><?php echo trim($s->first_name.' '.$s->last_name)?:('<span style="color:var(--df-muted)">—</span>'); ?></td>
                    <td><?php echo $s->business_name?esc_html($s->business_name):'<span style="color:var(--df-muted)">—</span>'; ?></td>
                    <td><?php echo $s->group_name?'<span class="dfem-badge-blue">'.esc_html($s->group_name).'</span>':'<span style="color:var(--df-muted)">—</span>'; ?></td>
                    <td><?php echo $s->status==='subscribed'?'<span class="dfem-badge-green">✅ Subscribed</span>':'<span class="dfem-badge-red">🚫 Unsubscribed</span>'; ?></td>
                    <td><?php echo date('d M Y',strtotime($s->created_at)); ?></td>
                    <td style="white-space:nowrap">
                        <a href="?page=dfem-subscribers&edit=<?php echo $s->id; ?>" class="dfem-btn dfem-btn-secondary dfem-btn-sm">✏️ Edit</a>
                        <button type="button" class="dfem-btn dfem-btn-danger dfem-btn-sm dfem-single-del" data-id="<?php echo $s->id; ?>">🗑️</button>
                    </td>
                </tr>
                <?php endforeach; else: ?><tr><td colspan="8" style="text-align:center;padding:28px;color:var(--df-muted)">No subscribers found.</td></tr><?php endif; ?>
                </tbody>
            </table></div>
            <?php if($subs): ?>
            <div style="display:flex;gap:10px;align-items:center;margin-top:14px;flex-wrap:wrap;padding:14px;background:var(--df-bg);border-radius:10px;border:1px solid var(--df-border);">
                <span style="font-size:.85em;font-weight:600;color:var(--df-muted);">With selected:</span>
                <button type="button" onclick="dfemBulkDelete()" class="dfem-btn dfem-btn-danger dfem-btn-sm">🗑️ Delete Selected</button>
                <span style="color:var(--df-muted);font-size:.85em;">|</span>
                <select id="dfem_bulk_group_sel" style="padding:6px 10px;border:2px solid var(--df-border);border-radius:8px;font-size:.85em;">
                    <option value="0">— Remove from group —</option>
                    <?php foreach($bulk_groups as $g): ?>
                    <option value="<?php echo $g->id; ?>">📍 <?php echo esc_html($g->name); ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="button" onclick="dfemBulkAssignGroup()" class="dfem-btn dfem-btn-primary dfem-btn-sm">✅ Assign Group</button>
                <span id="dfem_sel_count" style="margin-left:auto;font-size:.82em;color:var(--df-muted);"></span>
            </div>
            <?php endif; ?>
        </form>

        <form method="post" id="dfem-single-del-form" style="display:none;">
            <input type="hidden" name="_wpnonce" value="<?php echo $sub_nonce; ?>">
            <input type="hidden" name="dfem_action" value="delete">
            <input type="hidden" name="sub_id" id="dfem_single_del_id" value="">
        </form>

        <script>
        (function(){
            var bulkForm = document.getElementById('dfem-sub-bulk-form');
            var selAll   = document.getElementById('dfem_select_all_subs');
            var counter  = document.getElementById('dfem_sel_count');
            function getChecked(){ return Array.from(bulkForm.querySelectorAll('.sc:checked')); }
            function updateCount(){
                var n = getChecked().length;
                if(counter) counter.textContent = n ? n + ' selected' : '';
                if(selAll){
                    var all = bulkForm.querySelectorAll('.sc');
                    selAll.indeterminate = n > 0 && n < all.length;
                    selAll.checked = n > 0 && n === all.length;
                }
            }
            if(selAll){ selAll.addEventListener('change', function(){ bulkForm.querySelectorAll('.sc').forEach(function(c){ c.checked = selAll.checked; }); updateCount(); }); }
            bulkForm.querySelectorAll('.sc').forEach(function(c){ c.addEventListener('change', updateCount); });
            document.querySelectorAll('.dfem-single-del').forEach(function(btn){
                btn.addEventListener('click', function(){
                    if(!confirm('Delete this subscriber?')) return;
                    document.getElementById('dfem_single_del_id').value = btn.dataset.id;
                    document.getElementById('dfem-single-del-form').submit();
                });
            });
            window.dfemBulkDelete = function(){
                var ids = getChecked();
                if(!ids.length){ alert('No subscribers selected.'); return; }
                if(!confirm('Delete ' + ids.length + ' subscriber(s)? This cannot be undone.')){ return; }
                document.getElementById('dfem_bulk_action_field').value = 'bulk_delete';
                bulkForm.submit();
            };
            window.dfemBulkAssignGroup = function(){
                var ids = getChecked();
                if(!ids.length){ alert('No subscribers selected.'); return; }
                var sel = document.getElementById('dfem_bulk_group_sel');
                var gid = sel.value;
                var gname = sel.options[sel.selectedIndex].text;
                if(!confirm('Assign ' + ids.length + ' subscriber(s) to "' + gname + '"?')){ return; }
                document.getElementById('dfem_bulk_action_field').value = 'bulk_assign_group';
                document.getElementById('dfem_bulk_group_id_input').value = gid;
                bulkForm.submit();
            };
        })();
        </script>

        <?php
        $base_url = add_query_arg(array_filter(['page'=>'dfem-subscribers','s'=>$search?$search:null,'filter'=>$filter!=='all'?$filter:null,'group_filter'=>$group_filter?$group_filter:null]));
        dfem_pagination($paged, $total_pages, $base_url);
        if ($total_pages > 1): ?>
        <p class="dfem-page-info">Showing <?php echo $offset+1; ?>–<?php echo min($offset+$per_page,$total_subs); ?> of <?php echo $total_subs; ?> subscribers</p>
        <?php endif; ?>
    </div>
    <?php dfem_footer();
}

/* =========================================================
   GROUPS
   ========================================================= */
function dfem_page_groups() {
    global $wpdb;
    $table     = $wpdb->prefix.'dfem_groups';
    $sub_table = $wpdb->prefix.'dfem_subscribers';
    $msg       = '';

    if ( isset($_POST['dfem_action']) && isset($_POST['_wpnonce']) && wp_verify_nonce(sanitize_text_field($_POST['_wpnonce']),'dfem_groups_action') ) {
        $act = sanitize_text_field($_POST['dfem_action']);

        if ( $act === 'add' ) {
            $name = sanitize_text_field($_POST['name'] ?? '');
            $desc = sanitize_textarea_field($_POST['description'] ?? '');
            if ( empty($name) ) {
                $msg = '<div class="dfem-alert dfem-alert-error">❌ Group name is required.</div>';
            } elseif ( $wpdb->get_var($wpdb->prepare("SELECT id FROM $table WHERE name=%s", $name)) ) {
                $msg = '<div class="dfem-alert dfem-alert-error">❌ A group with that name already exists.</div>';
            } else {
                $inserted = $wpdb->insert($table, ['name'=>$name,'description'=>$desc], ['%s','%s']);
                $msg = $inserted
                    ? '<div class="dfem-alert dfem-alert-success">✅ Group "<strong>'.esc_html($name).'</strong>" created!</div>'
                    : '<div class="dfem-alert dfem-alert-error">❌ Database error: '.esc_html($wpdb->last_error).'</div>';
            }
        }
        if ( $act === 'edit' ) {
            $id   = (int)($_POST['group_id'] ?? 0);
            $name = sanitize_text_field($_POST['name'] ?? '');
            $desc = sanitize_textarea_field($_POST['description'] ?? '');
            if ( empty($name) ) {
                $msg = '<div class="dfem-alert dfem-alert-error">❌ Group name is required.</div>';
            } else {
                $updated = $wpdb->update($table, ['name'=>$name,'description'=>$desc], ['id'=>$id], ['%s','%s'], ['%d']);
                $msg = $updated !== false
                    ? '<div class="dfem-alert dfem-alert-success">✅ Group updated!</div>'
                    : '<div class="dfem-alert dfem-alert-error">❌ Update failed: '.esc_html($wpdb->last_error).'</div>';
            }
        }
        if ( $act === 'delete' ) {
            $id = (int)($_POST['group_id'] ?? 0);
            $wpdb->update($sub_table, ['group_id'=>0], ['group_id'=>$id], ['%d'], ['%d']);
            $wpdb->delete($table, ['id'=>$id], ['%d']);
            $msg = '<div class="dfem-alert dfem-alert-success">✅ Group deleted.</div>';
        }
    }

    $editing = isset($_GET['edit']) ? $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE id=%d", (int)$_GET['edit'])) : null;
    $groups  = $wpdb->get_results("SELECT g.*, COUNT(s.id) as subscriber_count FROM $table g LEFT JOIN $sub_table s ON s.group_id=g.id GROUP BY g.id ORDER BY g.name ASC");

    dfem_header('Groups'); echo $msg; ?>
    <div class="dfem-card">
        <h2><?php echo $editing ? '✏️ Edit Group' : '➕ Add Group'; ?></h2>
        <form method="post">
            <?php wp_nonce_field('dfem_groups_action'); ?>
            <input type="hidden" name="dfem_action" value="<?php echo $editing ? 'edit' : 'add'; ?>">
            <?php if($editing): ?><input type="hidden" name="group_id" value="<?php echo $editing->id; ?>"><?php endif; ?>
            <div class="dfem-grid2">
                <div class="dfem-form-row">
                    <label>Group Name <span style="color:red">*</span></label>
                    <input type="text" name="name" required placeholder="e.g. Marketing, Sales, VIP Customers" value="<?php echo $editing ? esc_attr($editing->name) : ''; ?>">
                </div>
            </div>
            <div class="dfem-form-row">
                <label>Description <em style="font-weight:400;color:var(--df-muted)">(optional)</em></label>
                <textarea name="description" rows="2" placeholder="What is this group for?" style="width:100%;padding:9px 13px;border:2px solid var(--df-border);border-radius:8px;font-size:.93em;font-family:inherit;box-sizing:border-box;"><?php echo $editing ? esc_textarea($editing->description) : ''; ?></textarea>
            </div>
            <div style="display:flex;gap:10px;">
                <button type="submit" class="dfem-btn dfem-btn-primary"><?php echo $editing ? '💾 Save Group' : '➕ Create Group'; ?></button>
                <?php if($editing): ?><a href="?page=dfem-groups" class="dfem-btn dfem-btn-secondary">✕ Cancel</a><?php endif; ?>
            </div>
        </form>
    </div>

    <div class="dfem-card">
        <h2>📋 Subscriber Groups <span style="font-size:.72em;font-weight:400;color:var(--df-muted)">(<?php echo count($groups); ?> groups)</span></h2>
        <div class="dfem-table-wrap"><table class="dfem-table">
            <thead><tr><th>Group Name</th><th>Description</th><th>Subscribers</th><th>Actions</th></tr></thead>
            <tbody>
            <?php if($groups): foreach($groups as $g): ?>
            <tr>
                <td><strong><?php echo esc_html($g->name); ?></strong></td>
                <td><?php echo $g->description ? esc_html(mb_substr($g->description,0,60)).(mb_strlen($g->description)>60?'…':'') : '<span style="color:var(--df-muted)">—</span>'; ?></td>
                <td><span class="dfem-badge-blue"><?php echo (int)$g->subscriber_count; ?> subscribers</span></td>
                <td style="white-space:nowrap">
                    <a href="?page=dfem-groups&edit=<?php echo $g->id; ?>" class="dfem-btn dfem-btn-secondary dfem-btn-sm">✏️ Edit</a>
                    <form method="post" style="display:inline" onsubmit="return confirm('Delete this group? Subscribers will be moved to no group.');">
                        <?php wp_nonce_field('dfem_groups_action'); ?>
                        <input type="hidden" name="dfem_action" value="delete">
                        <input type="hidden" name="group_id" value="<?php echo $g->id; ?>">
                        <button type="submit" class="dfem-btn dfem-btn-danger dfem-btn-sm">🗑️ Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; else: ?>
            <tr><td colspan="4" style="text-align:center;padding:28px;color:var(--df-muted)">No groups yet. Use the form above to create your first group.</td></tr>
            <?php endif; ?>
            </tbody>
        </table></div>
    </div>
    <?php dfem_footer();
}

/* =========================================================
   CAMPAIGNS  (with pagination)
   ========================================================= */
function dfem_page_campaigns() {
    global $wpdb;
    $table    = $wpdb->prefix.'dfem_campaigns';
    $per_page = 20;
    $msg = '';

    if ( isset($_POST['dfem_action']) && isset($_POST['_wpnonce']) ) {
        $act = sanitize_text_field($_POST['dfem_action']);
        if ( $act==='delete' && wp_verify_nonce(sanitize_text_field($_POST['_wpnonce']),'dfem_camp_action') ) {
            $wpdb->delete($table,['id'=>(int)$_POST['camp_id']],['%d']);
            $msg='<div class="dfem-alert dfem-alert-success">✅ Campaign deleted.</div>';
        }
        if ( $act==='bulk_delete' && wp_verify_nonce(sanitize_text_field($_POST['_wpnonce']),'dfem_camp_bulk') && !empty($_POST['bulk_ids']) ) {
            $ids=array_map('intval',(array)$_POST['bulk_ids']);
            $ph=implode(',',array_fill(0,count($ids),'%d'));
            $wpdb->query(vsprintf("DELETE FROM $table WHERE id IN ($ph)", $ids));
            $msg="<div class='dfem-alert dfem-alert-success'>✅ Deleted <strong>".count($ids)."</strong> campaign(s).</div>";
        }
    }

    // Preview
    if(isset($_GET['preview'])){
        $c=$wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE id=%d",(int)$_GET['preview']));
        if($c){
            dfem_header('Email Preview');
            echo '<div class="dfem-card"><h2>👁️ Preview: '.esc_html($c->subject).'</h2>';
            echo '<div style="margin-bottom:14px"><a href="?page=dfem-campaigns" class="dfem-btn dfem-btn-secondary">← Back</a></div>';
            echo '<div class="dfem-preview-wrap">'.dfem_build_email($c->subject,$c->body).'</div></div>';
            dfem_footer(); return;
        }
    }

    // Edit draft — WITH GROUP SELECTOR, ATTACHMENTS & TEST EMAIL
    if(isset($_GET['edit'])){
        $c=$wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE id=%d AND status='draft'",(int)$_GET['edit']));
        if($c){
            $attach_key  = 'dfem_attachments_draft_'.$c->id.'_'.get_current_user_id();
            $attachments = get_transient($attach_key) ?: [];

            if(isset($_POST['dfem_action']) && check_admin_referer('dfem_camp_action')){
                $act      = sanitize_text_field($_POST['dfem_action']);
                $subject  = sanitize_text_field($_POST['subject']??'');
                $body     = wp_kses_post($_POST['body']??'');
                $group_id = (int)($_POST['group_id']??0);

                if($act==='upload_attachment'){
                    if(!empty($_FILES['attachment']['name'])){
                        require_once ABSPATH.'wp-admin/includes/file.php';
                        $file=wp_handle_upload($_FILES['attachment'],['test_form'=>false]);
                        if(isset($file['url'])){
                            $attachments[]=['name'=>sanitize_text_field($_FILES['attachment']['name']),'path'=>$file['file'],'url'=>$file['url']];
                            set_transient($attach_key,$attachments,3600);
                            $msg='<div class="dfem-alert dfem-alert-success">✅ Attachment added: '.esc_html($_FILES['attachment']['name']).'</div>';
                        } else { $msg='<div class="dfem-alert dfem-alert-error">❌ Upload failed.</div>'; }
                    }
                }
                if($act==='remove_attachment'){
                    $idx=(int)($_POST['attach_idx']??0);
                    if(isset($attachments[$idx])){@unlink($attachments[$idx]['path']);array_splice($attachments,$idx,1);set_transient($attach_key,array_values($attachments),3600);}
                    $msg='<div class="dfem-alert dfem-alert-success">✅ Attachment removed.</div>';
                }
                if($act==='test'){
                    $test_email=sanitize_email($_POST['test_email']??'');
                    $settings_t=get_option('dfem_settings',[]);
                    if(empty($subject)){ $msg='<div class="dfem-alert dfem-alert-error">❌ Please enter a subject before sending a test.</div>'; }
                    elseif(!is_email($test_email)){ $msg='<div class="dfem-alert dfem-alert-error">❌ Enter a valid test email address.</div>'; }
                    else {
                        $html=dfem_build_email($subject,$body?:'<p>This is a test email from DadsFam Email Marketing.</p>','#');
                        $headers=['Content-Type: text/html; charset=UTF-8','From: '.($settings_t['from_name']??get_bloginfo('name')).' <'.($settings_t['from_email']??get_bloginfo('admin_email')).'>','Reply-To: noreply@'.$_SERVER['HTTP_HOST']];
                        $files=array_column($attachments,'path');
                        global $dfem_sending; $dfem_sending=true;
                        $ok=wp_mail($test_email,'[TEST] '.$subject,$html,$headers,$files);
                        $dfem_sending=false;
                        $msg=$ok?'<div class="dfem-alert dfem-alert-success">✅ Test email sent to <strong>'.esc_html($test_email).'</strong>!</div>':'<div class="dfem-alert dfem-alert-error">❌ Failed to send. Check your WordPress mail/SMTP settings.</div>';
                    }
                }
                $attachments=get_transient($attach_key)?:[];

                if(empty($subject)&&in_array($act,['preview','send'])){
                    $msg='<div class="dfem-alert dfem-alert-error">❌ Subject is required.</div>';
                } elseif($act==='save_draft'){
                    if(empty($subject)) { $msg='<div class="dfem-alert dfem-alert-error">❌ Subject is required.</div>'; }
                    else {
                        $wpdb->update($table,['subject'=>$subject,'body'=>$body,'group_id'=>$group_id],['id'=>$c->id],['%s','%s','%d'],['%d']);
                        $msg='<div class="dfem-alert dfem-alert-success">✅ Draft updated! <a href="?page=dfem-campaigns">← Back to campaigns</a></div>';
                        $c->subject  = $subject;
                        $c->body     = $body;
                        $c->group_id = $group_id;
                    }
                } elseif($act==='preview'){
                    dfem_header('Email Preview');
                    echo '<div class="dfem-card"><h2>👁️ Preview: '.esc_html($subject).'</h2>';
                    echo '<div style="margin-bottom:14px"><a href="?page=dfem-campaigns&edit='.$c->id.'" class="dfem-btn dfem-btn-secondary">← Back to Draft</a></div>';
                    echo '<div class="dfem-preview-wrap">'.dfem_build_email($subject,$body).'</div></div>';
                    dfem_footer(); return;
                } elseif($act==='send'){
                    $where_send = "WHERE status='subscribed'";
                    if($group_id) $where_send .= $wpdb->prepare(" AND group_id=%d", $group_id);
                    $subs=$wpdb->get_results("SELECT * FROM {$wpdb->prefix}dfem_subscribers $where_send");
                    if(empty($subs)){
                        $msg=$group_id?'<div class="dfem-alert dfem-alert-error">❌ No active subscribers in this group.</div>':'<div class="dfem-alert dfem-alert-error">❌ No active subscribers.</div>';
                    } else {
                        $settings   = get_option('dfem_settings',[]);
                        $from_name  = $settings['from_name']  ?? get_bloginfo('name');
                        $from_email = $settings['from_email'] ?? get_bloginfo('admin_email');
                        $files = array_column($attachments,'path');
                        $sent = 0;
                        global $dfem_sending; $dfem_sending = true;
                        foreach($subs as $sub){
                            $pb   = str_replace(['{{first_name}}','{{last_name}}','{{email}}','{{business}}'],
                                [esc_html($sub->first_name),esc_html($sub->last_name),esc_html($sub->email),esc_html($sub->business_name)],$body);
                            $html = dfem_build_email($subject,$pb,dfem_unsub_url($sub->email,$sub->token));
                            $hdrs = ['Content-Type: text/html; charset=UTF-8',"From: $from_name <$from_email>","Reply-To: noreply@{$_SERVER['HTTP_HOST']}"];
                            if(wp_mail($sub->email,$subject,$html,$hdrs,$files)) $sent++;
                        }
                        $dfem_sending = false;
                        $wpdb->update($table,['status'=>'sent','sent_to'=>$sent,'sent_at'=>current_time('mysql'),'group_id'=>$group_id],['id'=>$c->id],['%s','%d','%s','%d'],['%d']);
                        delete_transient($attach_key);
                        $msg="<div class='dfem-alert dfem-alert-success'>✅ Campaign sent to <strong>$sent</strong> subscribers! <a href='?page=dfem-campaigns'>← Back to campaigns</a></div>";
                        $c=null;
                    }
                }
            }

            if($c){
                $groups_all  = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}dfem_groups ORDER BY name");
                $settings_d  = get_option('dfem_settings',[]);
                dfem_header('Edit Draft'); echo $msg; ?>
                <div class="dfem-card">
                    <h2>✏️ Edit Draft Campaign</h2>
                    <p style="color:var(--df-muted);margin:-8px 0 20px">Use <code>{{first_name}}</code> <code>{{last_name}}</code> <code>{{email}}</code> <code>{{business}}</code> for personalisation.</p>
                    <form method="post" enctype="multipart/form-data">
                        <?php wp_nonce_field('dfem_camp_action'); ?>
                        <div class="dfem-form-row">
                            <label>Subject Line <span style="color:red">*</span></label>
                            <input type="text" name="subject" required placeholder="Subject..." value="<?php echo esc_attr($c->subject); ?>">
                        </div>
                        <div class="dfem-form-row">
                            <label>Email Body</label>
                            <?php wp_editor($c->body,'dfem_body',['textarea_name'=>'body','media_buttons'=>true,'textarea_rows'=>18]); ?>
                        </div>
                        <!-- GROUP SELECTOR -->
                        <div class="dfem-form-row" style="margin-top:18px;">
                            <label>Send To Group <em style="font-weight:400;color:var(--df-muted)">(optional — all active subscribers if not selected)</em></label>
                            <select name="group_id" style="width:100%;padding:9px 13px;border:2px solid var(--df-border);border-radius:8px;font-size:.93em;">
                                <option value="0" <?php selected((int)$c->group_id,0); ?>>— All Active Subscribers —</option>
                                <?php foreach($groups_all as $g): ?>
                                <option value="<?php echo $g->id; ?>" <?php selected((int)$c->group_id,(int)$g->id); ?>>📍 <?php echo esc_html($g->name); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <small style="color:var(--df-muted)">Choose a group to send only to subscribers in that group. <a href="?page=dfem-groups" style="color:var(--df-blue)">Manage groups →</a></small>
                        </div>
                        <!-- ATTACHMENTS -->
                        <div class="dfem-form-row" style="margin-top:20px">
                            <label>📎 Attachments</label>
                            <?php if($attachments): foreach($attachments as $i=>$att): ?>
                            <div class="attachment-item">
                                <span>📄 <?php echo esc_html($att['name']); ?></span>
                                <button type="submit" name="dfem_action" value="remove_attachment" formnovalidate
                                    onclick="document.getElementById('dfem-draft-aidx').value=<?php echo $i; ?>"
                                    class="dfem-btn dfem-btn-danger dfem-btn-sm">✕ Remove</button>
                            </div>
                            <?php endforeach; endif; ?>
                            <input type="hidden" id="dfem-draft-aidx" name="attach_idx" value="0">
                            <div style="display:flex;gap:10px;align-items:center;margin-top:8px;flex-wrap:wrap;">
                                <input type="file" name="attachment" style="padding:8px;border:2px dashed var(--df-border);border-radius:8px;background:#f8faff;flex:1;min-width:200px;">
                                <button type="submit" name="dfem_action" value="upload_attachment" formnovalidate class="dfem-btn dfem-btn-secondary">📎 Upload</button>
                            </div>
                            <small style="color:var(--df-muted)">Max: <?php echo ini_get('upload_max_filesize'); ?>. Attachments are included in every sent email.</small>
                        </div>
                        <!-- TEST EMAIL -->
                        <div class="dfem-card" style="background:#f8faff;margin-top:20px">
                            <h2 style="font-size:1em;border:none;padding:0;margin:0 0 12px">📨 Send Test Email</h2>
                            <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end;">
                                <div style="flex:1;min-width:220px">
                                    <label style="font-size:.88em;font-weight:600;display:block;margin-bottom:5px">Test recipient:</label>
                                    <input type="email" name="test_email" placeholder="your@email.com" value="<?php echo esc_attr($settings_d['from_email']??''); ?>" style="width:100%;padding:9px 13px;border:2px solid var(--df-border);border-radius:8px;font-size:.93em;box-sizing:border-box;">
                                </div>
                                <button type="submit" name="dfem_action" value="test" formnovalidate class="dfem-btn dfem-btn-warning">📨 Send Test</button>
                            </div>
                        </div>
                        <!-- ACTIONS -->
                        <div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:20px">
                            <button type="submit" name="dfem_action" value="preview" formnovalidate class="dfem-btn dfem-btn-secondary">👁️ Preview</button>
                            <button type="submit" name="dfem_action" value="save_draft" formnovalidate class="dfem-btn dfem-btn-secondary">💾 Save Draft</button>
                            <button type="submit" name="dfem_action" value="send" class="dfem-btn dfem-btn-success" onclick="return confirm('Send this campaign now?')">📤 Send Campaign</button>
                            <a href="?page=dfem-campaigns" class="dfem-btn dfem-btn-secondary">← Cancel</a>
                        </div>
                    </form>
                </div>
                <?php
                dfem_footer(); return;
            }
        }
    }

    // Campaign list with pagination
    $paged       = max(1,(int)($_GET['paged']??1));
    $total_camps = (int)$wpdb->get_var("SELECT COUNT(*) FROM $table");
    $total_pages = max(1,ceil($total_camps/$per_page));
    $offset      = ($paged-1)*$per_page;
    $has_grp_col = $wpdb->get_var("SHOW COLUMNS FROM $table LIKE 'group_id'");
    if($has_grp_col){
        $camps = $wpdb->get_results("SELECT c.*, g.name as group_name FROM $table c LEFT JOIN {$wpdb->prefix}dfem_groups g ON g.id=c.group_id ORDER BY c.created_at DESC LIMIT $per_page OFFSET $offset");
    } else {
        $camps = $wpdb->get_results("SELECT *, '' as group_name FROM $table ORDER BY created_at DESC LIMIT $per_page OFFSET $offset");
    }

    dfem_header('Campaigns'); echo $msg; ?>
    <div class="dfem-card">
        <h2>📋 All Campaigns <span style="font-size:.72em;font-weight:400;color:var(--df-muted)">(<?php echo $total_camps; ?> total · page <?php echo $paged; ?> of <?php echo $total_pages; ?>)</span></h2>
        <div style="margin-bottom:18px"><a href="?page=dfem-new-campaign" class="dfem-btn dfem-btn-primary">✏️ New Campaign</a></div>

        <?php $camp_nonce = wp_create_nonce('dfem_camp_action'); ?>

        <!-- Bulk delete form — NO nested forms inside this one -->
        <form method="post" id="dfem-camp-bulk-form" onsubmit="return confirm('Delete selected campaigns?');">
            <?php wp_nonce_field('dfem_camp_bulk'); ?>
            <input type="hidden" name="dfem_action" value="bulk_delete">
            <div class="dfem-table-wrap"><table class="dfem-table">
                <thead><tr>
                    <th><input type="checkbox" id="dfem_select_all_camps" style="cursor:pointer"></th>
                    <th>Subject</th><th>Group</th><th>Status</th><th>Sent To</th><th>Opens / Clicks</th><th>Sent / Scheduled</th><th>Created</th><th>Actions</th>
                </tr></thead>
                <tbody><?php if($camps): foreach($camps as $c): ?>
                <tr>
                    <td><input type="checkbox" class="cc" name="bulk_ids[]" value="<?php echo $c->id; ?>"></td>
                    <td><strong><?php echo esc_html($c->subject); ?></strong></td>
                    <td><?php echo $c->group_name ? '<span class="dfem-badge-purple">📍 '.esc_html($c->group_name).'</span>' : '<span style="color:var(--df-muted);font-size:.85em">All subscribers</span>'; ?></td>
                    <td><?php
                        if($c->status==='sent') echo '<span class="dfem-badge-green">📤 Sent</span>';
                        elseif($c->status==='scheduled') echo '<span class="dfem-badge-scheduled">📅 Scheduled</span>';
                        else echo '<span class="dfem-badge-gray">📝 Draft</span>';
                    ?></td>
                    <td><?php echo $c->sent_to ? $c->sent_to.' recipients' : ($c->status==='scheduled' ? '<span style="color:var(--df-muted)">Pending</span>' : '—'); ?></td>
                    <td>
                        <?php if($c->status==='sent' && isset($c->opens)): ?>
                        <span class="dfem-stat-mini" title="Opens">👁️ <?php echo (int)$c->opens; ?></span>
                        <span class="dfem-stat-mini" title="Clicks">🔗 <?php echo (int)$c->clicks; ?></span>
                        <?php else: echo '—'; endif; ?>
                    </td>
                    <td><?php echo $c->status==='scheduled' && $c->scheduled_at ? date('d M Y H:i',strtotime($c->scheduled_at)) : ($c->sent_at ? date('d M Y H:i',strtotime($c->sent_at)) : '—'); ?></td>
                    <td><?php echo date('d M Y',strtotime($c->created_at)); ?></td>
                    <td style="white-space:nowrap">
                        <a href="?page=dfem-campaigns&preview=<?php echo $c->id; ?>" class="dfem-btn dfem-btn-secondary dfem-btn-sm">👁️ Preview</a>
                        <?php if($c->status==='draft'): ?>
                        <a href="?page=dfem-campaigns&edit=<?php echo $c->id; ?>" class="dfem-btn dfem-btn-primary dfem-btn-sm">✏️ Edit</a>
                        <?php endif; ?>
                        <!-- Single delete: uses separate form outside, triggered by JS -->
                        <button type="button" class="dfem-btn dfem-btn-danger dfem-btn-sm dfem-camp-del" data-id="<?php echo $c->id; ?>">🗑️</button>
                    </td>
                </tr>
                <?php endforeach; else: ?><tr><td colspan="9" style="text-align:center;padding:28px;color:var(--df-muted)">No campaigns yet.</td></tr><?php endif; ?>
                </tbody>
            </table></div>
            <?php if($camps): ?>
            <div style="margin-top:12px">
                <button type="submit" class="dfem-btn dfem-btn-danger dfem-btn-sm">🗑️ Delete Selected</button>
            </div>
            <?php endif; ?>
        </form>

        <!-- Separate single-delete form — lives OUTSIDE the bulk form -->
        <form method="post" id="dfem-camp-single-del-form" style="display:none;">
            <input type="hidden" name="_wpnonce" value="<?php echo $camp_nonce; ?>">
            <input type="hidden" name="dfem_action" value="delete">
            <input type="hidden" name="camp_id" id="dfem_camp_del_id" value="">
        </form>

        <script>
        (function(){
            var selAll = document.getElementById('dfem_select_all_camps');
            if(selAll){
                selAll.addEventListener('change', function(){
                    document.querySelectorAll('.cc').forEach(function(c){ c.checked = selAll.checked; });
                });
            }
            document.querySelectorAll('.dfem-camp-del').forEach(function(btn){
                btn.addEventListener('click', function(){
                    if(!confirm('Delete this campaign?')) return;
                    document.getElementById('dfem_camp_del_id').value = btn.dataset.id;
                    document.getElementById('dfem-camp-single-del-form').submit();
                });
            });
        })();
        </script>

        <?php
        $base_url = add_query_arg(['page'=>'dfem-campaigns']);
        dfem_pagination($paged, $total_pages, $base_url);
        if ($total_pages > 1): ?>
        <p class="dfem-page-info">Showing <?php echo $offset+1; ?>–<?php echo min($offset+$per_page,$total_camps); ?> of <?php echo $total_camps; ?> campaigns</p>
        <?php endif; ?>
    </div>
    <?php dfem_footer();
}

/* =========================================================
   NEW CAMPAIGN
   ========================================================= */
function dfem_page_new_campaign() {
    global $wpdb;
    $msg = '';
    $settings   = get_option('dfem_settings',[]);
    $attach_key = 'dfem_attachments_'.get_current_user_id();
    $attachments= get_transient($attach_key) ?: [];

    if ( isset($_POST['dfem_action']) && check_admin_referer('dfem_campaign') ) {
        $act      = sanitize_text_field($_POST['dfem_action']);
        $subject  = sanitize_text_field($_POST['subject']??'');
        $body     = wp_kses_post($_POST['body']??'');
        $group_id = (int)($_POST['group_id']??0);

        if ( $act === 'upload_attachment' ) {
            if ( !empty($_FILES['attachment']['name']) ) {
                require_once ABSPATH.'wp-admin/includes/file.php';
                $file = wp_handle_upload($_FILES['attachment'],['test_form'=>false]);
                if ( isset($file['url']) ) {
                    $attachments[] = ['name'=>sanitize_text_field($_FILES['attachment']['name']),'path'=>$file['file'],'url'=>$file['url']];
                    set_transient($attach_key,$attachments,3600);
                    $msg = '<div class="dfem-alert dfem-alert-success">✅ Attachment added: '.esc_html($_FILES['attachment']['name']).'</div>';
                } else { $msg='<div class="dfem-alert dfem-alert-error">❌ Upload failed.</div>'; }
            }
        }
        if ( $act === 'remove_attachment' ) {
            $idx=(int)$_POST['attach_idx'];
            if(isset($attachments[$idx])){@unlink($attachments[$idx]['path']);array_splice($attachments,$idx,1);set_transient($attach_key,array_values($attachments),3600);}
            $msg='<div class="dfem-alert dfem-alert-success">✅ Attachment removed.</div>';
        }
        if ( empty($subject) && in_array($act,['preview','send','schedule']) ) {
            $msg='<div class="dfem-alert dfem-alert-error">❌ Subject is required.</div>';
        } elseif ( $act==='save_draft' ) {
            if(empty($subject)) { $msg='<div class="dfem-alert dfem-alert-error">❌ Subject is required to save a draft.</div>'; }
            else {
                $wpdb->insert($wpdb->prefix.'dfem_campaigns',['subject'=>$subject,'body'=>$body,'group_id'=>$group_id,'status'=>'draft'],['%s','%s','%d','%s']);
                $msg='<div class="dfem-alert dfem-alert-success">✅ Draft saved! <a href="?page=dfem-campaigns">View all →</a></div>';
            }
        } elseif ( $act==='schedule' ) {
            if (!dfem_is_premium()) {
                $msg='<div class="dfem-alert dfem-alert-error">🔒 Campaign Scheduling requires a Premium license key.</div>';
            } elseif (empty($subject)) {
                $msg='<div class="dfem-alert dfem-alert-error">❌ Subject is required.</div>';
            } else {
                $sched_raw = sanitize_text_field($_POST['scheduled_at'] ?? '');
                $sched_at  = $sched_raw ? date('Y-m-d H:i:s', strtotime($sched_raw)) : null;
                if (!$sched_at || strtotime($sched_at) <= time()) {
                    $msg='<div class="dfem-alert dfem-alert-error">❌ Please choose a future date and time for scheduling.</div>';
                } else {
                    $track = dfem_is_premium() && !empty($_POST['tracking_enabled']) ? 1 : 0;
                    $wpdb->insert($wpdb->prefix.'dfem_campaigns',['subject'=>$subject,'body'=>$body,'group_id'=>$group_id,'status'=>'scheduled','scheduled_at'=>$sched_at,'tracking_enabled'=>$track],['%s','%s','%d','%s','%s','%d']);
                    $msg='<div class="dfem-alert dfem-alert-success">✅ Campaign scheduled for <strong>'.date('d M Y H:i',strtotime($sched_at)).'</strong>! <a href="?page=dfem-campaigns">View campaigns →</a></div>';
                }
            }
        } elseif ( $act==='preview' ) {
            dfem_header('Email Preview');
            echo '<div class="dfem-card"><h2>👁️ Preview: '.esc_html($subject).'</h2>';
            echo '<p style="color:var(--df-muted)">This is how your email will look to subscribers.</p>';
            echo '<div style="margin-bottom:14px"><a href="?page=dfem-new-campaign" class="dfem-btn dfem-btn-secondary">← Back to Editor</a></div>';
            echo '<div class="dfem-preview-wrap">'.dfem_build_email($subject,$body).'</div></div>';
            dfem_footer(); return;
        } elseif ( $act==='test' ) {
            $test_email = sanitize_email($_POST['test_email']??'');
            if(empty($subject)){ $msg='<div class="dfem-alert dfem-alert-error">❌ Please enter a subject before sending a test.</div>'; }
            elseif(!is_email($test_email)){
                $msg='<div class="dfem-alert dfem-alert-error">❌ Enter a valid test email address.</div>';
            } else {
                $html    = dfem_build_email($subject, $body ?: '<p>This is a test email from DadsFam Email Marketing.</p>', '#');
                $headers = ['Content-Type: text/html; charset=UTF-8','From: '.($settings['from_name']??get_bloginfo('name')).' <'.($settings['from_email']??get_bloginfo('admin_email')).'>','Reply-To: noreply@'.$_SERVER['HTTP_HOST']];
                $files   = array_column($attachments,'path');
                global $dfem_sending; $dfem_sending = true;
                $ok      = wp_mail($test_email,'[TEST] '.$subject,$html,$headers,$files);
                $dfem_sending = false;
                $msg = $ok
                    ? '<div class="dfem-alert dfem-alert-success">✅ Test email sent to <strong>'.esc_html($test_email).'</strong>! Check your inbox (and spam folder).</div>'
                    : '<div class="dfem-alert dfem-alert-error">❌ Failed to send. Check your WordPress mail/SMTP settings.</div>';
            }
        } elseif ( $act==='send' ) {
            $where = "WHERE status='subscribed'";
            if($group_id) $where .= $wpdb->prepare(" AND group_id=%d", $group_id);
            $subs = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}dfem_subscribers $where");
            if(empty($subs)){$msg=$group_id?'<div class="dfem-alert dfem-alert-error">❌ No active subscribers in this group.</div>':'<div class="dfem-alert dfem-alert-error">❌ No active subscribers.</div>';}
            else {
                $track = dfem_is_premium() && !empty($_POST['tracking_enabled']) ? 1 : 0;
                $wpdb->insert($wpdb->prefix.'dfem_campaigns',['subject'=>$subject,'body'=>$body,'group_id'=>$group_id,'status'=>'draft','tracking_enabled'=>$track],['%s','%s','%d','%s','%d']);
                $new_id = $wpdb->insert_id;
                $sent   = dfem_dispatch_campaign($new_id);
                $msg = $sent !== false
                    ? "<div class='dfem-alert dfem-alert-success'>✅ Campaign sent to <strong>$sent</strong> subscribers! <a href='?page=dfem-campaigns'>View campaigns →</a></div>"
                    : "<div class='dfem-alert dfem-alert-error'>❌ Could not send. Check your SMTP settings.</div>";
                $attachments = [];
            }
        }
        $attachments = get_transient($attach_key) ?: [];
    }

    $saved_subject = isset($_POST['subject']) ? sanitize_text_field($_POST['subject']) : '';
    $saved_body    = isset($_POST['body'])    ? wp_kses_post($_POST['body'])            : '';
    $saved_group   = isset($_POST['group_id'])? (int)$_POST['group_id'] : 0;

    $groups = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}dfem_groups ORDER BY name");

    dfem_header('New Campaign'); echo $msg; ?>
    <div class="dfem-card">
        <h2>✏️ Compose Email Campaign</h2>
        <p style="color:var(--df-muted);margin:-8px 0 20px">Use <code>{{first_name}}</code> <code>{{last_name}}</code> <code>{{email}}</code> <code>{{business}}</code> for personalisation.</p>
        <form method="post" enctype="multipart/form-data">
            <?php wp_nonce_field('dfem_campaign'); ?>
            <div class="dfem-form-row">
                <label>Subject Line <span style="color:red">*</span></label>
                <input type="text" name="subject" required placeholder="🎉 Exciting news from DadsFam!" value="<?php echo esc_attr($saved_subject); ?>">
            </div>
            <div class="dfem-form-row">
                <label>Email Body <span style="color:red">*</span></label>
                <?php wp_editor($saved_body,'dfem_body',['textarea_name'=>'body','media_buttons'=>true,'textarea_rows'=>18]); ?>
            </div>

            <div class="dfem-form-row">
                <label>Send To Group <em style="font-weight:400;color:var(--df-muted)">(optional — all active subscribers if not selected)</em></label>
                <select name="group_id" style="width:100%;padding:9px 13px;border:2px solid var(--df-border);border-radius:8px;font-size:.93em;">
                    <option value="0" <?php selected($saved_group,0); ?>>— All Active Subscribers —</option>
                    <?php foreach($groups as $g): ?>
                    <option value="<?php echo $g->id; ?>" <?php selected($saved_group,(int)$g->id); ?>>📍 <?php echo esc_html($g->name); ?></option>
                    <?php endforeach; ?>
                </select>
                <small style="color:var(--df-muted)">Choose a group to send only to subscribers in that group. <a href="?page=dfem-groups" style="color:var(--df-blue)">Manage groups →</a></small>
            </div>

            <div class="dfem-form-row" style="margin-top:20px">
                <label>📎 Attachments</label>
                <?php if($attachments): foreach($attachments as $i=>$att): ?>
                <div class="attachment-item">
                    <span>📄 <?php echo esc_html($att['name']); ?></span>
                    <button type="submit" name="dfem_action" value="remove_attachment" formnovalidate
                        onclick="document.getElementById('dfem-aidx').value=<?php echo $i; ?>"
                        class="dfem-btn dfem-btn-danger dfem-btn-sm">✕ Remove</button>
                </div>
                <?php endforeach; endif; ?>
                <input type="hidden" id="dfem-aidx" name="attach_idx" value="0">
                <div style="display:flex;gap:10px;align-items:center;margin-top:8px;flex-wrap:wrap;">
                    <input type="file" name="attachment" style="padding:8px;border:2px dashed var(--df-border);border-radius:8px;background:#f8faff;flex:1;min-width:200px;">
                    <button type="submit" name="dfem_action" value="upload_attachment" formnovalidate class="dfem-btn dfem-btn-secondary">📎 Upload</button>
                </div>
                <small style="color:var(--df-muted)">Max: <?php echo ini_get('upload_max_filesize'); ?>. Attachments are included in every sent email.</small>
            </div>

            <div class="dfem-card" style="background:#f8faff;margin-top:20px">
                <h2 style="font-size:1em;border:none;padding:0;margin:0 0 12px">📨 Send Test Email</h2>
                <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end;">
                    <div style="flex:1;min-width:220px">
                        <label style="font-size:.88em;font-weight:600;display:block;margin-bottom:5px">Test recipient:</label>
                        <input type="email" name="test_email" placeholder="your@email.com" value="<?php echo esc_attr($settings['from_email']??''); ?>" style="width:100%;padding:9px 13px;border:2px solid var(--df-border);border-radius:8px;font-size:.93em;box-sizing:border-box;">
                    </div>
                    <button type="submit" name="dfem_action" value="test" formnovalidate class="dfem-btn dfem-btn-warning">📨 Send Test</button>
                </div>
            </div>

            <div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:20px">
                <!-- PREMIUM: Open & Click Tracking Toggle -->
                <?php if(dfem_is_premium()): ?>
                <div style="width:100%;background:#f4fdf9;border:2px solid #00A878;border-radius:10px;padding:14px 18px;">
                    <label style="display:flex;align-items:center;gap:10px;cursor:pointer;font-weight:600;color:#005c34;">
                        <input type="checkbox" name="tracking_enabled" value="1" style="width:auto;transform:scale(1.3);">
                        📊 Enable Open & Click Tracking for this campaign
                    </label>
                    <small style="color:var(--df-muted);margin-top:4px;display:block;">Tracks who opens your email and which links they click. View results under 📊 Tracking in the menu.</small>
                </div>
                <?php else: ?>
                <div style="width:100%;background:#fffbeb;border:1px dashed #f59e0b;border-radius:8px;padding:12px 16px;font-size:.88em;color:#78350f;">
                    🔒 <strong>Open & Click Tracking</strong> is a Premium feature. <a href="?page=dfem-settings&tab=license" style="color:#d97706;font-weight:600;">Activate a license key to unlock →</a>
                </div>
                <?php endif; ?>
            </div>

            <div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:16px;align-items:flex-start;">
                <button type="submit" name="dfem_action" value="preview"    formnovalidate class="dfem-btn dfem-btn-secondary">👁️ Preview</button>
                <button type="submit" name="dfem_action" value="save_draft" formnovalidate class="dfem-btn dfem-btn-secondary">💾 Save Draft</button>
                <button type="submit" name="dfem_action" value="send"       class="dfem-btn dfem-btn-success" onclick="return confirm('Send this campaign to the selected audience now?')">📤 Send Now</button>

                <!-- PREMIUM: Schedule for Later -->
                <?php if(dfem_is_premium()): ?>
                <div class="dfem-schedule-box" style="flex:1;min-width:280px;">
                    <strong style="color:#92400e;display:block;margin-bottom:10px;">📅 Schedule for Later</strong>
                    <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
                        <input type="datetime-local" name="scheduled_at" min="<?php echo date('Y-m-d\TH:i'); ?>" style="padding:9px 12px;border:2px solid #f59e0b;border-radius:8px;font-size:.9em;flex:1;min-width:200px;">
                        <button type="submit" name="dfem_action" value="schedule" formnovalidate class="dfem-btn dfem-btn-warning" onclick="return confirm('Schedule this campaign?')">📅 Schedule</button>
                    </div>
                    <small style="color:#92400e;margin-top:6px;display:block;">Sends automatically at the chosen date and time via WP Cron.</small>
                </div>
                <?php else: ?>
                <div style="background:#fffbeb;border:1px dashed #f59e0b;border-radius:8px;padding:12px 16px;font-size:.88em;color:#78350f;flex:1;min-width:200px;">
                    🔒 <strong>Campaign Scheduling</strong> is a Premium feature.<br>
                    <a href="?page=dfem-settings&tab=license" style="color:#d97706;font-weight:600;">Activate a license key to unlock →</a>
                </div>
                <?php endif; ?>
            </div>
        </form>
    </div>
    <?php dfem_footer();
}

/* =========================================================
   TRACKING PAGE (PREMIUM)
   ========================================================= */
function dfem_page_tracking() {
    global $wpdb;
    dfem_header('📊 Tracking');

    if (!dfem_is_premium()) { ?>
        <div class="dfem-card">
            <h2>📊 Open & Click Tracking</h2>
            <?php
            echo '<div style="text-align:center;padding:40px 20px">';
            echo '<div style="font-size:3em;margin-bottom:16px">🔒</div>';
            echo '<h3 style="color:var(--df-text);margin:0 0 8px">Open & Click Tracking — Premium Feature</h3>';
            echo '<p style="color:var(--df-muted);max-width:480px;margin:0 auto 20px">See exactly who opened your emails and which links they clicked. Upgrade to DadsFam Email Marketing Premium to unlock full tracking analytics.</p>';
            echo '<a href="?page=dfem-settings&tab=license" class="dfem-btn dfem-btn-primary">⭐ Activate Premium License →</a>';
            echo '</div>';
            ?>
        </div>
    <?php dfem_footer(); return; }

    $camp_id   = isset($_GET['camp']) ? (int)$_GET['camp'] : 0;
    $campaigns = $wpdb->get_results("SELECT id,subject,sent_to,opens,clicks,sent_at FROM {$wpdb->prefix}dfem_campaigns WHERE status='sent' ORDER BY sent_at DESC LIMIT 50");
    ?>
    <div class="dfem-stats">
        <?php
        $total_opens  = (int)$wpdb->get_var("SELECT SUM(opens) FROM {$wpdb->prefix}dfem_campaigns WHERE status='sent'");
        $total_clicks = (int)$wpdb->get_var("SELECT SUM(clicks) FROM {$wpdb->prefix}dfem_campaigns WHERE status='sent'");
        $total_sent   = (int)$wpdb->get_var("SELECT SUM(sent_to) FROM {$wpdb->prefix}dfem_campaigns WHERE status='sent'");
        $open_rate    = $total_sent > 0 ? round(($total_opens/$total_sent)*100,1) : 0;
        $click_rate   = $total_sent > 0 ? round(($total_clicks/$total_sent)*100,1) : 0;
        ?>
        <div class="dfem-stat"><div class="n"><?php echo $total_opens; ?></div><div class="l">👁️ Total Opens</div></div>
        <div class="dfem-stat" style="border-top-color:#00A878"><div class="n" style="color:#00A878"><?php echo $open_rate; ?>%</div><div class="l">📈 Avg Open Rate</div></div>
        <div class="dfem-stat" style="border-top-color:#7b2cbf"><div class="n" style="color:#7b2cbf"><?php echo $total_clicks; ?></div><div class="l">🔗 Total Clicks</div></div>
        <div class="dfem-stat" style="border-top-color:#f59e0b"><div class="n" style="color:#f59e0b"><?php echo $click_rate; ?>%</div><div class="l">🎯 Avg Click Rate</div></div>
    </div>

    <div class="dfem-card">
        <h2>📋 Campaign Performance</h2>
        <?php if($campaigns): ?>
        <div class="dfem-table-wrap"><table class="dfem-table">
            <thead><tr><th>Campaign</th><th>Sent To</th><th>Opens</th><th>Open Rate</th><th>Clicks</th><th>Click Rate</th><th>Sent</th><th></th></tr></thead>
            <tbody><?php foreach($campaigns as $c):
                $or = $c->sent_to > 0 ? round(($c->opens/$c->sent_to)*100,1) : 0;
                $cr = $c->sent_to > 0 ? round(($c->clicks/$c->sent_to)*100,1) : 0;
            ?>
            <tr>
                <td><strong><?php echo esc_html($c->subject); ?></strong></td>
                <td><?php echo (int)$c->sent_to; ?></td>
                <td>
                    <strong><?php echo (int)$c->opens; ?></strong>
                    <div class="dfem-progress" style="width:80px"><div class="dfem-progress-bar" style="width:<?php echo min(100,$or); ?>%"></div></div>
                </td>
                <td><?php echo $or; ?>%</td>
                <td>
                    <strong><?php echo (int)$c->clicks; ?></strong>
                    <div class="dfem-progress" style="width:80px"><div class="dfem-progress-bar" style="width:<?php echo min(100,$cr*2); ?>%;background:linear-gradient(90deg,#7b2cbf,#5b21b6)"></div></div>
                </td>
                <td><?php echo $cr; ?>%</td>
                <td style="font-size:.83em;color:var(--df-muted)"><?php echo $c->sent_at ? date('d M Y',strtotime($c->sent_at)) : '—'; ?></td>
                <td><a href="?page=dfem-tracking&camp=<?php echo $c->id; ?>" class="dfem-btn dfem-btn-secondary dfem-btn-sm">🔍 Details</a></td>
            </tr>
            <?php endforeach; ?></tbody>
        </table></div>
        <?php else: ?><p style="color:var(--df-muted)">No sent campaigns yet. Enable tracking when composing your next campaign.</p><?php endif; ?>
    </div>

    <?php if($camp_id):
        $camp    = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}dfem_campaigns WHERE id=%d", $camp_id));
        $opens   = $wpdb->get_results($wpdb->prepare("SELECT email,ip_address,created_at FROM {$wpdb->prefix}dfem_tracking WHERE campaign_id=%d AND type='open' ORDER BY created_at DESC LIMIT 100", $camp_id));
        $clicks  = $wpdb->get_results($wpdb->prepare("SELECT email,url,ip_address,created_at FROM {$wpdb->prefix}dfem_tracking WHERE campaign_id=%d AND type='click' ORDER BY created_at DESC LIMIT 100", $camp_id));
        if($camp): ?>
    <div class="dfem-card">
        <h2>🔍 Detail: <?php echo esc_html($camp->subject); ?></h2>
        <div class="dfem-grid2">
            <div>
                <h3 style="margin:0 0 12px;color:var(--df-text);font-size:1em">👁️ Opens (<?php echo count($opens); ?>)</h3>
                <?php if($opens): foreach($opens as $o): ?>
                <div class="dfem-tracking-row">
                    <span><?php echo esc_html($o->email); ?></span>
                    <span style="color:var(--df-muted);font-size:.8em"><?php echo $o->ip_address; ?></span>
                    <span style="color:var(--df-muted);font-size:.8em"><?php echo date('d M H:i',strtotime($o->created_at)); ?></span>
                </div>
                <?php endforeach; else: ?><p style="color:var(--df-muted);font-size:.9em">No opens tracked yet.</p><?php endif; ?>
            </div>
            <div>
                <h3 style="margin:0 0 12px;color:var(--df-text);font-size:1em">🔗 Clicks (<?php echo count($clicks); ?>)</h3>
                <?php if($clicks): foreach($clicks as $cl): ?>
                <div class="dfem-tracking-row">
                    <span><?php echo esc_html($cl->email); ?></span>
                    <span style="font-size:.78em;color:var(--df-muted);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:160px" title="<?php echo esc_attr($cl->url); ?>"><?php echo esc_html(parse_url($cl->url,PHP_URL_HOST).'/...'); ?></span>
                    <span style="color:var(--df-muted);font-size:.8em"><?php echo date('d M H:i',strtotime($cl->created_at)); ?></span>
                </div>
                <?php endforeach; else: ?><p style="color:var(--df-muted);font-size:.9em">No clicks tracked yet.</p><?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; endif; ?>
    <?php dfem_footer();
}

/* =========================================================
   SETTINGS — Completely restructured to fix "Link Expired" bug.
   Root cause: nested <form> tags (License form inside main form).
   Fix: URL-based tabs render ONLY the active tab — one form per
   page load, no nesting possible, always fresh nonce.
   ========================================================= */
function dfem_page_settings() {
    global $wpdb;
    $msg        = '';
    $active_tab = sanitize_text_field($_GET['tab'] ?? 'general');
    $settings   = get_option('dfem_settings', []);

    // ---- Handle General / Branding save ----
    if ( isset($_POST['dfem_save']) && isset($_POST['_wpnonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wpnonce'])), 'dfem_settings') ) {
        $social_links = [];
        if (!empty($_POST['social_label'])) {
            foreach($_POST['social_label'] as $i => $label) {
                $label = sanitize_text_field($label);
                $url   = esc_url_raw($_POST['social_url'][$i] ?? '');
                if ($label && $url) $social_links[] = compact('label','url');
            }
        }
        $settings = [
            'from_name'     => sanitize_text_field($_POST['from_name'] ?? ''),
            'from_email'    => sanitize_email($_POST['from_email'] ?? ''),
            'footer_text'   => sanitize_textarea_field($_POST['footer_text'] ?? ''),
            'logo_media_id' => (int)($_POST['logo_media_id'] ?? 0),
            'primary_color' => sanitize_hex_color($_POST['primary_color'] ?? '') ?: '#0066cc',
            'social_links'  => $social_links,
        ];
        update_option('dfem_settings', $settings);
        $active_tab = sanitize_text_field($_POST['current_tab'] ?? 'general');
        $msg = '<div class="dfem-alert dfem-alert-success">✅ Settings saved!</div>';
    }

    // ---- Handle License save ----
    if ( isset($_POST['dfem_save_license']) && isset($_POST['_wpnonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wpnonce'])), 'dfem_license') ) {
        $key = sanitize_text_field($_POST['license_key'] ?? '');
        update_option('dfem_license_key', $key);
        delete_transient('dfem_license_status');
        if (!empty($key)) {
            // Schedule background check — don't block page load
            wp_schedule_single_event(time() + 2, 'dfem_bg_license_check');
            $msg = '<div class="dfem-alert dfem-alert-success">✅ License key saved! Verification running in background — refresh in ~30 seconds to see your Premium status.</div>';
        } else {
            delete_option('dfem_license_status_cache');
            $msg = '<div class="dfem-alert dfem-alert-info">ℹ️ License key removed.</div>';
        }
        $active_tab = 'license';
    }

    // ---- Handle Force Verify ----
    if ( isset($_POST['dfem_force_verify']) && isset($_POST['_wpnonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wpnonce'])), 'dfem_license') ) {
        $key = sanitize_text_field($_POST['license_key'] ?? '');
        delete_transient('dfem_license_status');
        if (!empty($key)) {
            $valid = dfem_verify_license($key);
            $msg = $valid
                ? '<div class="dfem-alert dfem-alert-success">✅ License verified! Premium features are now active.</div>'
                : '<div class="dfem-alert dfem-alert-error">❌ Key could not be verified. Check it is Active in your License Manager and the site URL matches.</div>';
        }
        $active_tab = 'license';
    }

    $social_links  = $settings['social_links']  ?? [];
    $logo_media_id = (int)($settings['logo_media_id'] ?? 0);
    $logo_url      = $logo_media_id ? wp_get_attachment_url($logo_media_id) : '';
    $unsub_id      = get_option('dfem_unsub_page_id');
    $unsub_url     = $unsub_id ? get_permalink($unsub_id) : '';
    $changelog     = dfem_get_changelog();
    $license_key   = dfem_get_license_key();
    $is_premium    = dfem_is_premium();
    $valid_tabs    = ['general','branding','license','export','unsubscribe','changelog'];
    if (!in_array($active_tab, $valid_tabs)) $active_tab = 'general';

    dfem_header('Settings'); echo $msg; ?>

    <!-- TAB NAVIGATION -->
    <div style="margin-bottom:0;">
        <div class="dfem-tabs">
            <a href="?page=dfem-settings&tab=general"     class="dfem-tab <?php echo $active_tab==='general'     ?'active':''; ?>">⚙️ General</a>
            <a href="?page=dfem-settings&tab=branding"    class="dfem-tab <?php echo $active_tab==='branding'    ?'active':''; ?>">🎨 Branding</a>
            <a href="?page=dfem-settings&tab=license"     class="dfem-tab <?php echo $active_tab==='license'     ?'active':''; ?>">⭐ License<?php if($is_premium): ?> <span style="background:#d4f7ed;color:#005c34;padding:2px 8px;border-radius:20px;font-size:.75em;font-weight:700;margin-left:4px;">Active</span><?php endif; ?></a>
            <a href="?page=dfem-settings&tab=export"      class="dfem-tab <?php echo $active_tab==='export'      ?'active':''; ?>">📥 Export<?php if(!$is_premium): ?> 🔒<?php endif; ?></a>
            <a href="?page=dfem-settings&tab=unsubscribe" class="dfem-tab <?php echo $active_tab==='unsubscribe' ?'active':''; ?>">🔗 Unsubscribe</a>
            <a href="?page=dfem-settings&tab=changelog"   class="dfem-tab <?php echo $active_tab==='changelog'   ?'active':''; ?>">📋 Changelog</a>
        </div>
    </div>

    <!-- GENERAL TAB — own form, no nesting possible -->
    <?php if($active_tab === 'general'): ?>
    <form method="post">
        <?php wp_nonce_field('dfem_settings'); ?>
        <input type="hidden" name="current_tab" value="general">
        <div class="dfem-card" style="border-radius:0 12px 12px 12px;">
            <h2>📧 Sender Settings</h2>
            <div class="dfem-grid2">
                <div class="dfem-form-row"><label>From Name</label><input type="text" name="from_name" value="<?php echo esc_attr($settings['from_name'] ?? get_bloginfo('name')); ?>"></div>
                <div class="dfem-form-row"><label>From Email</label><input type="text" name="from_email" value="<?php echo esc_attr($settings['from_email'] ?? get_bloginfo('admin_email')); ?>"></div>
            </div>
        </div>
        <div class="dfem-card">
            <h2>🌐 Social / Footer Links <small style="font-weight:400;color:var(--df-muted);font-size:.82em">Shown as hyperlinks in every email footer.</small></h2>
            <div id="dfem-socials-wrap">
                <?php foreach($social_links as $sl): ?>
                <div class="social-row">
                    <input type="text" name="social_label[]" value="<?php echo esc_attr($sl['label']); ?>" placeholder="Label (e.g. Facebook)" style="max-width:200px;">
                    <input type="url"  name="social_url[]"   value="<?php echo esc_attr($sl['url']); ?>"   placeholder="https://...">
                    <button type="button" class="dfem-btn dfem-btn-danger dfem-btn-sm" onclick="this.closest('.social-row').remove()">✕ Remove</button>
                </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="dfem-btn dfem-btn-secondary" style="margin-top:10px" onclick="dfemAddSocial()">+ Add Link</button>
        </div>
        <div style="margin-bottom:30px"><button type="submit" name="dfem_save" value="1" class="dfem-btn dfem-btn-primary">💾 Save Settings</button></div>
    </form>

    <!-- BRANDING TAB -->
    <?php elseif($active_tab === 'branding'): ?>
    <form method="post">
        <?php wp_nonce_field('dfem_settings'); ?>
        <input type="hidden" name="current_tab" value="branding">
        <div class="dfem-card" style="border-radius:0 12px 12px 12px;">
            <h2>🎨 Email Template Branding</h2>
            <div class="dfem-form-row">
                <label>Logo Image</label>
                <div class="dfem-logo-wrap">
                    <?php if($logo_url): ?><img id="dfem-logo-preview" src="<?php echo esc_url($logo_url); ?>" class="dfem-logo-preview" alt="Logo">
                    <?php else: ?><div id="dfem-logo-placeholder" class="dfem-logo-placeholder">No logo<br>selected</div><img id="dfem-logo-preview" src="" class="dfem-logo-preview" style="display:none" alt="Logo"><?php endif; ?>
                    <div style="display:flex;flex-direction:column;gap:8px;">
                        <button type="button" class="dfem-btn dfem-btn-primary" id="dfem-upload-logo">📁 Choose from Media Library</button>
                        <?php if($logo_url): ?><button type="button" class="dfem-btn dfem-btn-danger dfem-btn-sm" id="dfem-remove-logo">✕ Remove Logo</button><?php endif; ?>
                        <small style="color:var(--df-muted)">Appears in the email header.</small>
                    </div>
                </div>
                <input type="hidden" name="logo_media_id" id="dfem-logo-media-id" value="<?php echo $logo_media_id; ?>">
            </div>
            <div class="dfem-grid2"><div class="dfem-form-row"><label>Primary Brand Colour</label><input type="color" name="primary_color" value="<?php echo esc_attr($settings['primary_color'] ?? '#0066cc'); ?>"></div></div>
            <div class="dfem-form-row"><label>Email Footer Text</label><textarea name="footer_text" rows="2"><?php echo esc_textarea($settings['footer_text'] ?? ''); ?></textarea></div>
        </div>
        <div style="margin-bottom:30px"><button type="submit" name="dfem_save" value="1" class="dfem-btn dfem-btn-primary">💾 Save Branding</button></div>
    </form>

    <!-- LICENSE TAB — its own separate form, never nested -->
    <?php elseif($active_tab === 'license'): ?>
    <div class="dfem-card" style="border-radius:0 12px 12px 12px;">
        <h2>⭐ DadsFam Premium License</h2>
        <?php if($is_premium): ?>
        <div style="padding:14px 18px;border-radius:10px;margin-bottom:18px;font-weight:600;background:#d4f7ed;color:#005c34;border:2px solid #00A878;">⭐ Premium License Active — your site has full premium access.</div>
        <?php else: ?>
        <div style="padding:14px 18px;border-radius:10px;margin-bottom:18px;font-weight:600;background:#f0f4f8;color:var(--df-muted);border:2px solid var(--df-border);">🔒 No active premium license. Generate a key in your License Manager, then enter it below.</div>
        <?php endif; ?>
        <p style="color:var(--df-muted);margin:0 0 20px;font-size:.93em">Generate a key in <strong>DF Licenses → Add New Key</strong>, paste it below, then click <strong>Activate</strong>. The key verifies in the background — refresh the page after ~30 seconds to see your Premium status update.</p>
        <form method="post">
            <?php wp_nonce_field('dfem_license'); ?>
            <div class="dfem-form-row" style="max-width:520px;">
                <label>License Key</label>
                <div style="display:flex;gap:10px;">
                    <input type="text" name="license_key" value="<?php echo esc_attr($license_key); ?>" placeholder="DFEM-XXXX-XXXX-XXXX-XXXX" style="font-family:monospace;letter-spacing:1px;">
                    <button type="submit" name="dfem_save_license" value="1" style="display:inline-flex;align-items:center;gap:5px;padding:9px 18px;border-radius:8px;font-weight:700;font-size:.88em;background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;border:none;cursor:pointer;white-space:nowrap;">⭐ Save Key</button>
                </div>
                <?php if($license_key && !$is_premium): ?><small style="color:var(--df-muted);margin-top:6px;display:block;">🕐 Key saved — background verification pending. Refresh in ~30 seconds. Or use "Force Check" below.</small><?php endif; ?>
            </div>
        </form>
        <?php if($license_key): ?>
        <form method="post" style="margin-top:10px">
            <?php wp_nonce_field('dfem_license'); ?>
            <input type="hidden" name="license_key" value="<?php echo esc_attr($license_key); ?>">
            <button type="submit" name="dfem_force_verify" value="1" class="dfem-btn dfem-btn-secondary dfem-btn-sm">🔄 Force Check Now</button>
            <small style="color:var(--df-muted);margin-left:8px">Runs verification immediately and refreshes your status.</small>
        </form>
        <?php endif; ?>
        <hr style="border:none;border-top:2px solid var(--df-border);margin:24px 0">
        <h3 style="margin:0 0 14px;color:var(--df-text)">🚀 Premium Features</h3>
        <div class="dfem-grid2">
            <div style="padding:14px;background:#f8faff;border-radius:10px;border-left:3px solid var(--df-blue)"><strong>📅 Campaign Scheduling</strong><p style="color:var(--df-muted);font-size:.88em;margin:4px 0 0">Schedule campaigns to auto-send at a future date/time.</p></div>
            <div style="padding:14px;background:#f8faff;border-radius:10px;border-left:3px solid var(--df-green)"><strong>📊 Open & Click Tracking</strong><p style="color:var(--df-muted);font-size:.88em;margin:4px 0 0">See who opened your emails and which links they clicked.</p></div>
            <div style="padding:14px;background:#f8faff;border-radius:10px;border-left:3px solid #7b2cbf"><strong>📥 CSV/Excel Export</strong><p style="color:var(--df-muted);font-size:.88em;margin:4px 0 0">Export your full subscriber list to a formatted Excel file.</p></div>
            <div style="padding:14px;background:#f8faff;border-radius:10px;border-left:3px solid #f59e0b"><strong>🤖 More Coming Soon</strong><p style="color:var(--df-muted);font-size:.88em;margin:4px 0 0">Automations, sequences, advanced analytics — in development.</p></div>
        </div>
    </div>

    <!-- EXPORT TAB (PREMIUM) -->
    <?php elseif($active_tab === 'export'): ?>
    <div class="dfem-card" style="border-radius:0 12px 12px 12px;">
        <h2>📥 Export Subscribers</h2>
        <?php if(!$is_premium): ?>
        <div style="text-align:center;padding:30px">
            <div style="font-size:2.5em;margin-bottom:12px">🔒</div>
            <h3 style="color:var(--df-text);margin:0 0 8px">CSV/Excel Export — Premium Feature</h3>
            <p style="color:var(--df-muted);margin:0 0 18px">Upgrade to export your full subscriber list as a formatted Excel-compatible file.</p>
            <a href="?page=dfem-settings&tab=license" class="dfem-btn dfem-btn-primary">⭐ Activate Premium License →</a>
        </div>
        <?php else:
        $export_groups = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}dfem_groups ORDER BY name");
        $total_subs    = (int)$wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}dfem_subscribers"); ?>
        <p style="color:var(--df-muted);margin:0 0 18px;font-size:.92em">Export your subscriber list to a CSV file formatted for Excel. Columns: First Name, Last Name, Email, Business Name, Group, Status, Date Added.</p>
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <?php wp_nonce_field('dfem_export'); ?>
            <input type="hidden" name="action" value="dfem_export_csv">
            <div class="dfem-form-row" style="max-width:380px">
                <label>Filter by Group <em style="font-weight:400;color:var(--df-muted)">(optional — leave blank for all)</em></label>
                <select name="export_group_id" style="width:100%;padding:9px 13px;border:2px solid var(--df-border);border-radius:8px;font-size:.93em;">
                    <option value="0">— All Subscribers (<?php echo $total_subs; ?>) —</option>
                    <?php foreach($export_groups as $g):
                        $cnt = (int)$wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->prefix}dfem_subscribers WHERE group_id=%d",$g->id)); ?>
                    <option value="<?php echo $g->id; ?>">📍 <?php echo esc_html($g->name); ?> (<?php echo $cnt; ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div style="background:#f8faff;padding:14px;border-radius:8px;border:1px solid var(--df-border);margin-bottom:18px;font-size:.88em;color:var(--df-muted)">
                📋 <strong style="color:var(--df-text)">Excel-compatible CSV format:</strong> First Name · Last Name · Email · Business Name · Group · Status · Date Added<br>
                <span style="font-size:.85em">UTF-8 BOM encoded so Excel opens it correctly with proper character support.</span>
            </div>
            <button type="submit" name="dfem_export_csv" value="1" class="dfem-btn dfem-btn-success">📥 Download Excel/CSV Export</button>
        </form>
        <?php endif; ?>
    </div>

    <!-- UNSUBSCRIBE TAB -->
    <?php elseif($active_tab === 'unsubscribe'): ?>
    <div class="dfem-card" style="border-radius:0 12px 12px 12px;">
        <h2>🔗 Unsubscribe Page</h2>
        <?php if($unsub_url): ?>
        <div class="dfem-alert dfem-alert-info">✅ Unsubscribe page live at: <a href="<?php echo esc_url($unsub_url); ?>" target="_blank"><?php echo esc_url($unsub_url); ?></a></div>
        <?php else: ?>
        <div class="dfem-alert dfem-alert-error">⚠️ Page missing. Deactivate and reactivate the plugin to recreate it.</div>
        <?php endif; ?>
        <p style="color:var(--df-muted)">The unsubscribe link is automatically added to every campaign email footer. Shortcode: <code>[dfem_unsubscribe]</code></p>
    </div>

    <!-- CHANGELOG TAB -->
    <?php elseif($active_tab === 'changelog'): ?>
    <div class="dfem-card" style="border-radius:0 12px 12px 12px;">
        <h2>📋 Plugin Changelog</h2>
        <p style="color:var(--df-muted);margin:-10px 0 20px;font-size:.92em">Full update history for DadsFam Email Marketing. Built by <a href="https://www.dadsfam.co.za" target="_blank" style="color:var(--df-blue)">DadsFam</a>.</p>
        <?php foreach($changelog as $entry):
            $label_map=['new'=>'🆕 New Features','major'=>'🚀 Major Release','fix'=>'🛠 Bug Fixes','feature'=>'✅ New Features','initial'=>'🎉 Initial Release'];
            $current=$entry['version']===DFEM_VERSION; ?>
        <div class="dfem-cl-entry <?php echo esc_attr($entry['label']); ?>">
            <div class="dfem-cl-version">
                <strong>v<?php echo esc_html($entry['version']); ?></strong>
                <?php if($current): ?><span class="dfem-badge-green">✅ Current</span><?php endif; ?>
                <span class="dfem-badge-gray"><?php echo esc_html($label_map[$entry['label']]??ucfirst($entry['label'])); ?></span>
                <span class="date">Released <?php echo esc_html(date('d M Y',strtotime($entry['date']))); ?></span>
            </div>
            <ul class="dfem-cl-changes"><?php foreach($entry['changes'] as $chg): ?><li><?php echo esc_html($chg); ?></li><?php endforeach; ?></ul>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
    <?php dfem_footer(); ?>
<?php
}

/* =========================================================
   EMAIL BUILDER
   ========================================================= */
function dfem_build_email( $subject, $body, $unsub_url = '', $campaign_id = 0, $sub_token = '' ) {
    $settings      = get_option('dfem_settings',[]);
    $site_name     = get_bloginfo('name');
    $site_url      = home_url();
    $logo_media_id = (int)($settings['logo_media_id']??0);
    $logo_url      = $logo_media_id ? wp_get_attachment_url($logo_media_id) : '';
    $color         = $settings['primary_color'] ?? '#0066cc';
    $dark          = dfem_darken($color,20);
    $footer_txt    = $settings['footer_text'] ?? '© '.date('Y').' '.$site_name.'. All rights reserved.';
    $links         = $settings['social_links'] ?? [];

    if(!$unsub_url){
        $pid = get_option('dfem_unsub_page_id');
        $unsub_url = $pid ? add_query_arg(['email'=>'{{email}}','token'=>'{{token}}'],get_permalink($pid)) : '#';
    }

    $link_html='';
    if($links){
        $link_html='<p style="margin:12px 0 4px">';
        foreach($links as $i=>$l){
            if($i>0) $link_html.=' &nbsp;·&nbsp; ';
            $link_html.='<a href="'.esc_url($l['url']).'" target="_blank" style="color:#ffffff;text-decoration:none;font-weight:600;opacity:.9;">'.esc_html($l['label']).'</a>';
        }
        $link_html.='</p>';
    }

    ob_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?php echo esc_html($subject); ?></title>
<style>
body{margin:0;padding:0;background:#f0f4f8;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;}
.ew{max-width:620px;margin:30px auto;background:#fff;border-radius:14px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,.1);}
.eh{background:linear-gradient(135deg,<?php echo esc_attr($color); ?>,<?php echo esc_attr($dark); ?>);padding:34px 40px;text-align:center;}
.eh img{max-height:70px;max-width:220px;object-fit:contain;}
.eh .sn{color:#fff;font-size:26px;font-weight:800;text-decoration:none;letter-spacing:-.5px;}
.eb{padding:38px 40px;color:#1a2332;line-height:1.75;font-size:16px;}
.eb h1,.eb h2,.eb h3{color:#0d1a2b;}
.eb a{color:<?php echo esc_attr($color); ?>;}
.eb img{max-width:100%;border-radius:8px;}
.ef{background:linear-gradient(135deg,<?php echo esc_attr($color); ?>,<?php echo esc_attr($dark); ?>);padding:28px 40px;text-align:center;}
.ef p{color:rgba(255,255,255,.8);font-size:13px;margin:0 0 5px;}
.unsub{margin-top:16px;}
.unsub a{color:#ffffff;font-size:12px;text-decoration:underline;opacity:.85;}
@media(max-width:640px){.eb,.eh,.ef{padding:22px 18px!important;}}
</style>
</head>
<body>
<div class="ew">
  <div class="eh">
    <div style="display:flex;align-items:center;justify-content:center;gap:18px;flex-wrap:wrap;">
      <?php if($logo_url): ?>
        <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr($site_name); ?>" style="max-height:50px;max-width:120px;object-fit:contain;flex-shrink:0;">
      <?php endif; ?>
      <a href="<?php echo esc_url($site_url); ?>" class="sn" style="color:#fff;text-decoration:none;margin:0;flex-shrink:0"><?php echo esc_html($site_name); ?></a>
    </div>
  </div>
  <div class="eb">
    <?php echo wp_kses_post(wpautop($body)); ?>
    <hr style="border:none;border-top:2px solid #e8edf2;margin:28px 0">
    <p style="font-size:14px;color:#6b7a8d">Thank you for being part of the <?php echo esc_html($site_name); ?> community!</p>
  </div>
  <div class="ef">
    <p><?php echo esc_html($footer_txt); ?></p>
    <p><a href="<?php echo esc_url($site_url); ?>" style="color:rgba(255,255,255,.9);text-decoration:none;font-weight:700"><?php echo esc_html($site_name); ?></a></p>
    <?php echo $link_html; ?>
    <?php if($campaign_id && $sub_token): ?>
    <img src="<?php echo esc_url(add_query_arg(['dfem_track'=>'open','c'=>$campaign_id,'s'=>$sub_token], home_url('/'))); ?>" width="1" height="1" style="display:block;width:1px;height:1px;border:0" alt="">
    <?php endif; ?>
    <div class="unsub"><a href="<?php echo esc_url($unsub_url); ?>">Unsubscribe from these emails</a></div>
  </div>
</div>
</body>
</html>
<?php return ob_get_clean();
}

/* =========================================================
   HELPERS
   ========================================================= */
function dfem_darken($hex,$pct){
    $hex=ltrim($hex,'#');
    if(strlen($hex)===3) $hex=$hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
    $r=max(0,hexdec(substr($hex,0,2))-round(255*$pct/100));
    $g=max(0,hexdec(substr($hex,2,2))-round(255*$pct/100));
    $b=max(0,hexdec(substr($hex,4,2))-round(255*$pct/100));
    return sprintf('#%02x%02x%02x',$r,$g,$b);
}
function dfem_unsub_url($email,$token){
    $pid=get_option('dfem_unsub_page_id');
    $base=$pid?get_permalink($pid):home_url('/email-unsubscribe/');
    return add_query_arg(['email'=>rawurlencode($email),'token'=>$token],$base);
}

/* =========================================================
   UNSUBSCRIBE SHORTCODE
   ========================================================= */
add_shortcode('dfem_unsubscribe','dfem_unsub_shortcode');
function dfem_unsub_shortcode(){
    global $wpdb;
    $table    = $wpdb->prefix.'dfem_subscribers';
    $settings = get_option('dfem_settings',[]);
    $color    = $settings['primary_color']??'#0066cc';
    $dark     = dfem_darken($color,20);
    $email    = isset($_GET['email'])?sanitize_email(rawurldecode($_GET['email'])):'';
    $token    = isset($_GET['token'])?sanitize_text_field($_GET['token']):'';

    ob_start(); ?>
    <style>
    .dfem-unsub-btn{display:inline-block;background:#e63946;color:#fff;border:none;padding:13px 30px;border-radius:8px;font-size:1em;font-weight:700;cursor:pointer;margin-right:8px;text-decoration:none;transition:all .25s ease;}
    .dfem-unsub-btn:hover{background:<?php echo esc_attr($color); ?>!important;transform:translateY(-2px);box-shadow:0 4px 12px rgba(0,102,204,.3);}
    .dfem-cancel-btn{background:#f0f4f8;color:#1a2332;padding:13px 22px;border-radius:8px;text-decoration:none;font-weight:600;display:inline-block;transition:all .2s;}
    .dfem-cancel-btn:hover{background:#e2e8f0;}
    </style>
    <div style="max-width:480px;margin:50px auto;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;padding:16px;">
      <div style="background:linear-gradient(135deg,<?php echo esc_attr($color); ?>,<?php echo esc_attr($dark); ?>);padding:38px;border-radius:14px 14px 0 0;text-align:center;">
        <div style="font-size:46px;margin-bottom:8px">📧</div>
        <h2 style="color:#fff;margin:0;font-size:1.5em">Email Preferences</h2>
        <p style="color:rgba(255,255,255,.85);margin:6px 0 0"><?php echo esc_html(get_bloginfo('name')); ?></p>
      </div>
      <div style="background:#fff;padding:38px;border-radius:0 0 14px 14px;box-shadow:0 8px 28px rgba(0,0,0,.1);text-align:center;">
        <?php
        if(isset($_POST['dfem_unsub'])&&check_admin_referer('dfem_unsub_'.$email)){
            $sub=$wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE email=%s AND token=%s",$email,$token));
            if($sub){
                $wpdb->update($table,['status'=>'unsubscribed'],['id'=>$sub->id],['%s'],['%d']);
                echo '<div style="font-size:46px;margin-bottom:14px">✅</div>';
                echo '<h3 style="color:#005c34">Successfully Unsubscribed</h3>';
                echo '<p style="color:#666">You have been removed from our mailing list.</p>';
                echo '<p style="margin-top:22px"><a href="'.esc_url(home_url()).'" style="background:'.esc_attr($color).';color:#fff;padding:12px 26px;border-radius:8px;text-decoration:none;font-weight:700">← Back to '.esc_html(get_bloginfo('name')).'</a></p>';
            } else {
                echo '<div style="font-size:46px;margin-bottom:14px">❌</div><h3 style="color:#9b1c28">Invalid Link</h3><p style="color:#666">This link is invalid or already used.</p>';
            }
        } elseif($email&&$token){
            $sub=$wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE email=%s AND token=%s",$email,$token));
            if($sub&&$sub->status==='unsubscribed'){
                echo '<div style="font-size:46px;margin-bottom:14px">ℹ️</div><p style="color:#666"><strong>'.esc_html($email).'</strong> is already unsubscribed.</p>';
            } elseif($sub){ ?>
                <div style="font-size:46px;margin-bottom:12px">😔</div>
                <h3 style="color:#1a2332;margin:0 0 8px">Unsubscribe?</h3>
                <p style="color:#666">Remove <strong><?php echo esc_html($email); ?></strong> from all marketing emails?</p>
                <form method="post" style="margin-top:22px">
                    <?php wp_nonce_field('dfem_unsub_'.$email); ?>
                    <input type="hidden" name="dfem_unsub" value="1">
                    <button type="submit" class="dfem-unsub-btn">Yes, Unsubscribe Me</button>
                    <a href="<?php echo esc_url(home_url()); ?>" class="dfem-cancel-btn">Cancel</a>
                </form>
            <?php } else {
                echo '<div style="font-size:46px;margin-bottom:14px">❌</div><h3 style="color:#9b1c28">Invalid Link</h3><p style="color:#666">Use the link from your email.</p>';
            }
        } else {
            echo '<div style="font-size:46px;margin-bottom:14px">⚠️</div><p style="color:#666">Please use the unsubscribe link from your email.</p>';
        } ?>
      </div>
    </div>
    <?php return ob_get_clean();
}
