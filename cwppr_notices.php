<?php

/**
 * @Author: RAYHAN
 * @Date:   2020-11-19 16:00:51
 * @Last Modified by:   RAYHAN
 * @Last Modified time: 2022-06-13 23:01:02
 */
header('Access-Control-Allow-Origin: *');

$notices = array(
    array(
        'title' => '<a href="https://wordpress.org/support/plugin/export-wp-page-to-static-html/reviews/?rate=5#new-post">Support us with a 5-star ratings »</a>',
        'key' => 'support_us',
        'publishing_date' => '24 December 2020',
        'auto_hide' => '',
        'auto_hide_date' => '25 November 2070',
        'is_right_sidebar' => 1,
        'content' => '',
        'status' => 1,
        'version' => array( 'free' ),

        'styles' => ''
    ),

    array(
        'title' => 'More plugins you may like!',
        'key' => 'more_plugins',
        'publishing_date' => '19 November 2020',
        'auto_hide' => false,
        'auto_hide_date' => '25 November 2070',
        'is_right_sidebar' => true,
        'content' => '1. <a href="https://wordpress.org/plugins/different-menus-in-different-pages/?r=export-html">Different Menu in Different Pages</a></br>2. <a href="https://myrecorp.com/product/menu-import-and-export-pro/?r=export-html">Menu Import & Export Pro</a></br>3. <a href="https://myrecorp.com/product/mailchimp-for-divi-contact-form/?r=export-html">Divi Contact Form MailChimp Extension</a>',
        'status' => true,
        'version' => array('free', 'paid'),
        'styles' => '.right_notice_title{font-size: 17px;font-weight: bold;margin-top: 10px;}',

    ),

);

echo json_encode($notices);