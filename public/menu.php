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
            <a class="nav-link" href="./"><span class="octicon octicon-list-ordered"></span> 排行榜</a>
          </li>
      <li class="nav-item<?=($pagename === 'problems.php'?' active':'')?>">
<?php if ($started): ?>
            <a class="nav-link" href="problems.php"><span class="octicon octicon-book"></span> 题目列表</a>
<?php else: ?>
            <a class="nav-link disabled"><span class="octicon octicon-book"></span> 题目列表</a>
<?php endif; ?>
          </li>
<?php if (!logged_in()): ?>
		<li class="nav-item">
			<a class="nav-link" href="register.php"><span class="octicon octicon-organization"></span> 报名参赛</a>
        </li>
<?php endif; ?>
<?php if (checkrole('team')): ?>
          <li class="nav-item">
            <a class="nav-link" href="../team/"><span class="octicon octicon-arrow-right"></span> 比赛中心</a>
      </li>
<?php endif; ?>
<?php if (checkrole('jury') || checkrole('balloon')): ?>
          <li class="nav-item">
            <a class="nav-link" href="../jury"><span class="octicon octicon-arrow-right"></span> Jury</a>
      </li>
<?php endif; ?>
         </ul>
      </div>
<?php
logged_in(); // fill userdata

if (!logged_in()) {
    echo '<div id="loginbut"><a class="nav-link justify-content-center" href="login.php"><button type="button" class="btn btn-info btn-sm"><span class="octicon octicon-sign-in"></span> 登录</button></a></div>';
} else {
    echo '<div id="logoutbut"><a class="nav-link justify-content-center" href="../logout"><button type="button" class="btn btn-outline-info btn-sm"><span class="octicon octicon-sign-out"></span> 注销</button></a></div>';
}

if (! $isstatic) {
    putClock();
}
?>
    </nav>
<?php putProgressBar(-9); ?>
