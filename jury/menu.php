<?php
global $updates;
?>
<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
<a class="navbar-brand hidden-sm-down" href="./">BIT Online Judge</a>
<div class="collapse navbar-collapse" id="menuDefault">
<ul class="navbar-nav mr-auto">
<?php if (IS_ADMIN) {
        $ndown = count($updates['judgehosts']);
        if ($ndown > 0) {
            ?>
<li class="nav-item">
<a class="nav-link" href="judgehosts.php" accesskey="j" id="menu_judgehosts"><i class="fas fa-server fa-fw"></i> judgehosts (<?php echo $ndown ?> down)</a></li>
<?php
        } else {
            ?>
<a class="nav-link" href="judgehosts.php" accesskey="j" id="menu_judgehosts"><i class="fas fa-server fa-fw"></i> judgehosts</a>
<?php
        }
        $nerr = count($updates['internal_error']);
        if ($nerr > 0) {
            ?>
<a class="nav-link" href="internal_errors.php" accesskey="e" id="menu_internal_error"><span class="fas fa-bolt fa-fw"></span> internal error (<?php echo $nerr ?> new)</a>
<?php
        }
    } ?>
<?php if (checkrole('jury')) {
        ?>
<?php
    $nunread = count($updates['clarifications']);
        if ($nunread > 0) {
            ?>
<a class="nav-link" href="clarifications.php" accesskey="c" id="menu_clarifications"><span class="fas fa-comments"></span> clarifications <span class="badge badge-info" id="num-alerts-clarifications"><?php echo $nunread ?></span></a>
<?php
        } else {
            ?>
<a class="nav-link" href="clarifications.php" accesskey="c" id="menu_clarifications"><span class="fas fa-comments"></span> clarifications</a>
<?php
        } ?>
<a class="nav-link" href="submissions.php" accesskey="s"><span class="fas fa-file-code"></span> submissions</a>
<?php
    $nrejudgings = count($updates['rejudgings']);
        if ($nrejudgings > 0) {
            ?>
<a class="nav-link" href="rejudgings.php" accesskey="r" id="menu_rejudgings"><span class="fas fa-sync"></span> rejudgings <span class="badge badge-info" id="num-alerts-clarifications"><?php echo $nrejudgings ?></span></a>
<?php
        } else {
            ?>
<a class="nav-link" href="rejudgings.php" accesskey="r" id="menu_rejudgings"><span class="fas fa-sync"></span> rejudgings</a>
<?php
        } ?>
<?php
    } /* checkrole('jury') */ ?>
<?php if (checkrole('jury')) {
        ?>
<a class="nav-link" href="scoreboard.php" accesskey="b"><span class="fas fa-list-ol"></span> scoreboard</a>
<?php
    } ?>
<?php
if (checkrole('team')) {
        echo "<a class=\"nav-link\" target=\"_top\" href=\"../team/\" accesskey=\"t\"><span class=\"octicon octicon-arrow-right\"></span> team</a>\n";
    }
?>
</ul>

<?php

$notify_flag  =  isset($_COOKIE["domjudge_notify"])  && (bool)$_COOKIE["domjudge_notify"];
$refresh_flag = !isset($_COOKIE["domjudge_refresh"]) || (bool)$_COOKIE["domjudge_refresh"];

// if (isset($refresh)) {
//     $text = $refresh_flag ? 'Disable' : 'Enable';
//     echo '<input id="refresh-toggle" type="button" value="' . $text . ' refresh" />';
// }

// Default hide this from view, only show when javascript and
// notifications are available:
// echo '<div id="notify"' .
//     addForm('toggle_notify.php', 'get') .
//     addHidden('enable', ($notify_flag ? 0 : 1)) .
//     addSubmit(
//         ($notify_flag ? 'Dis' : 'En') . 'able notifications',
//         'toggle_notify',
//               'return toggleNotifications(' . ($notify_flag ? 'false' : 'true') . ')'
//     ) .
//     addEndForm() . "</div>";

echo '<ul class="navbar-nav ml-auto">';

if (IS_JURY && logged_in()) {
        // Show pretty name if possible
        $displayname = $username;
        if ($userdata['name']) {
            $displayname = $userdata['name'];
        }
        echo '<li class="nav-item dropdown">' . 
            '<a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown"><i class="fa fa-user"></i> '. $displayname .'</a>' .
            '<div class="dropdown-menu"><a class="dropdown-item disabled" style="color:silver;">Administrator</a>';
            

            echo '<a class="dropdown-item" href="#" id="notify-navitem"><i class="' . ($notify_flag ? 'fas fa-bell-slash fa-fw' : 'fas fa-bell fa-fw') . '"></i> ' . ($notify_flag ? 'Disable' : 'Enable') . ' Notifications</a>';
            
            if (isset($refresh)) {
                $text = $refresh_flag ? 'Disable' : 'Enable';
                echo '<a class="dropdown-item" href="#" id="refresh-navitem"><i class="fas fa-sync-alt fa-fw"></i> <span id="refresh-toggle">' . $text . ' Refresh</span> <span class="small text-muted">(30s)</span></a>';
            }
            echo '<a class="dropdown-item" href="../auth/logout.php"><i class="fas fa-sign-out-alt fa-fw"></i> Logout</a>' .
            '</div></li>';
            
        // echo "<div id=\"username\">logged in as " . $displayname
        //     . (have_logout() ? ' <a href="../auth/logout.php">' .
        //         '<span class="octicon octicon-sign-out"></span></a>' : "")
        //     . "</div>";
}    
    
putClock();

?>

<script type="text/javascript">

var notificationsEnabled = false;

function enableNotifications() {
    if (notificationsEnabled) {
        return;
    }
    toggleNotifications(true);
    notificationsEnabled = true;
    window.Cookies && Cookies.set('domjudge_notify', 1);
}

function disableNotifications() {
    if (!notificationsEnabled) {
        return;
    }
    toggleNotifications(false);
    notificationsEnabled = false;
    window.Cookies && Cookies.set('domjudge_notify', 0);
}


<?php if ($notify_flag): ?>
    enableNotifications();
<?php endif; ?>

$(function () {
    $('#notify-navitem').on('click', function () {
        if (notificationsEnabled) {
            this.innerHTML = '<i class="fas fa-bell-slash fa-fw"></i> Disable Notifications';
            disableNotifications();
        } else {
            this.innerHTML = '<i class="fas fa-bell fa-fw"></i> Enable Notifications';
            enableNotifications();
        }
    });
});
</script>


</div>
</nav>
