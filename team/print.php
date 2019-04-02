<?php
/**
 * Upload form for documents to be sent to the printer.
 *
 * Part of the DOMjudge Programming Contest Jury System and licenced
 * under the GNU GPL. See README and COPYING for details.
 */

require('init.php');

$title = '打印页面';
require(LIBWWWDIR . '/header.php');

echo "<h1>打印代码</h1>\n\n";

if (! have_printing()) {
    error("Printing disabled.");
}

// Seems reasonable to require that there's a contest running
// before allowing to submit printouts.
$fdata = calcFreezeData($cdata);
if (!checkrole('jury') && !$fdata['started']) {
    echo "<div class=\"alert alert-secondary\">比赛尚未开始，无法打印！</div>\n";
    require(LIBWWWDIR . '/footer.php');
    exit;
}

if (isset($_POST['printlangid'])) {
    handle_print_upload();
} else {
    put_print_form();
}

require(LIBWWWDIR . '/footer.php');
