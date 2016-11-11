<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="cn">
	<head>
		<meta charset="utf-8" />
		<title>登录界面</title>

		<!-- basic styles -->

		<link href="/thinkphp/Public/Admin/assets/css/bootstrap.min.css" rel="stylesheet" />
		<link rel="stylesheet" href="/thinkphp/Public/Admin/assets/css/font-awesome.min.css" />

		<!--[if IE 7]>
		  <link rel="stylesheet" href="assets/css/font-awesome-ie7.min.css" />
		<![endif]-->

		<!-- page specific plugin styles -->

		<!-- fonts -->

		<link rel="stylesheet" href="/thinkphp/Public/Admin/assets/css/fontsgoogle.css" />

		<!-- ace styles -->

		<link rel="stylesheet" href="/thinkphp/Public/Admin/assets/css/ace.min.css" />
		<link rel="stylesheet" href="/thinkphp/Public/Admin/assets/css/ace-rtl.min.css" />

		<!--[if lte IE 8]>
		  <link rel="stylesheet" href="assets/css/ace-ie.min.css" />
		<![endif]-->

		<!-- inline styles related to this page -->

		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

		<!--[if lt IE 9]>
		<script src="assets/js/html5shiv.js"></script>
		<script src="assets/js/respond.min.js"></script>
		<![endif]-->
	</head>

	<body class="login-layout">
		<div class="main-container">
			<div class="main-content">
				<div class="row">
					<div class="col-sm-10 col-sm-offset-1">
						<div class="login-container">
							<div class="center">
								<h1>
									<i class="icon-leaf green"></i>
									<span class="red">CSW</span>
									<span class="white">登录</span>
								</h1>
								<h4 class="blue">&copy; Community Service Website</h4>
							</div>

							<div class="space-6"></div>

							<div class="position-relative">
								<div id="login-box" class="login-box visible widget-box no-border">
									<div class="widget-body">
										<div class="widget-main">
											<h4 class="header blue lighter bigger">
												<i class="icon-coffee green"></i>
												请输入你的登录信息
											</h4>

											<div class="space-6"></div>

											<form action="<?php echo U('Login/doLogin');?>" method="post">
												<fieldset>
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="text" name="name" class="form-control" placeholder="手机/邮箱/用户名" />
															<i class="icon-user"></i>
														</span>
													</label>

													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="password" name="password" class="form-control" placeholder="密码" />
															<i class="icon-lock"></i>
														</span>
													</label>

													<label class="block ">
														<span class="block input-icon-right" style="width:140px;float:left">
															<input type="text" name="yzm" class="form-control" placeholder="验证码" />
														</span>
													</label>
															<img src="<?php echo U('Login/yzm');?>" id="yz_img" title="点击刷新" style="float:right;cursor:pointer">

													<div class="space"></div>

													<!-- <div class="clearfix">
														<label class="inline">
															<input type="checkbox" class="ace" />
															<span class="lbl"> 记住账号密码</span>
														</label>

														<button type="button" class="width-35 pull-right btn btn-sm btn-primary">
															<i class="icon-key"></i>
															登录
														</button>
													</div> -->

													<div class="space-4"></div>
												</fieldset>
											
										</div><!-- /widget-main -->

										<div class="toolbar clearfix" style="height:80px;line-height:80px;text-align:center;">
											<!-- <div>
												<a href="#" onclick="show_box('forgot-box'); return false;" class="forgot-password-link">
													<i class="icon-arrow-left"></i>
													忘记密码
												</a>
											</div> -->
											<button type="submit" class="width-35 btn btn-sm btn-primary" style="width:600px;height:70px;font-size:20px">
												<i class="icon-key"></i>
												登录
											</button>
											</form>
											<!--<div>
												<a href="#" onclick="show_box('signup-box'); return false;" class="user-signup-link">
													切换账号
													<i class="icon-arrow-right"></i>
												</a>
											</div> -->
										</div>
									</div><!-- /widget-body -->
								</div><!-- /login-box -->
																
							</div><!-- /position-relative -->
						</div>
					</div><!-- /.col -->
				</div><!-- /.row -->
			</div>
		</div><!-- /.main-container -->



		<!-- basic scripts -->

		<!--[if !IE]> -->

		<!--不能删 // <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script> -->

		<!-- <![endif]-->

		<!--[if IE]>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<![endif]-->

		<!--[if !IE]> -->

		<script type="text/javascript">
			window.jQuery || document.write("<script src='/thinkphp/Public/Admin/assets/js/jquery-2.0.3.min.js'>"+"<"+"/script>");
		</script>

		<!-- <![endif]-->

		<!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='assets/js/jquery-1.10.2.min.js'>"+"<"+"/script>");
</script>
<![endif]-->

		<script type="text/javascript">
			if("ontouchend" in document) document.write("<script src='/thinkphp/Public/Admin/assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
		</script>

		<!-- inline scripts related to this page -->

		<script type="text/javascript">
			function show_box(id) {
			 jQuery('.widget-box.visible').removeClass('visible');
			 jQuery('#'+id).addClass('visible');
			}
		</script>
	<div style="display:none"><script src='/thinkphp/Public/Admin/assets/js/cnzz.js' language='JavaScript' charset='gb2312'></script></div>
	<script src="/thinkphp/Public/Public/js/jquery.min.js"></script>
	<script>
		var yz_img = $('#yz_img');
		var src_img = yz_img.attr('src');
		yz_img.click(function(){
			$(this).attr('src',src_img+'?yzm='+Math.random());
		});
	</script>
</body>
</html>