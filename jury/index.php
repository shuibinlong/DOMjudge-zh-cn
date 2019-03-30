<?php
/**
 * Part of the DOMjudge Programming Contest Jury System and licenced
 * under the GNU GPL. See README and COPYING for details.
 */

global $REQUIRED_ROLES;
$REQUIRED_ROLES = array('jury', 'balloon');
require('init.php');

$title = 'Jury interface';
require(LIBWWWDIR . '/header.php');


echo "<div class=\"row\">" . 
        "<div class=\"col-12\">" . 
        "<h1>DOMjudge Jury interface</h1>\n\n";

echo "<a href=\"http://www.bit.edu.cn/\">" .
     "<img src=\"../images/BITlogo.png\" id=\"djlogo\" " .
     "alt=\"DOMjudge logo\" title=\"The BIT logo: free as in beer!\" /></a>\n\n";
?>


<div class="row equal mt-3">
<div class="col-lg-4 col-md-5 col-sm-6 mt-3">
<?php if (checkrole('jury')) { ?>
    <div class="card mb-3">
    <div class="card-header">Before contest:</div>
    <div class="card-body">
        <ul>
        <li><a href="contests.php">Contests</a></li>
        <li><a href="executables.php">Executables</a></li>
        <li><a href="judgehosts.php">Judgehosts</a></li>
        <li><a href="judgehost_restrictions.php">Judgehost Restrictions</a></li>
        <li><a href="languages.php">Languages</a></li>
        <li><a href="problems.php">Problems</a></li>
        <li><a href="users.php">Users</a></li>
        <li><a href="teams.php">Teams</a></li>
        <li><a href="team_categories.php">Team Categories</a></li>
        <li><a href="team_affiliations.php">Team Affiliations</a></li>
        </ul>
    </div></div>
<?php } ?>

    <div class="card mb-3">
    <div class="card-header">During contest:</div>
    <div class="card-body">
        <ul>
        <li><a href="balloons.php">Balloon Status</a></li>
<?php if (checkrole('jury')) { ?>
        <li><a href="clarifications.php">Clarifications</a></li>
        <li><a href="internal_errors.php">Internal Errors</a></li>
        <li><a href="rejudgings.php">Rejudgings</a></li>
        <li><a href="scoreboard.php">Scoreboard</a></li>
        <li><a href="statistics.php">Statistics/Analysis</a></li>
        <li><a href="submissions.php">Submissions</a></li>
<?php } ?>
        </ul>
    </div></div>

</div>

<div class="col-lg-4 col-md-5 col-sm-6 mt-3">

<?php if (IS_ADMIN): ?>
    <div class="card mb-3">
    <div class="card-header">Administrator:</div>
    <div class="card-body">
        <ul>
        <li><a href="config.php">Configuration settings</a></li>
        <li><a href="checkconfig.php">Config checker</a></li>
        <li><a href="impexp.php">Import/Export</a></li>
        <li><a href="genpasswds.php">Manage team passwords</a></li>
        <li><a href="refresh_cache.php">Refresh scoreboard cache</a></li>
        <li><a href="check_judgings.php">Judging verifier</a></li>
        <li><a href="auditlog.php">Audit log</a></li>
        </ul>
    </div></div>
<?php endif; ?>

    <div class="card mb-3">
    <div class="card-header">Documentation:</div>
    <div class="card-body">
        <ul>
        <li><a href="doc/judge/judge-manual.html">Judge manual</a>
            (also <a href="doc/judge/judge-manual.pdf">PDF</a>)</li>
        <li><a href="doc/admin/admin-manual.html">Administrator manual</a>
            (also <a href="doc/admin/admin-manual.pdf">PDF</a>)</li>
        <li><a href="doc/team/team-manual.pdf">Team manual</a>
            (PDF only)</li>
        </ul>
    </div></div>



</div>


</div></div>

<p><br /><br /><br /><br /></p>

<?php
putDOMjudgeVersion();

require(LIBWWWDIR . '/footer.php');
