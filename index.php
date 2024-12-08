<?php
function isMobile() {
    return preg_match('/(android|iphone|ipad|mobile)/i', $_SERVER['HTTP_USER_AGENT']);
}

if (isMobile()) {
    header('Location: mobile_index.php');
    exit();
} else {
    header('Location: admin/admin_index.php');
    exit();
}
?>