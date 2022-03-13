<?php
/*
Plugin Name: Admin Manipulate Log
Plugin URI: https://github.com/3rd-cloud/WPPlugin_AdminUserDeleteWithContentsDisabled
Description: 管理画面操作のログを記録します。
Author: Yuji Mikumo
Author URI: https://github.com/3rd-cloud/
Version: 0.2
License: GPL2
*/
/*
Admin Manipulate Log is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Admin Manipulate Log is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Admin Manipulate Log. If not, see https://github.com/3rd-cloud/WPPlugin_AdminUserDeleteWithContentsDisabled/blob/main/LICENSE.
*/

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'WPAdmin_Manipulate_Log' ) ) {
    class WPAdmin_Manipulate_Log {
        private $textdomain = 'wpadmin-manipulate-log';

        public function __construct() {
            // load_textdomain( $this->textdomain, __DIR__ . '/languages/plugin-' . get_locale() . '.mo' );

            if ( is_admin() ) {
                date_default_timezone_set( get_option( 'timezone_string' ) );
                if (is_multisite()) {
                    add_action( 'wpmu_new_user', array( $this, 'action_wpmu_new_user' ), 10000 );
                } else {
                    add_action( 'user_register', array( $this, 'action_user_register' ), 10000 );
                }

                add_action( 'delete_user', array( $this, 'action_delete_user' ), 10000 );
                add_action( 'post_updated', array( $this, 'action_post_updated' ), 10000 );
                add_action( 'deleted_post', array( $this, 'action_deleted_post' ), 10000 );
                add_action( 'transition_post_status', array( $this, 'action_transition_post_status' ), 10000 );
                add_action( 'add_attachment', array( $this, 'action_add_attachment' ), 10000 );
                add_action( 'edit_attachment', array( $this, 'action_edit_attachment' ), 10000 );
                add_action( 'delete_attachment', array( $this, 'action_delete_attachment' ), 10000 );
            }
        }

        private function admin_log_write( $message ) {
            global $current_user;
            $admin_log_file = __DIR__ . '/logs/admin.log';

            $log_message = date( 'c' ) . " - Login User {$current_user->ID}: {$current_user->user_login} ({$current_user->display_name}) - {$message} - " . $_SERVER['REQUEST_URI'] . "\n";
            error_log( $log_message, 3, $admin_log_file );
        }

        public function action_user_register( $user_id ) {
            $this->admin_log_write( "AddUserID:{$user_id} ユーザー追加" );
        }

        public function action_wpmu_new_user( $user_id ) {
            $this->admin_log_write( "AddUserID:{$user_id} ユーザー追加" );
        }

        public function action_delete_user( $user_id ) {
            $this->admin_log_write( "DeleteUserID:{$user_id} ユーザー削除" );
        }

        public function action_post_updated( $post_id ) {
            $this->admin_log_write( "UpdatePostID:{$post_id} 投稿更新" );
        }

        public function action_deleted_post( $post_id ) {
            $this->admin_log_write( "DeletePostID:{$post_id} 投稿削除" );
        }

        public function action_transition_post_status( $post_id ) {
            $this->admin_log_write( "UpdatePostID:{$post_id} 投稿ステータス更新" );
        }

        public function action_add_attachment( $attachment_id ) {
            $this->admin_log_write( "AddAttachmentID:{$attachment_id} 添付ファイル追加" );
        }

        public function action_edit_attachment( $attachment_id ) {
            $this->admin_log_write( "EditAttachmentID:{$attachment_id} 添付ファイル更新" );
        }

        public function action_delete_attachment( $attachment_id ) {
            $this->admin_log_write( "DeleteAttachmentID:{$attachment_id} 添付ファイル削除" );
        }
    }
}

new WPAdmin_Manipulate_Log();
