<?php
/**
 * Part of the DOMjudge Programming Contest Jury System and licenced
 * under the GNU GPL. See README and COPYING for details.
 */

require('init.php');
$title = specialchars($teamdata['name']);


require(LIBWWWDIR . '/header.php');
?>
<div data-ajax-refresh-target="" data-ajax-refresh-after="setFlashAndProgress" data-ajax-refresh-before="saveFlash"><div id="teamscoresummary">

<?php 


// Don't use HTTP meta refresh, but javascript: otherwise we cannot FIXME still relevant?
$refreshtime = 60;

$submitted = @$_GET['submitted'];

$fdata = calcFreezeData($cdata);
$langdata = $DB->q('KEYTABLE SELECT langid AS ARRAYKEY, name, extensions, require_entry_point, entry_point_description
                    FROM language WHERE allow_submit = 1');

echo "<script type=\"text/javascript\">\n<!--\n";

if ($fdata['started'] || checkrole('jury')) {
    $probdata = $DB->q('TABLE SELECT probid, shortname, name FROM problem
                        INNER JOIN contestproblem USING (probid)
                        WHERE cid = %i AND allow_submit = 1
                        ORDER BY shortname', $cid);

    putgetMainExtension($langdata);

    echo "function getProbDescription(probid)\n{\n";
    echo "\tswitch(probid) {\n";
    foreach ($probdata as $probinfo) {
        echo "\t\tcase '" . specialchars($probinfo['shortname']) .
            "': return '" . specialchars($probinfo['name']) . "';\n";
    }
    echo "\t\tdefault: return '';\n\t}\n}\n\n";
}
// echo "initReload(" . $refreshtime . ");\n";
echo "// -->\n</script>\n";

// Put overview of team submissions (like scoreboard)
putTeamRow($cdata, array($teamid));

//echo "<div id=\"submitlist\">\n";

if ($submitted):
?>

<div class="mt-4 alert alert-success alert-dismissible show" role="alert">
  <a href="./" class="close" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </a>
  <strong>提交成功！</strong> 请注意下面列表显示的评测结果。
</div>
<?php
endif;

/* submitform moved away
if ( ($fdata['started'] || checkrole('jury') )) {
    echo <<<HTML
<script type="text/javascript">
$(function() {
    var matches = location.hash.match(/submitted=(\d+)/);
    if (matches) {
        var \$p = \$('<p class="submissiondone" />').html('submission done <a href="#">x</a>');
        \$('#submitlist > .teamoverview').after(\$p);
        \$('table.submissions tr[data-submission-id=' + matches[1] + ']').addClass('highlight');

        \$('.submissiondone a').on('click', function() {
            \$(this).parent().remove();
            \$('table.submissions tr.highlight').removeClass('highlight');
            reloadLocation = 'index.php';
        });
    }
});
</script>
HTML;
    $maxfiles = dbconfig_get('sourcefiles_limit',100);

    echo addForm('upload.php','post',null,'multipart/form-data', null,
             ' onreset="resetUploadForm('.$refreshtime .', '.$maxfiles.');"') .
        "<p id=\"submitform\">\n\n";

    echo "<input type=\"file\" name=\"code[]\" id=\"maincode\" required";
    if ( $maxfiles > 1 ) {
        echo " multiple";
    }
    echo " />\n";


    echo addSelect('langid', $langs, '', true);

    echo addSubmit('submit', 'submit',
               "return checkUploadForm();");

    echo addReset('cancel');

    if ( $maxfiles > 1 ) {
        echo "<br /><span id=\"auxfiles\"></span>\n" .
            "<input type=\"button\" name=\"addfile\" id=\"addfile\" " .
            "value=\"Add another file\" onclick=\"addFileUpload();\" " .
            "disabled=\"disabled\" />\n";
    }
    echo "<script type=\"text/javascript\">initFileUploads($maxfiles);</script>\n\n";

    echo "</p>\n</form>\n\n";
}
// call putSubmissions function from common.php for this team.
*/

?>
<script>
var $flash = null;

function saveFlash() {
    $flash = $('[data-flash-messages]').children();
}

function setFlashAndProgress() {
    var $newProgress = $('[data-ajax-refresh-target] > [data-progress-bar]');
    if ($newProgress.length) {
        var $oldProgress = $('body > [data-progress-bar]');
        $oldProgress.html($newProgress.children());
        $newProgress.remove();
    }

    $('[data-flash-messages]').html($flash);
}

function markSeen($elem) {
    $elem.closest('tr').removeClass('unseen');
}

function markRead($elem) {
    $elem.closest('tr').removeClass('unread');
}

var refreshHandler = null;
var refreshEnabled = false;
function enableTeamRefresh($url, $after, usingAjax) {
    if (refreshEnabled) {
        return;
    }
    var refresh = function () {
        if (usingAjax) {
            $('.ajax-loader').show('fast');
            $.ajax({
                url: $url
            }).done(function(data, status, jqXHR) {
                processAjaxResponse(jqXHR, data);
                $('.ajax-loader').hide('fast');
            });
        } else {
            window.location = $url;
        }
    };
    if (usingAjax) {
        refreshHandler = setInterval(refresh, $after * 1000);
    } else {
        refreshHandler = setTimeout(refresh, $after * 1000);
    }
    refreshEnabled = true;
    window.Cookies && Cookies.set('domjudge_refresh', 1);

    if(window.location.href == localStorage.getItem('lastUrl')) {
    	window.scrollTo(0, localStorage.getItem('scrollTop'));
    } else {
    	localStorage.setItem('lastUrl', window.location.href);
    	localStorage.setItem('scrollTop', 42);
    }
    var scrollTimeout;
    document.addEventListener('scroll', function() {
      clearTimeout(scrollTimeout);
      scrollTimeout = setTimeout(function() {
        localStorage.setItem('scrollTop', $(window).scrollTop());
      }, 100)
    });
}


$(function () {
    enableTeamRefresh('/team', 30, 1);
    initializeAjaxModals();
});
</script>

<div class="modal fade" id="ClarificationForm"> 
   <div class="modal-dialog"> 
    <div class="modal-content"> 
     <div class="modal-header"> 
      <h2 class="modal-title">请完善您的提问内容</h2> 
      <button type="button" class="close" data-dismiss="modal">&times;</button> 
     </div>
     <div class="modal-body" id="clar"> 
        <?php putClarificationForm("clarification.php", null, $cid); ?>
     </div>
    </div>
   </div>
</div>

<?php if (!$fdata['started'] && !checkrole('jury')): ?>
<script>
    $('#SendButton').prop('disabled', true);
</script>
<?php endif; ?>


<div class="row">
<div class="col">
<h3 class="teamoverview">提交记录</h3>

<?php

$restrictions = array( 'teamid' => $teamid );
putSubmissions(array($cdata['cid'] => $cdata), $restrictions, null, $submitted);
?>
</div>
<div class="col">
<?php

$requests = $DB->q('SELECT c.*, cp.shortname, t.name AS toname, f.name AS fromname
                    FROM clarification c
                    LEFT JOIN problem p USING(probid)
                    LEFT JOIN contestproblem cp USING (probid, cid)
                    LEFT JOIN team t ON (t.teamid = c.recipient)
                    LEFT JOIN team f ON (f.teamid = c.sender)
                    WHERE c.cid = %i AND c.sender = %i
                    ORDER BY submittime DESC, clarid DESC', $cid, $teamid);

$clarifications = $DB->q('SELECT c.*, cp.shortname, t.name AS toname, f.name AS fromname,
                          u.mesgid AS unread
                          FROM clarification c
                          LEFT JOIN problem p USING (probid)
                          LEFT JOIN contestproblem cp USING (probid, cid)
                          LEFT JOIN team t ON (t.teamid = c.recipient)
                          LEFT JOIN team f ON (f.teamid = c.sender)
                          LEFT JOIN team_unread u ON (c.clarid=u.mesgid AND u.teamid = %i)
                          WHERE c.cid = %i AND c.sender IS NULL
                          AND ( c.recipient IS NULL OR c.recipient = %i )
                          ORDER BY c.submittime DESC, c.clarid DESC',
                         $teamid, $cid, $teamid);

echo "<h3 class=\"teamoverview\">公告区</h3>\n";

# FIXME: column width and wrapping/shortening of clarification text
if ($clarifications->count() == 0) {
    echo "<p class=\"nodata\">当前暂无最新公告。</p>\n\n";
} else {
    putClarificationList($clarifications, $teamid);
}

echo "<h3 class=\"teamoverview\">提问区</h3>\n";

if ($requests->count() == 0) {
    echo "<p class=\"nodata\">当前暂无提问记录。</p>\n\n";
} else {
    putClarificationList($requests, $teamid);
}

?>

<button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#ClarificationForm">我要提问</button>

</div>
</div>
</div>
<?php

require(LIBWWWDIR . '/footer.php');
