<?php
/**
 * Show clarification thread and reply box.
 * When no id is given, show clarification request box.
 *
 * Part of the DOMjudge Programming Contest Jury System and licenced
 * under the GNU GPL. See README and COPYING for details.
 */

require('init.php');

$id = getRequestID();

if (isset($id)) {
    $req = $DB->q('MAYBETUPLE SELECT * FROM clarification
                   WHERE cid = %i AND clarid = %i', $cid, $id);
    if (! $req) {
        error("clarification $id not found");
    }
    if (! canViewClarification($teamid, $req)) {
        error("Permission denied");
    }
    $myrequest = ($req['sender'] == $teamid);

    $respid = empty($req['respid']) ? $id : $req['respid'];
}

// insert a request (if posted)
if (isset($_POST['submit']) && !empty($_POST['bodytext'])) {
    list($cid, $probid) = explode('-', $_POST['problem']);
    $category = null;
    $queue = null;
    if (!ctype_digit($probid)) {
        $category = $probid;
        $probid = null;
    } else {
        $queue = dbconfig_get('clar_default_problem_queue');
        if ($queue === "") {
            $queue = null;
        }
    }

    // Disallow problems that are not submittable or
    // before contest start.
    if (!problemVisible($probid)) {
        $probid = null;
    }

    $newid = $DB->q('RETURNID INSERT INTO clarification
                     (cid, submittime, sender, probid, category, queue, body)
                     VALUES (%i, %s, %i, %i, %s, %s, %s)',
                    $cid, now(), $teamid, $probid, $category, $queue, $_POST['bodytext']);

    eventlog('clarification', $newid, 'create', $cid);
    auditlog('clarification', $newid, 'added', null, null, $cid);

    // redirect back to the original location
    header('Location: ./');
    exit;
}

?>

<div class="modal fade" tabindex="-1" role="dialog" style="padding-right: 15px; display: block;" aria-modal="true"> 
<div class="modal-dialog modal-lg" role="document"> 
<div class="modal-content"> 
<div class="modal-header">

<?php

if (isset($id)) {
    // display clarification thread
    if ($myrequest) {
        echo "<h2 class=\"modal-title\">提问版</h2>\n\n";
    } else {
        echo "<h2 class=\"modal-title\">公告板</h2>\n\n";
    }
    echo "<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"> <span aria-hidden=\"true\">&times;</span> </button>";
    echo "</div>";

    echo "<div class=\"modal-body\"><div class=\"container clarificationform\"><div class=\"card card-body\">";
    putClarification($respid, $teamid);

    echo '</div>';
    echo "</div>";

    echo '<div class="collapse mt-3 container clarificationform" id="collapsereplyform"><div class="card card-body">';
    putClarificationForm("clarification.php", $id, $cid);
    echo '</div></div></div>';
    echo '<div class="modal-footer"><button class="btn btn-secondary" data-toggle="collapse" data-target="#collapsereplyform" aria-expanded="false" aria-controls="collapsereplyform"> <i class="fa fa-reply"></i> 回复本条内容 </button> <button type="button" class="btn btn-secondary" data-dismiss="modal"> 取消 </button>';
    echo '</div>';
} else {
    // display a clarification request send box
    echo "<h1>请完善您的提问内容</h1>\n\n";
    putClarificationForm("clarification.php", null, $cid);
}

?>

</div>
</div>
