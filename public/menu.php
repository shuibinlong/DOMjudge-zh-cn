<?php
$fdata = calcFreezeData($cdata);
$started = checkrole('jury') || $fdata['started'];
?>
<nav class="navbar navbar-expand-md navbar-light bg-light fixed-top">

      <a class="navbar-brand hidden-sm-down" href="./">BIT Online Judge</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#menuDefault" aria-controls="menuDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="menuDefault">
        <ul class="navbar-nav mr-auto">
      <li class="nav-item<?=($pagename === 'index.php'?' active':'')?>">
            <a class="nav-link" href="./"><span class="fas fa-list-ol"></span> 排行榜</a>
          </li>
      <li class="nav-item<?=($pagename === 'problems.php'?' active':'')?>">
<?php if ($started): ?>
            <a class="nav-link" href="problems.php"><span class="fas fa-book-open"></span> 题目列表</a>
<?php else: ?>
            <a class="nav-link disabled"><span class="fas fa-book-open"></span> 题目列表</a>
<?php endif; ?>
          </li>
<?php if (checkrole('team')): ?>
          <li class="nav-item">
            <a class="nav-link" href="../team/"><span class="fas fa-arrow-right"></span> 比赛中心</a>
      </li>
<?php endif; ?>
<?php if (checkrole('jury') || checkrole('balloon')): ?>
          <li class="nav-item">
            <a class="nav-link" href="../jury"><span class="fas fa-arrow-right"></span> Jury</a>
      </li>
<?php endif; ?>
         </ul>
      </div>
<?php
logged_in(); // fill userdata

if (!logged_in()) {
    echo '<div id="loginbut"><a class="nav-link justify-content-center" href="login.php"><button type="button" class="btn btn-info btn-sm"><span class="fas fa-sign-in-alt"></span> 登录</button></a></div>';
} else {
    echo '<div id="logoutbut"><a class="nav-link justify-content-center" href="../logout"><button type="button" class="btn btn-outline-info btn-sm"><span class="fas fa-sign-out-alt"></span> 注销</button></a></div>';
}

if (! $isstatic) {
    putClock();
}
?>
    </nav>
<?php putProgressBar(-9); ?>
