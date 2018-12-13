<?php
/**
 * View/download problem texts and sample testcases
 *
 * Part of the DOMjudge Programming Contest Jury System and licenced
 * under the GNU GPL. See README and COPYING for details.
 */

require('init.php');

$title = '比赛题目页面';
require(LIBWWWDIR . '/header.php');
?>

<h1>比赛题目</h1>

<div class="container">

<?php putProblemTextList(); ?>

</div>

<?php
require(LIBWWWDIR . '/footer.php');
