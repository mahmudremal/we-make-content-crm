<?php

if(!function_exists('bridge_qode_child_theme_enqueue_scripts')) {

	Function bridge_qode_child_theme_enqueue_scripts() {
		wp_register_style('bridge-childstyle', get_stylesheet_directory_uri() . '/style.css');
		wp_enqueue_style('bridge-childstyle');
	}

	add_action('wp_enqueue_scripts', 'bridge_qode_child_theme_enqueue_scripts', 11);
}



add_action( 'login_footer', function() {
  ?>
  <style type="text/css">
    /** login page design CSS */
    body.login #login {margin-top: 30px;}
    body.login #login h1 a {
        max-width: 100%;
        display: block;
        position: relative;
        background-size: contain;
    }

    body.login.js.login-action-login.wp-core-ui.locale-en-us.login-designer-template-01, html {
      height: 100% !important;
      max-width: 100%;
      max-height: 100%;
    }
    @media(max-width: 400px) {
      body.login #jetpack-sso-wrap a.jetpack-sso.button.button-primary {
          display: inline-table;
          white-space: pre-wrap;
          line-height: 22px;
          padding: 10px 5px;
      }
    }
    @media only screen and (max-width: 650px) {
      body.login.login-designer-template-01 #login {
        margin: auto !important;
      }
    }
  </style>
  <?php
}, 10, 0 );