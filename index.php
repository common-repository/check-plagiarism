<?php

/*
Plugin Name: Check Plagiarism
Plugin URI: http://www.check-plagiarism.com/
Description: This a plagiarism checker plugin which gives the most accurate results. This plugin gives you functionelity to check your new posts for plagiarism before publishing.You can also check your existing posts for plagiarism.
Version: 2.0
Author: Check Plagiarism
Author URI: https://profiles.wordpress.org/plagiarismchecker/
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/
global $wpdb;
global $jal_db_version;
$jal_db_version = '1.0';

function chk_plag_mine_plugin_my_custom_admin_page($hook) {
    if ( is_admin() ) {
        if ( 'post.php' == $hook ) {
			wp_enqueue_style("chk_plag_mine_plugin_css", plugin_dir_url( __FILE__ )."assets/css/style.css", false, "2.0", "all");
		}
    }
}

add_action( 'admin_enqueue_scripts', 'chk_plag_mine_plugin_my_custom_admin_page' );


add_action( 'admin_footer', 'chk_plagkku587764_mine_plugin_add_script' );
function chk_plagkku587764_mine_plugin_add_script() {
?> 
<style>
	.chkplajbox {
		justify-content: center;
		align-items: center;
		display: flex;
	}

	.chkplajmodel-box {
		width: 400px !important;
		display: flex;
		flex-direction: column;
		align-items: center;
		background-color: #fff;
		color: #000;
		text-align: center;
		border-radius: 20px;
		padding: 30px 30px 40px;
		display: none;
		position: fixed;
		top: 100px;
		z-index: 60;
		margin-left: unset;
		left: unset;
		border: 1px solid #ddd;
		box-shadow: rgb(0 0 0 / 35%) 0px 5px 15px;
	}


	.chkplajmodel-box button.chkplajclose {
		width: 30px;
		font-size: 20px;
		color: #000000;
		align-self: flex-end;
		background-color: transparent;
		border: none;
		margin-bottom: 10px;
		float: right;
    	opacity: .5;
	}
	.chkplajclose:hover {
		color: #000;
		/* text-decoration: none; */
		/* cursor: pointer; */
		opacity: 1;
	}
	.chkplajmodel-box img {
		width: 182px;
		margin-bottom: 15px;
	}

	.chkplajmodel-box p {
		margin-bottom: 40px;
		font-size: 18px;
	}

	.chkplajmodel-box a.chkplajaccept {
		background-color: #C9302C;
		border: none;
		border-radius: 5px;
		width: 200px;
		padding: 14px;
		font-size: 16px;
		color: white !important;
		box-shadow: 0px 6px 18px -5px #ed6755;
	}
	.chkplajmodel-bg {
		background: rgba(0,0,0,.4);
		height: 100%;
		width: 100%;
		position: fixed;
		z-index: 50;
	}
</style>
<div class="chkplajmodel-bg" style="display: none;"></div>
<div class="chkplajbox">
	<div class="chkplajmodel-box">
		<button class="chkplajclose chkplajmodelclose-model">✖</button>
		<img src="https://www.check-plagiarism.com/imgs/model-bg.png" alt="alert-img" />
		<p id="chkplajmodal-content"></p>
	</div>
</div>
<script>
	function chkplajshowModel(data)
	{
		jQuery("#chkplajmodal-content").html(data);
		jQuery(".chkplajmodel-box").fadeIn(500);
		jQuery(".chkplajmodel-bg").show();
	}

	jQuery(".chkplajmodelclose-model, .chkplajmodel-bg").click(function(){
		jQuery(".chkplajmodel-box").hide();
		jQuery(".chkplajmodel-bg").fadeOut(800);
	});
</script>
<?php

 if(basename($_SERVER['PHP_SELF']) == "post.php") { ?>
		<div class="pluginDivv">
		
			<style>.modal{display:none;position:fixed;z-index:1;padding-top:100px;left:0;top:0;width:100%;height:100%;overflow:auto;background-color:rgba(0,0,0,.4)}.modal-content{position:relative;background-color:#fefefe;margin:auto;padding:0;border:1px solid #888;width:80%;box-shadow:0 4px 8px 0 rgba(0,0,0,.2),0 6px 20px 0 rgba(0,0,0,.19);-webkit-animation-name:animatetop;-webkit-animation-duration:.4s;animation-name:animatetop;animation-duration:.4s}@-webkit-keyframes animatetop{from{top:-300px;opacity:0}to{top:0;opacity:1}}@keyframes animatetop{from{top:-300px;opacity:0}to{top:0;opacity:1}}.close{color:#fff;float:right;font-size:28px;font-weight:700}.close:focus,.close:hover{color:#000;text-decoration:none;cursor:pointer}.modal-footer,.modal-header{padding:2px 16px;background-color:#5cb85c;color:#fff}.modal-body{padding:2px 16px}.modal-footer:before{display:none !important;}.modal{    padding-top: 20px !important;}</style>
								
			<div id="myModal" class="modal">
				<div class="modal-content">
					
					<div class="modal-body">
						<div class="container-fluid">
							<div class="plg-res" id="mainResultsDisplay" style="">
								<div class="col-lg-12 result_box_inner">
									<div class="col-sm-6 noP sentences">
										<div class="col-sm-5 boxresult text-success"> <span class="resultText text-success" id="howUnique"><span id="uniqueCount">0</span>%</span> <strong>Unique Content</strong> </div>
										<div class="col-sm-1"></div>
										<div class="col-sm-5 boxresult text-danger"> <span class="resultText"><span id="plagCount">100</span>%</span> <strong>Plagiarized content</strong> </div>
									</div>
									<div class="col-sm-5 noP pull-right loader-box"> <span id="loadGif"><i class="fa fa-check"></i> COMPLETED</span>
										<div class="progress">
											<div id="percentBar" class="progress-bar active" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">100%</div>
										</div>
									</div>
									<hr class="clear-both"> </div>
								<div class="col-md-12 pl-0 checking-box">
									<div class="col-md-12">
										<p class="animateBorder" style="display: none;"> <i class="fa fa-spinner fa-pulse fa-spin"></i> <strong>Checking:</strong> <span class="status">Analyzing and checking sentences against billions of websites and documents..</span> </p>
									</div>
									<div class="col-md-12 main-tabs">
										<ul class="nav nav-tabs">
											<li class="active"><a name="sentences-box" class="shiftLayoutPlug sentenceee" data-typee="sentenceee">Sentence wise results</a> </li>
											<li class=""><a name="matches" class="shiftLayoutPlug matchess" data-typee="matchess">Matched URLs</a> </li>
										</ul>
									</div>
									<div class="col-md-12 resultsBars hideAlerts tab-box" id="sentences-box" style=""> </div>
									<div class="col-md-12 matches tab-box" style="display: none;" id="matches">
										<table class="table table-bordered match_table" style="">
											<thead>
												<tr class="bg-warning">
													<td width="85%"> Sources </td>
													<td width="15%" align="center" id="simi"> Similarity </td>
												</tr>
											</thead>
											<tbody> </tbody>
										</table>
										<div class="match col-sm-12 noP default-match-msg" style="display: none;"> No sources found against your content.. </div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer" style="background: #fff;">
						<button class="btn btn-danger" id="closeit">Close</button> 
					</div>
				</div>
			</div>
		</div>
		<?php } ?>
	
				<script class="pluginSScript">
				<?php if(basename($_SERVER['PHP_SELF']) == "post.php") { ?>
				jQuery(document).find(".shiftLayoutPlug").click(function() {
					var typ = jQuery(this).attr('data-typee');
					if(typ == "sentenceee")
					{	
						jQuery(".matchess").parent().removeClass('active');
						jQuery(this).parent().addClass('active');
						jQuery(document).find("#matches").hide();
						jQuery(document).find("#sentences-box").show();
					}
					else
					{
						jQuery(".sentenceee").parent().removeClass('active');
						jQuery(this).parent().addClass('active');
	
						jQuery(document).find("#sentences-box").hide();
						jQuery(document).find("#matches").show();
					}
				});
				function compareIT(obj)
				{
					var id = jQuery(obj).attr('data-key');
					jQuery("#"+id).toggle();
				}
				<?php } elseif(basename($_SERVER['PHP_SELF']) == "edit.php") { ?>
				function runAllAjax(idsArr,cStatus) {
					// initialize index counter
					var i = 0;
	
					function next() {
						var id = idsArr[i].id;
						var elemm = idsArr[i].elemm;
						jQuery.ajax({
							url: ajaxurl, // this is the object instantiated in wp_localize_script function
							type: 'POST',
							dataType: 'json',
							async: true,
							data:{ 
								action 				: 'chk_plag_mine_plugin_multipleplgCheck',
									actionagain 				: cStatus,
									
							id: id, // this is the function in your functions.php that will be triggered
							},
							beforeSend: function()
							{
								jQuery(document).find(".chkPlgAll, .chkPlgSingle").attr("disabled","disabled")
								jQuery(document).find(".chkPlgAll, .chkPlgSingle").html("Checking Plagiarism ... ");
							},
							success: function(dat)
							{
								
								console.log(dat);
	
								++i;
								jQuery(document).find(".chkPlgAll, .chkPlgSingle").removeAttr("disabled")
									jQuery(document).find(".chkPlgAll").html("Check Plagiarism For All Posts");
									jQuery(document).find(".chkPlgSingle").html("Check Plagiarism");
									
									
									if(typeof(dat.error) != "undefined" && dat.error !== null) {
										if(dat.error == 'API Key not provided')
										{
											var msgg = `Api Key Not Provided. Kindly Get Your API Key From <a href="https://www.check-plagiarism.com/" target="_blank">Check-Plagiarism.com</a> <br>Or<br> Enter Your Api Key<a href="<?= admin_url() ?>admin.php?page=checkplagiarism-settings" target="_blank"> Here.</a>`;
										}
										else if(dat.error == 'Authentication Error')
										{
											var msgg = `API Key You Provided Is Wrong. Kindly Get Your API Key From <a href="https://www.check-plagiarism.com/" target="_blank">Check-Plagiarism.com</a> <br>Or<br> Enter Your Api Key<a href="<?= admin_url() ?>admin.php?page=checkplagiarism-settings" target="_blank"> Here.</a>`;
										}
										else
										{
											var msgg = dat.error;
										}
										chkplajshowModel(msgg);
										return;
									}
									if(elemm.find('.alert-danger').length > 0) {
	
										elemm.find('td:last-child').remove();
									}
									elemm.append(`
									<td><p class="alert alert-danger" style="background-color: #f44336;color: white;padding: 3px;text-align: center;">Plagiarized: `+dat.plagPercent+`%</p>
									<p class="alert alert-danger" style="background-color: #04AA6D;color: white;padding: 3px;text-align: center;">Unique: `+dat.uniquePercent+`%</p></td>
									`);
									elemm.addClass('checkedd');
								if(i >= idsArr.length) {
									
								} else {
									// do the next ajax call
									next();
								}
	
								
							},
							complete: function() {
								jQuery(document).find(".chkPlgAll, .chkPlgSingle").removeAttr("disabled")
								jQuery(document).find(".chkPlgAll").html("Check Plagiarism For All Posts");
								jQuery(document).find(".chkPlgSingle").html("Check Plagiarism");
							}
						});
					}
					// start the first one
					next();
				}
				<?php } ?>
				window.addEventListener("load",function(event) {
					<?php if(basename($_SERVER['PHP_SELF']) == "edit.php") { ?>
					jQuery("#title").css('width','14%');
					jQuery("#categories, #tags, #comments").css('width','auto');
					jQuery(document).find("#the-list").find('tr').each( function(ind,val) {

						var elemm = jQuery(this);
						var id = +elemm.attr('id').replace("post-","");
						elemm.append('<td><button type="button" class="button chkPlgSingle" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;">Check Plagiarism</button></td><td class="lastTd"><p class="alert alert-danger">--</p></td>')
						jQuery.ajax({
							url: ajaxurl, // this is the object instantiated in wp_localize_script function
							type: 'POST',
							dataType: 'json',
							async: true,
							data:{ 
								action 				: 'chk_plag_mine_plugin_multipleplgCheck',
								actionagain 				: "checkStatus",
									
							id: id, // this is the function in your functions.php that will be triggered
							},
							success: function(dat)
							{
								if(dat == '')
								{

								}
								else
								{
									elemm.find('.lastTd').html(`<p class="alert alert-danger" style="background-color: #f44336;color: white;padding: 3px;text-align: center;">Plagiarized: `+dat.plagPercent+`%</p>
									<p class="alert alert-danger" style="background-color: #04AA6D;color: white;padding: 3px;text-align: center;">Unique: `+dat.uniquePercent+`%</p>`);
									elemm.addClass('checkedd')
								}

							}
					});


					});
	
					jQuery(document).find("#the-list").parent().find('thead').find('tr').append('<th scope="col" class=" manage-column column-date sortable asc">&nbsp;</th><th scope="col" class="plgLabel manage-column column-date sortable asc">Results</th>');
					jQuery(document).find("#the-list").parent().find('tfoot').find('tr').append('<th scope="col" class=" manage-column column-date sortable asc">&nbsp;</th><th scope="col" class="plgLabel manage-column column-date sortable asc">Results</th>');
	
					jQuery('.tablenav.top').prepend('<button type="button" class="button chkPlgAll" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;">Check Plagiarism For All Posts</button>');
					
					
				
					jQuery(document).find(".chkPlgSingle").click(function() { 
						var idsArr = new Array;
						var chkArr = [];
						var elemm = jQuery(this).parent().parent();
						var id = +elemm.attr('id').replace("post-","");
						console.log(id);
							chkArr['id'] = id;
							chkArr['elemm'] = elemm;
							idsArr.push(chkArr);
							runAllAjax(idsArr, 'ajax_multiple');
					});
					jQuery(document).find(".chkPlgAll").click(function() { 
						var idsArr = new Array;
						jQuery(document).find("#the-list").find('tr').each( function(ind,val) {
							if (!jQuery(this).hasClass("checkedd")) {
								var chkArr = [];
								var elemm = jQuery(this);
								var id = +elemm.attr('id').replace("post-","");
								chkArr['id'] = id;
								chkArr['elemm'] = elemm;
								idsArr.push(chkArr);
							}
						});
						// console.log(idsArr)
						if(idsArr.length !== 0)
						{
							runAllAjax(idsArr,'ajax_multiple');
						}
					});
					<?php } ?>
					<?php if(basename($_SERVER['PHP_SELF']) == "post.php") { ?>
	
						jQuery(document).find(".edit-post-header__settings").prepend('<button type="button" class="components-button editor-post-switch-to-plagiarism is-primary">Check Plagiarism</button>');
						jQuery('.edit-post-sidebar__panel-tabs').find('ul').append('<li><button type="button" aria-label="Plagiarism" data-label="Plagiarism" class="components-button  plgBtn" id="plgBtn" style="position: relative;z-index: 1;border-radius: 0;height: 48px;background: transparent;border: none;box-shadow: none;cursor: pointer;display: inline-block;padding: 3px 15px;margin-left: 0;font-weight: 500;">Plagiarism</button></li>');
	
						jQuery(document).find(".editor-post-switch-to-plagiarism").click(function() {
							var text = jQuery(".block-editor-block-list__layout").text();

							if(text.length < 30)
							{
								var msg = 'Please Enter atleast 15 words to check for plagiarism';
								chkplajshowModel(msg);
								return;
							}
								console.log(text);
	
								jQuery.ajax({
									url: ajaxurl, // this is the object instantiated in wp_localize_script function
									type: 'POST',
									dataType: 'json',
									data:{ 
											action 				: "chk_plag_mine_plugin_singlePlgReq",
											actionagain 				: "ajax_single",
									text: text, // this is the function in your functions.php that will be triggered
									},
									beforeSend: function()
									{
										jQuery(document).find(".editor-post-switch-to-plagiarism").addClass("is-busy");
										jQuery(document).find(".editor-post-switch-to-plagiarism").attr("disabled","disabled")
										jQuery(document).find(".editor-post-switch-to-plagiarism").html("Checking Plagiarism ... ");
									},
									success: function( data ){
										jQuery(document).find(".editor-post-switch-to-plagiarism").removeAttr("disabled")
										jQuery(document).find(".editor-post-switch-to-plagiarism").removeClass("is-busy");
										jQuery(document).find(".editor-post-switch-to-plagiarism").html("Check Plagiarism");
										if(typeof(data.error) != "undefined" && data.error !== null) {
											if(data.error == 'API Key not provided')
											{
												var msgg = `Api Key Not Provided. Kindly Get Your API Key From <a href="https://www.check-plagiarism.com/" target="_blank">Check-Plagiarism.com</a> <br>Or<br> Enter Your Api Key<a href="<?= admin_url() ?>admin.php?page=checkplagiarism-settings" target="_blank"> Here.</a>`;
											}
											else if(data.error == 'Authentication Error')
											{
												var msgg = `API Key You Provided Is Wrong. Kindly Get Your API Key From <a href="https://www.check-plagiarism.com/" target="_blank">Check-Plagiarism.com</a> <br>Or<br> Enter Your Api Key<a href="<?= admin_url() ?>admin.php?page=checkplagiarism-settings" target="_blank"> Here.</a>`;
											}
											else
											{
												var msgg = data.error;
											}
											chkplajshowModel(msgg);
											return;
										}
										var htmll = ``;
										jQuery.each(data.details, function(ind, val) {
											if(typeof(val.query) != "undefined" && val.query !== null)
											{
												var qq = val.query.length;
											}
											else
											{
												var qq = 0;
											}
											qq = (qq >= 90) ? val.query.substring(0,87) : val.query;
											if(val.unique == "false") {
												var ddes = (val.display.des == "") ? qq : val.display.des;
												ddes = ddes.replace(val.query, "<strong style=\"color: #c94440;\">"+val.query+"</strong>");
												htmll+= `<div class="alert alert-danger my-alert" style=""><span class="status">Plagiarized</span>
														<p>`+qq+`</p><a class="comp-btn btn btn-default btn-xs" href="javascript:void(0)" data-show="hide" onclick="compareIT(this)" data-key="plagtext`+ind+`">Compare</a>
													</div>
													<div id="plagtext`+ind+`" style="display:none">
														<table class="table table-bordered bdr compareTable">
															<thead>
																<tr>
																	<td colspan="3" class="bg-primary" style="font-size: 18px;">Following results found against this sentence</td>
																</tr>
															</thead>
															<tbody>
																<tr>
																	<td width="40%"><strong>URL</strong>
																	</td>
																	<td width="60%"><strong>TEXT</strong>
																	</td>
																</tr>
																<tr>
																	<td><a style="word-break: break-all;" href="`+val.display.url+`" target="_blank">`+val.display.url+`</a>
																	</td>
																	<td><span class="description">`+ddes+`</span>
																	</td>
																</tr>
															</tbody>
														</table>
													</div>
													
													`;
											}
											else {
												htmll+= `<div class="alert alert-success my-alert" style=""><span class="status">Unique</span>
														<p>`+qq+`</p>
													</div>`;
											}
										});
										var sourcs = "";
										if(data.sources !== "") {
											jQuery.each(data.sources, function(ind,val) {
												sourcs+=`
												<tr >
												<td><span class="title">`+val.title+`</span> <a target="_blank"
													href="`+val.link+`" class="link">`+val.link+`</a> </td>
												<td class="s_p">`+val.percent+`%</td>
											</tr>
												`;
											});
											jQuery(document).find(".match_table").find('tbody').html(sourcs);
										}
										console.log(htmll);
										jQuery(document).find("#sentences-box").html(htmll);
										jQuery(document).find("#plagCount").text(data.plagPercent);
										
										jQuery(document).find("#uniqueCount").text(data.uniquePercent);
	
										jQuery(document).find("#plgBtn").click(function() {
										jQuery(document).find("#myModal").show();
										});
	
										jQuery(document).find("#closeit").click(function() {
										jQuery(document).find("#myModal").hide();
										});	
	
										jQuery(document).click(function (e) {
											if (jQuery(e.target).is('#myModal')) {
												jQuery('#myModal').hide();
											}
	
										});
	
										jQuery(document).find("#plgBtn").click();
	
								
									},
									complete: function() {
										jQuery(document).find(".editor-post-switch-to-plagiarism").removeAttr("disabled")
										jQuery(document).find(".editor-post-switch-to-plagiarism").removeClass("is-busy");
										jQuery(document).find(".editor-post-switch-to-plagiarism").html("Check Plagiarism");
									}
								});
								
						});
					<?php } ?>
	
				},false);
				
			</script>
<?php
}
// Act on plugin activation
register_activation_hook( __FILE__, "chk_plag_mine_plugin_activate_myplugin" );

// Activate Plugin
function chk_plag_mine_plugin_activate_myplugin() {
	global $wpdb;
	$plugin = plugin_dir_url( __FILE__ );

	global $jal_db_version;
	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	
	$sqlM = $wpdb->prepare("set global sql_mode = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';");
	$wpdb->get_results($sqlM);

	$table_name = $wpdb->prefix . 'plugin_users';
	
	$charset_collate = $wpdb->get_charset_collate();


	$sqlM = $wpdb->prepare( "CREATE TABLE $table_name (
		id int(11) NOT NULL AUTO_INCREMENT,
		api_key varchar(100) DEFAULT '' NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;" );
	$wpdb->get_results($sqlM);

	$table_name2 = $wpdb->prefix . 'posts';
	$sqlM = $wpdb->prepare( "ALTER TABLE $table_name2  ADD `plagPercent` INT NOT NULL  AFTER `comment_count`,  ADD `uniquePercent` INT NOT NULL  AFTER `plagPercent`;" );
	$wpdb->get_results($sqlM);
	
	add_option( 'jal_db_version', $jal_db_version );
}


// Act on plugin activation

register_deactivation_hook( __FILE__, 'chk_plag_mine_plugin_deactivate_myplugin' );

function chk_plag_mine_plugin_deactivate_myplugin() {
    global $wpdb;
	global $jal_db_version;

	$table_name = $wpdb->prefix . 'plugin_users';
	
	$sqlM = $wpdb->prepare( "DROP TABLE IF EXISTS $table_name" );
	$wpdb->get_results($sqlM);

	$table_name2 = $wpdb->prefix . 'posts';
	
	$sqlM = $wpdb->prepare( "ALTER TABLE $table_name2
	DROP `plagPercent`,
	DROP `uniquePercent`;" );
	$wpdb->get_results($sqlM);
    delete_option("my_plugin_db_version");

}



add_action('admin_menu', 'chk_plag_mine_plugin_plag_plugin_setup_menu');

 
function chk_plag_mine_plugin_plag_plugin_setup_menu(){
    add_menu_page( 'Plagiarism Plugin Page', 'Check Plagiarism', 'manage_options', 'checkplagiarism-settings', 'chk_plag_mine_plugin_load_plag_page','dashicons-schedule', 3);
}
 
function chk_plag_mine_plugin_load_plag_page(){

	global $wpdb;
	$wpdb_tablename = $wpdb->prefix.'plugin_users';
	$result = $wpdb->get_results("SELECT * FROM $wpdb_tablename");

	$api = "";
	$acton = "";
	$id = "";
	if(!empty($result))
	{
		$api = $result[0]->api_key;
		$acton = "update";
		$id = $result[0]->id;
	}
	else
	{
		$acton = "add";
		
	}
	$plugin = plugin_dir_url( __FILE__ );
    $allowed_html = array(
		'h1'      => array(),
		'form'     => array(
			'action'  => array(),
			'method' => array(),
		),
		'table'     => array(
			'class'  => array(),
			'role' => array(),
		),
		'tr' => array(),
		'th' => array(
			'scope'  => array(),
		),
		'td' => array(
			'colspan'  => array(),
		),
		'tbody' => array(),
		'input' => array(
			'name'  => array(),
			'type' => array(),
			'id' => array(),
			'value' => array(),
			'class' => array(),
		),
		'p' => array(
			'class'  => array(),
		),
		'a' => array(
			'href'  => array(),
			'target' => array(),
			'style' => array(),
		),

	);
	echo wp_kses('<h1>Plagiarism Plugin Settings</h1><form action="'.admin_url( 'admin.php' ).'" method="post"><table class="form-table" role="presentation">

				<tbody>
					<tr>
						<th scope="row">
							<label for="blogname">API Key</label>
						</th>
						<td>
							<input name="apikey" type="text" id="apikey" value="'.$api.'" class="regular-text">
							<input type="hidden" name="acton" value="'.$acton.'">
							<input type="hidden" name="id" value="'.$id.'">
							<input type="hidden" name="action" value="wpse10500">
						</td>
					</tr>
					<tr>
						
						<td colspan="2">Please Login or Signup at <a href="https://www.check-plagiarism.com/" style="font-weight: bold;" target="_blank">check-plagiarism.com</a> to get your API key</td>
					</tr>
				</tbody>
			</table><p class="submit">
			<input type="submit" name="submitApi" id="submit" class="button button-primary" value="Save Changes">
		</p></form>',$allowed_html);
}


add_action( 'wp_ajax_chk_plag_mine_plugin_singlePlgReq', 'chk_plag_mine_plugin_singlePlgReq' );

function chk_plag_mine_plugin_singlePlgReq() 
{
	global $wpdb;
	$wpdb_tablename = $wpdb->prefix.'plugin_users';
	$sqll = $wpdb->prepare("SELECT * FROM $wpdb_tablename order by id ASC");
	$result = $wpdb->get_results($sqll);
	$api = "";
	if(!empty($result))
	{
		$api = $result[0]->api_key;
		
	}
	$text = sanitize_text_field($_POST['text']);
	$text = strip_tags($text);
	$text = trim($text);

	$body = array("key" => $api,'data'=>$text);
	$args = array(
		'method' => 'POST',
		'headers' => array(
		),
		'httpversion' => '1.0',
		'timeout' => 90,
		'blocking'    => true,
		'sslverify' => false,
		'cookies'     => array(),
		'body' => $body
		);
	$data = wp_remote_retrieve_body(wp_remote_get( 'https://www.check-plagiarism.com/apis/checkPlag', $args));

	echo wp_specialchars_decode(esc_html($data),ENT_QUOTES) ;

	die();
}

add_action( 'wp_ajax_chk_plag_mine_plugin_multipleplgCheck', 'chk_plag_mine_plugin_multipleplgCheck' );
function chk_plag_mine_plugin_multipleplgCheck()
{
	global $wpdb;
    $id = sanitize_text_field($_POST['id']);
    $wpdb_tablename = $wpdb->prefix.'posts';
    $sqll = $wpdb->prepare("SELECT * FROM $wpdb_tablename where ID = %d",$id);
	$data = $wpdb->get_results($sqll);
    if(isset($_POST['actionagain']) && $_POST['actionagain'] == "checkStatus") {
        $chk = array();
        if(!empty($data)) {
            if($data[0]->plagPercent != 0 or $data[0]->uniquePercent != 0){
                $chk['isDone'] = 1;
                $chk['plagPercent'] = $data[0]->plagPercent;
                $chk['uniquePercent'] = $data[0]->uniquePercent;
            }
        }
        echo json_encode($chk);
    }

    else if(isset($_POST['actionagain']) && $_POST['actionagain'] == "ajax_multiple") {

        $wpdb_tablename = $wpdb->prefix.'plugin_users';
        $sqll = $wpdb->prepare("SELECT * FROM $wpdb_tablename order by id ASC");
		$result = $wpdb->get_results($sqll);
        $api = "";
        if(!empty($result))
        {
            $api = $result[0]->api_key;
            
        }

        
		$body = array("key" => $api,'data'=>strip_tags($data[0]->post_content));
		$args = array(
			'method' => 'POST',
			'headers' => array(
			),
			'httpversion' => '1.0',
			'timeout' => 90,
			'blocking'    => true,
			'sslverify' => false,
			'cookies'     => array(),
			'body' => $body
			);
		$data = wp_remote_retrieve_body(wp_remote_get( 'https://www.check-plagiarism.com/apis/checkPlag', $args));

        $arr = json_decode($data,true);
        $table_name2 = $wpdb->prefix . 'posts';
        $sqll = $wpdb->prepare( "UPDATE $table_name2 SET `plagPercent` = '".$arr['plagPercent']."', `uniquePercent` = '".$arr['uniquePercent']."' WHERE `ID` = %d",$id );
		$result = $wpdb->get_results($sqll);
		echo wp_specialchars_decode(esc_html($data),ENT_QUOTES) ;
    }

    die(); // ajax call must die to avoid trailing 0 in your response
}

add_action( 'admin_action_wpse10500', 'chk_plag_mine_plugin_wpse10500_admin_action' );
function chk_plag_mine_plugin_wpse10500_admin_action()
{
	global $wpdb;
	
	$table_name = $wpdb->prefix . 'plugin_users';
	
	$charset_collate = $wpdb->get_charset_collate();
	if(sanitize_text_field($_POST['acton']) == "add")
	{
			$sqll = $wpdb->prepare("INSERT into  $table_name (api_key) VALUES(%s) ",sanitize_text_field($_POST['apikey']));
	}
	elseif(sanitize_text_field($_POST['acton']) == "update")
	{
			$sqll = $wpdb->prepare("UPDATE $table_name SET `api_key` = %s WHERE `id` = %d", sanitize_text_field($_POST['apikey']), sanitize_text_field($_POST['id']) );
	}
	
	$wpdb->get_results($sqll);
	wp_redirect($_SERVER['HTTP_REFERER']);
	die();
}

?>