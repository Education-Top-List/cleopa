<?php
/**
 * Cấu hình cơ bản cho WordPress
 *
 * Trong quá trình cài đặt, file "wp-config.php" sẽ được tạo dựa trên nội dung 
 * mẫu của file này. Bạn không bắt buộc phải sử dụng giao diện web để cài đặt, 
 * chỉ cần lưu file này lại với tên "wp-config.php" và điền các thông tin cần thiết.
 *
 * File này chứa các thiết lập sau:
 *
 * * Thiết lập MySQL
 * * Các khóa bí mật
 * * Tiền tố cho các bảng database
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */
// ** Thiết lập MySQL - Bạn có thể lấy các thông tin này từ host/server ** //
/** Tên database MySQL */
define('DB_NAME', 'cleopa');
/** Username của database */
define('DB_USER', 'root');
/** Mật khẩu của database */
//define('DB_PASSWORD', '');
define('DB_PASSWORD', '');
/** Hostname của database */
define('DB_HOST', '127.0.0.1');
/** Database charset sử dụng để tạo bảng database. */
define('DB_CHARSET', 'utf8mb4');
/** Kiểu database collate. Đừng thay đổi nếu không hiểu rõ. */
define('DB_COLLATE', '');
/**#@+
 * Khóa xác thực và salt.
 *
 * Thay đổi các giá trị dưới đây thành các khóa không trùng nhau!
 * Bạn có thể tạo ra các khóa này bằng công cụ
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * Bạn có thể thay đổi chúng bất cứ lúc nào để vô hiệu hóa tất cả
 * các cookie hiện có. Điều này sẽ buộc tất cả người dùng phải đăng nhập lại.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'F)%wadCs&otNPb4+Z(wuYd8oW/5tQY2&mi0FB!C+-2m%IR-yq}A+F|IRr3tn[/h4');
define('SECURE_AUTH_KEY',  ':`V4_*Pc;It5}kIuK;uWqTxNT>`lb51/ 3*D(2#6w(#8F7u6r+%a`yHK[fjB+4f}');
define('LOGGED_IN_KEY',    'r`McfVc7VU~d:CjeXl/!Ut|/92&xS78>r+?~JpV`n/Ui77FOG5Odp@[Sv!i36<zS');
define('NONCE_KEY',        'Md7v+l4S@+M:ko[.{fL~F{|CDX6^?0>nX-FXk-8L_bJN13^Ru!iUDQ/;@W-[|y4n');
define('AUTH_SALT',        'NUu7rl,fk#5w9Yd`tUH;3_JS#k$1y%&`3y#q;B5Q=9a[th)0.uLf&{z|Pz=WgN6h');
define('SECURE_AUTH_SALT', '-1=[:Rb~1Q=7xr9JpKd<2 w@.M+`w}z#X0CFs2tY~x6B&$$X9ZiT)b]L$dtR|33h');
define('LOGGED_IN_SALT',   ')yL1]FKCEKw?@LU+]nR+04nknvB=7&$n8B+~m=vuEIhg36CPzEi+|js|t#eDsgY=');
define('NONCE_SALT',       'enxVzgfrcsD))2)O[|KF@m*X)AZ7&QWXt2Sf)$%X*v06JVmOY3!t9kSC:Rf4>_@l');
/**#@-*/
/**
 * Tiền tố cho bảng database.
 *
 * Đặt tiền tố cho bảng giúp bạn có thể cài nhiều site WordPress vào cùng một database.
 * Chỉ sử dụng số, ký tự và dấu gạch dưới!
 */
$table_prefix  = 'wp_';
/**
 * Dành cho developer: Chế độ debug.
 *
 * Thay đổi hằng số này thành true sẽ làm hiện lên các thông báo trong quá trình phát triển.
 * Chúng tôi khuyến cáo các developer sử dụng WP_DEBUG trong quá trình phát triển plugin và theme.
 *
 * Để có thông tin về các hằng số khác có thể sử dụng khi debug, hãy xem tại Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);
//define('FS_METHOD', 'direct');
define( 'WP_AUTO_UPDATE_CORE', false );
/* Đó là tất cả thiết lập, ngưng sửa từ phần này trở xuống. Chúc bạn viết blog vui vẻ. */
/** Đường dẫn tuyệt đối đến thư mục cài đặt WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
/** Thiết lập biến và include file. */
require_once(ABSPATH . 'wp-settings.php');
