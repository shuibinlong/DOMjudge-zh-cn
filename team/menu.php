<?php
$fdata = calcFreezeData($cdata);
$started = checkrole('jury') || $fdata['started'];
?>


<?php
$langdata = $DB->q('KEYTABLE SELECT langid AS ARRAYKEY, name, extensions, require_entry_point, entry_point_description
FROM language WHERE allow_submit = 1');

$probdata = $DB->q('TABLE SELECT probid, shortname, name FROM problem
INNER JOIN contestproblem USING (probid)
WHERE cid = %i AND allow_submit = 1
ORDER BY shortname', $cid);
?>

<script>
<?php putgetMainExtension($langdata);?>
</script>

<?php
$maxfiles = dbconfig_get('sourcefiles_limit', 100);

$probs = array();
$probs[''] = '请选择一个题目';
foreach ($probdata as $probinfo) {
    $probs[$probinfo['probid']]=$probinfo['shortname'] . ' - ' .$probinfo['name'];
}

$langs = array();
$langs[''] = '请选择一种编程语言';
foreach ($langdata as $langid => $langdata) {
    $langs[$langid] = $langdata['name'];
}
?>

<div class="modal fade" id="SubmitForm"> 
   <div class="modal-dialog"> 
    <div class="modal-content"> 
     <div class="modal-header"> 
      <h2 class="modal-title">提交代码</h2> 
      <button type="button" class="close" data-dismiss="modal">&times;</button> 
     </div>
    <form action="upload.php" method="post" enctype="multipart/form-data" onsubmit="return checkUploadForm();">
     <div class="modal-body"> 
        <div class="form-group">
          <label for="maincode">源文件：</label>
          <div class="custom-file">
            <input type="file" class="custom-file-input" name="code[]" id="maincode" required <?=($maxfiles > 1 ? 'multiple': '')?> />
            <label for="maincode" class="custom-file-label">未选择任何文件</label>
          </div>
        </div>
        <div class="form-group">
          <label for="probid">题号：</label>
          <select class="custom-select" name="probid" id="probid" required>
<?php
    foreach ($probs as $probid => $probname) {
        print '      <option value="' .specialchars($probid). '">' . specialchars($probname) . "</option>\n";
    }
?>
          </select>
        </div>
        <div class="form-group">
          <label for="langid">语言：</label>
          <select class="custom-select" name="langid" id="langid" required>
<?php
    foreach ($langs as $langid => $langname) {
        print '      <option value="' .specialchars($langid). '">' . specialchars($langname) . "</option>\n";
    }
?>
          </select>
        </div>
        <div class="form-group">
          <label for="entry_point" id="entry_point_text">程序入口点:</label>
          <input type="text" class="form-control" name="entry_point" id="entry_point" aria-describedby="entrypointhelp">
          <small id="entrypointhelp" class="form-text text-muted">请输入代码的程序入口点（C 和 C++ 可空，Java 请输入其入口类名）</small>
        </div>
        <script type="text/javascript">initFileUploads(<?=$maxfiles?>);</script>
     </div> 
     <div class="modal-footer">
	  <button type="submit" name="submit" class="btn btn-primary"><span class="fas fa-cloud-upload-alt"></span> 提交</button> 
      <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
     </div>
     </form>
    </div>
   </div>
  </div>

  <script>
    $('#maincode').on('change', function() {
      var filename = $(this).val();
      if (filename !== '' && filename !== undefined) {
        filename = filename.replace(/^.*[\\\/]/, '');
        $(this).next('.custom-file-label').html(filename);
      }
    })
  
    function ShowSubmitDialog() {
      $("#SubmitForm").modal("show");
    }
  </script>



    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
      <a class="navbar-brand hidden-sm-down" href="./">BIT Online Judge</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#menuDefault" aria-controls="menuDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="menuDefault">
        <ul class="navbar-nav mr-auto">
      <li class="nav-item<?=($pagename === 'index.php'?' active':'')?>">
            <a class="nav-link" href="./"><span class="fas fa-home"></span> 首页</a>
          </li>
      <li class="nav-item<?=($pagename === 'problems.php'?' active':'')?>">
<?php if ($started): ?>
            <a class="nav-link" href="problems.php"><span class="fas fa-book-open"></span> 题目列表</a>
<?php else: ?>
            <a class="nav-link disabled"><span class="fas fa-book-open"></span> 题目列表</a>
<?php endif; ?>
          </li>
<?php if (have_printing()): ?>
      <li class="nav-item<?=($pagename === 'print.php'?' active':'')?>">
            <a class="nav-link" href="print.php"><span class="fas fa-print"></span> 打印</a>
      </li>
<?php endif; ?>
      <li class="nav-item<?=($pagename === 'scoreboard.php'?' active':'')?>">
            <a class="nav-link" href="scoreboard.php"><span class="fas fa-list-ol"></span> 排行榜</a>
          </li>
<?php if (checkrole('jury') || checkrole('balloon')): ?>
          <li class="nav-item">
            <a class="nav-link" href="../jury"><span class="fas fa-arrow-right"></span> Jury</a>
      </li>
<?php endif; ?>
         </ul>
      </div>

<?php if ($started): ?>
      <div id="submitbut"><a class="nav-link justify-content-center" onclick="ShowSubmitDialog()"><button type="button" class="btn btn-success btn-sm"><span class="fas fa-cloud-upload-alt"></span> 提交代码</button></a></div>
<?php else: ?>
      <div id="submitbut"><a class="nav-link justify-content-center"><button type="button" class="btn btn-success btn-sm disabled"><span class="fas fa-cloud-upload-alt"></span> 提交代码</button></a></div>
<?php endif; ?>

<div id="logoutbut"><a class="nav-link justify-content-center" href="../logout"><button type="button" class="btn btn-outline-info btn-sm" onclick="return confirmLogout();"><span class="fas fa-sign-out-alt"></span> 注销</button></a></div>

<?php putClock(); ?>
    </nav>
<?php putProgressBar(-9); ?>
