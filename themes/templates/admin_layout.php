<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) PHP-Fusion Inc
| https://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: admin_layout.php
| Author: PHP-Fusion Development Team
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
$locale = fusion_get_locale('', LOCALE.LOCALESET."admin/main.php");
$settings = fusion_get_settings();
\PHPFusion\Admins::getInstance()->setAdmin();
header("Content-Type: text/html; charset=".$locale['charset']."");

echo "<!DOCTYPE html>";
echo "<html lang='".$locale['xml_lang']."' dir='".$locale['text-direction']."'>";
echo "<head>";
echo "<title>".$settings['sitename']."</title>";
echo "<meta charset='".$locale['charset']."'/>";
echo "<meta name='robots' content='none'/>";
echo "<meta name='googlebot' content='noarchive'/>";

if ($settings['bootstrap'] || defined('BOOTSTRAP')) {
    echo "<meta http-equiv='X-UA-Compatible' content='IE=edge'/>\n";
    echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'/>\n";
    echo "<link href='".INCLUDES."bootstrap/bootstrap.min.css' rel='stylesheet' media='screen'/>";

    if ($locale['text-direction'] == 'rtl') {
        echo "<link href='".INCLUDES."bootstrap/bootstrap-rtl.min.css' rel='stylesheet' media='screen'/>";
    }
}

if ($settings['entypo'] || defined('ENTYPO')) {
    echo "<link rel='stylesheet' href='".INCLUDES."fonts/entypo/entypo.min.css' type='text/css'/>\n";
}

// Font Awesome 4
if (defined('FONTAWESOME-V4')) {
    if ($settings['fontawesome'] || defined('FONTAWESOME')) {
        echo "<link rel='stylesheet' href='".INCLUDES."fonts/font-awesome/css/font-awesome.min.css' type='text/css'/>\n";
    }
}

// Font Awesome 5
if (!defined('FONTAWESOME-V4')) {
    if ($settings['fontawesome'] || defined('FONTAWESOME')) {
        echo "<link rel='stylesheet' href='".INCLUDES."fonts/font-awesome-5/css/all.min.css' type='text/css'/>\n";
        echo "<link rel='stylesheet' href='".INCLUDES."fonts/font-awesome-5/css/v4-shims.min.css' type='text/css'/>\n";
    }
}

// Default CSS styling which applies to all themes but can be overriden
if (!defined('NO_DEFAULT_CSS')) {
    echo "<link href='".THEMES."templates/default.min.css' rel='stylesheet' type='text/css' media='screen'/>\n";
}

// Admin Panel Theme CSS
$admin_theme_css = file_exists(THEMES.'admin_themes/'.$settings['admin_theme'].'/acp_styles.min.css') ? THEMES.'admin_themes/'.$settings['admin_theme'].'/acp_styles.min.css' : THEMES.'admin_themes/'.$settings['admin_theme'].'/acp_styles.css';
echo "<link href='".$admin_theme_css."' rel='stylesheet' type='text/css' media='screen'/>\n";

echo render_favicons(defined('THEME_ICON') ? THEME_ICON : IMAGES.'favicons/');

if (function_exists("get_head_tags")) {
    echo get_head_tags();
}

echo "<script type='text/javascript' src='".INCLUDES."jquery/jquery.min.js'></script>\n";
echo "<script type='text/javascript' src='".INCLUDES."jscripts/jscript.js'></script>\n";
echo "</head>";

/**
 * Constant - THEME_BODY;
 * replace <body> tags with your own theme definition body tags. Some body tags require additional params
 * for the theme purposes.
 */

if (!defined("THEME_BODY")) {
    echo "<body>\n";
} else {
    echo THEME_BODY;
}

// Check if the user is logged in
if (!check_admin_pass('')) {
    if (empty(fusion_get_userdata("user_admin_password"))) {
        redirect(BASEDIR."edit_profile.php");
    } else {
        render_admin_login();
    }
} else {
    render_admin_panel();
}

// Load Bootstrap javascript
if ($settings['bootstrap'] || defined('BOOTSTRAP')) {
    echo "<script type='text/javascript' src='".INCLUDES."bootstrap/bootstrap.min.js'></script>\n";
}

echo "<script type='text/javascript' src='".INCLUDES."jquery/admin-scripts.js'></script>\n";
echo "<script type='text/javascript' src='".INCLUDES."jquery/holder/holder.min.js'></script>\n";

// Output lines added with add_to_footer()
echo $fusion_page_footer_tags;

// Output lines added with add_to_jquery()
if (!empty($fusion_jquery_tags)) {
    if ($settings['devmode'] == 0) {
        $minifier = new PHPFusion\Minify\JS($fusion_jquery_tags);
        $js = $minifier->minify();
    } else {
        $js = $fusion_jquery_tags;
    }

    echo "<script type='text/javascript'>$(function(){".$js."});</script>\n";
}

// Uncomment to guide your theme development
//echo "<script src='".INCLUDES."jscripts/html-inspector.js'></script>\n<script> HTMLInspector.inspect() </script>\n";
echo "</body>\n";
echo "</html>";
