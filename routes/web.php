<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
| 
*/
 
/**
Arindam Note :
if want to remove en tag from url, then copy all frontend route and create another set without
lng prefix. remember you should rename the route name in new set.
now create new controller FrontEndController2
logic is -> check lng tag is 'en' or not into your current FrontEndController. 
if en then redirect to FrontEndController2 (where no lng slug)
**/

Route::get('/updateapp', function()
{
    \Artisan::call('dump-autoload');
    echo 'dump-autoload complete';
});
Route::get('/config-cache', function() {
    $exitCode = Artisan::call('config:cache');
    return 'Config cache cleared';
});
Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    return 'Application cache cleared';
});

Route::post('/arindam-form-submit', 'FormBuilder@formSubmitData')->name('frm_submit');
Route::get('/test-email', 'TestEmail@test');
/* By Force admin login redirect */
Route::get('/admin', function() {
	return redirect()->route('dashboard_login');
} );
Route::get('/filecontent', 'MediaController@filecontents')->name('filecontents');
Route::get('/en/admin', function() {
	return redirect()->route('dashboard_login');
} );
Route::get('/en/current-events/{year}', 'FrontEndController@currentEvents')->name('currentEvents');
Route::get('en/events', 'FrontEndController@eventLists')->name('eventListsen');
Route::get('/en/event-details/{id}', 'FrontEndController@eventDetails')->name('eventDetails');

Route::get('/get-event-countries', 'EventManagementController@getCountries')->name('ajaxgetCountries');
Route::get('/get-countries', 'FrontEndController@getCountries')->name('getCountries');

Route::get('commodities-api','CommoditiesApiController@fetchApi');


/* ------------ admin login redirect ------------------------- */
 

/********************** FRONT END START ***************************/
Route::group(['prefix' => '{lng?}'], function () {
 
	Route::get('/login', 'FrontEndController@login')->name('user_login');
	Route::post('/login', 'FrontEndController@loginAction')->name('user_login_action');

	/*Route::group(['middleware' => 'CompressHTML'], function() {*/

		Route::get('/', 'FrontEndController@home')->name('home');
	
		Route::get('/demo-dev', 'FrontEndController@demoDev')->name('demoDev');

		Route::get('/webinar', 'FrontEndController@webinar')->name('webinar');
		Route::get('/webinar/{id}', 'FrontEndController@webinarContent')->name('front.webinarCont');
		Route::get('/webinar-video/{id}', 'FrontEndController@webinarVideo')->name('front.webinarVideo');

		Route::post('/ajaxWebinar', 'AjaxController@ajaxWebinar')->name('ajaxWebinar');
		
		Route::post('/saveWbUser', 'FrontEndController@saveWbUser')->name('saveWbUser');
        
		Route::get('/gallery/files', 'FrontEndController@allFileCategory')->name('front_allFileCat');
		Route::get('/gallery/files/{category}/{subcategory?}', 'FrontEndController@fileSubcategory')->name('front_fileSubCat');

		Route::get('/gallery/images', 'FrontEndController@allImgGalCats')->name('img_gal_cats');
		Route::get('/gallery/images/{category}/{subcategory?}', 'FrontEndController@galSubcategory')->name('front_galSubCat');

		Route::get('/gallery/videos', 'FrontEndController@allVidGalCats')->name('vid_gal_cats');
		Route::get('/gallery/videos/{category}/{subcategory?}', 'FrontEndController@videoGalSubcategory')->name('front_galVSubCat');
 
		Route::get('/gallery/technical-resources', 'FrontEndController@viewTechnicalResourceList')->name('viewTechResLst');
		Route::post('/gallery/technical-resource/ajx-lists', 'FrontEndController@ajxTechnicalResourceList')->name('ajxTechResLst');
		Route::post('/gallery/technical-resource/ajx-lists-tab', 'FrontEndController@ajxTechnicalResourceTab')->name('ajxTechResTab');
		Route::post('/gallery/technical-resource/ajx-lists-search', 'FrontEndController@ajxTechnicalResourceSrc')->name('ajxTechResSrc');
 
		Route::get('/news-articles', 'FrontEndController@newsArticleLists')->name('newsArticleList');
		Route::get('/news-articles/{slug}', 'FrontEndController@articleContent')->name('front.artCont');
 
		Route::get('/events', 'FrontEndController@eventLists')->name('eventLists');

		//Route::get('/events/{slug}', 'FrontEndController@eventContent')->name('front.evtCont');

		Route::get('/profiles', 'FrontEndController@profileLists')->name('profLists');
		Route::get('/profiles/{slug}', 'FrontEndController@profileContent')->name('front.profCont');
	/*});*/
 

	Route::get('/distributor', 'FrontEndController@distributorMap')->name('front.distrbMap');
	Route::get('/distributor-map-filter', 'FrontEndController@distributorMapFilter')->name('front.distrbMapFilter');
	Route::get('/distributor/{catslug}', 'FrontEndController@distributorCategory')->name('front.distrbCat');
	Route::get('/distributor/{catslug}/{distrbslug}', 'FrontEndController@distributor')->name('front.distrb');
	Route::get('/distributor/{catslug}/{distrbslug}/{contslug}', 'FrontEndController@distributorContent')->name('front.distrbCont');

	/*Route::group(['middleware' => 'CompressHTML'], function() {*/
      	Route::get('/chile', 'FrontEndController@landingPageChile')->name('landing_page_view');
		Route::get('/landing-pages/{slug}', 'FrontEndController@landingPages')->name('landing_page_view');

		/** Preview **/
		Route::get('/preview/{device}/{slug}', 'PreviewController@preview')->name('preview');
		Route::get('/content/preview', 'PreviewController@previewTool')->name('previewTool');
		/** End Preview **/

		/** Global Search **/
		Route::get('/search', 'FrontEndController@globalSearch')->name('globalSearch');
		/** End Global Search **/
	
		Route::get('/{slug}', 'FrontEndController@cmsPage')->name('front_cms_page');
		Route::get('/default-404', 'FrontEndController@notFound')->name('notfound');
	/*});*/


/******************************* END FRONTEND ***************************************/

});

 

Route::group(['prefix' => '/en/admin'], function () {

	

	Route::group(['middleware' => 'IfAdminNotLogIn'], function() {
	
		Route::get('/login', 'DashboardController@login')->name('dashboard_login');
		Route::post('/login', 'DashboardController@loginAction')->name('dashboard_login_action');
		Route::get('/password-reset-link', 'DashboardController@resetLink')->name('reset_link');
		Route::post('/send-reset-link', 'DashboardController@sendLink')->name('send_link');
		Route::get('/reset-password/{token}', 'DashboardController@resetPassword')->name('reset_pwd');
		Route::post('/reset-password/{token}', 'DashboardController@resetPasswordAction')->name('post_reset_pwd');

	}); // end IfAdminNotLogIn middleware


	/********** AFTER ADMIN LOGIN PART **********/
	/********** DASHBOARD ACTION START *********/
	Route::group(['prefix' => 'dashboard',  'middleware' => ['IfAdminLogIn'] ], function () {

		Route::get('/referral-list', 'ProductController@listReferral')->name('list_referral');
		Route::get('/delete/{id}', 'ProductController@deleteReferral')->name('delReferral');
		
		Route::get('/', 'DashboardController@index')->name('dashboard');
		Route::get('/logout', 'DashboardController@logout')->name('logout');

		Route::group(['prefix' => 'settings', 'middleware' => ['role:Super-Admin'] ], function () {

			Route::get('/', 'SettingsController@generalSettings')->name('gen_sett');
			Route::post('/save', 'SettingsController@saveGeneralSettings')->name('sv_gen_sett');
		});

		/*** Global Image Delete ***/
		Route::get('/global-image-delete', 'DashboardController@globalImageDelete')->name('glbImgDel');

		/*** Duplicate Page ***/
		Route::get('/duplicate', 'DuplicateController@createDuplicate')->name('crte.dup');

		/*Route::get('/arix', function() { 
			$data = DB::table('file_categories')->where('status', '!=', '3')->get();
			if(!empty($data)) {
				foreach($data as $v) {
					DB::table('cms_links')->insert(['table_id' => $v->id, 'table_type' => 'FILE_CATEGORY', 'slug_url' => $v->slug]);
				}
			}
		});*/

		Route::group(['prefix' => 'home-content'], function() {

			Route::get('/', 'ContentController@home')->name('home.cont');
			// Route::get('/{pid}', 'ContentController@home')->name('home.cont');
			Route::post('/save', 'ContentController@homeAct')->name('home.contAct');
			Route::get('/mineral-processing', 'ContentController@mineralProcessing')->name('hmps');


			Route::pattern('llid', '[0-9]+');

			Route::get('/{llid}', 'ContentController@home1')->name('home.cont1');

			/*** Mineral Processing ***/
		
			Route::get('/mineral-processing/add', 'ContentController@mineralProcessingAdd')->name('hmps_add');
			Route::post('/mineral-processing/save', 'ContentController@mineralProcessingSave')->name('hmps_sve');
			Route::get('/mineral-processing/edit/{id}', 'ContentController@mineralProcessingEdit')->name('hmps_edt');
			Route::post('/mineral-processing/edit/{id}', 'ContentController@mineralProcessingEdit')->name('hmps_edt');
			Route::post('/mineral-processing/update/{id}', 'ContentController@mineralProcessingUpdate')->name('hmps_upd');
			Route::get('/mineral-processing/delete/{id}', 'ContentController@mineralProcessingDelete')->name('hmps_del');
			Route::post('/mineral-processing/bulk-action', 'ContentController@mineralProcessingBulkAction')->name('mps.blkAct');

			/** Home Map **/
			Route::get('/home-map', 'ContentController@homeMap')->name('home.map');
			Route::post('/home-map-save', 'ContentController@homeMapAct')->name('home.mapAct');


			Route::pattern('mmid', '[0-9]+');

			Route::get('/home-map/{mmid}', 'ContentController@homeMap1')->name('home.map1');


			/*** Minerals ***/
			Route::get('/minerals', 'ContentController@mineral')->name('mina');
			Route::get('/minerals/add', 'ContentController@mineralAdd')->name('mina_add');
			Route::post('/minerals/save', 'ContentController@mineralSave')->name('mina_sve');
			Route::get('/minerals/edit/{id}', 'ContentController@mineralEdit')->name('mina_edt');
			Route::post('/minerals/edit/{id}', 'ContentController@mineralEdit')->name('mina_edt');
			Route::post('/minerals/update/{id}', 'ContentController@mineralUpdate')->name('mina_upd');
			Route::get('/minerals/delete/{id}', 'ContentController@mineralDelete')->name('mina_del');
			Route::post('/minerals/bulk-action', 'ContentController@mineralBulkAction')->name('mina.blkAct');


			/*** Logo ***/
			Route::get('/logos', 'ContentController@logos')->name('home.logo');
			Route::get('/logos/add', 'ContentController@logoslAdd')->name('logo_add');
			Route::post('/logos/save', 'ContentController@logosSave')->name('logo_sve');
			Route::get('/logos/edit/{id}', 'ContentController@logosEdit')->name('logo_edt');
			Route::post('/logos/update/{id}', 'ContentController@logosUpdate')->name('logo_upd');
			Route::get('/logos/delete/{id}', 'ContentController@logosDelete')->name('logo_del');


			/***** LANGUAGE *****/
			Route::get('/add-edit-lng/{pid}/{cid?}', 'ContentController@addEditHomeLanguage')->name('home.adedlng');
			Route::post('/add-edit-lng/{pid}/{cid?}', 'ContentController@addEditHomeLanguagePost')->name('home.adedlngPst');
			Route::get('/delete-lng/{pid}/{cid}', 'ContentController@deleteHomeLanguage')->name('home.adedlngDel');
		});



		/***************** Landing Pages ******************/
		Route::group(['prefix' => 'landing-pages'], function () {

			Route::get('/', 'LandingPagesController@index')->name('land.list');
			Route::get('/new-create', 'LandingPagesController@newCreate')->name('land.new');
			Route::post('/new-create-upload', 'LandingPagesController@newCreateUpload')->name('land.new.upd');
			Route::get('/delete/{id}', 'LandingPagesController@delete')->name('land.del');
			Route::get('/edit/{id}', 'LandingPagesController@edit')->name('land.edt');
			Route::post('/update/{id}', 'LandingPagesController@update')->name('land.update');
		});

		/*************** USER MANAGEMENT ******************/
		Route::group(['prefix' => 'users-management'], function () {

			Route::get('/', 'UserController@index')->name('users_list');
			Route::get('/create-user', 'UserController@createUser')->name('crte_user');
			Route::post('/save-user', 'UserController@saveUser')->name('save_user');
			Route::get('/edit-user/{user_timestamp_id}', 'UserController@editUser')->name('edit_user');
			Route::post('/update-user/{user_timestamp_id}', 'UserController@updateUser')->name('upd_user');
			Route::get('/reset-password/{user_timestamp_id}', 'UserController@resetPassword')->name('rst_pwd');
			Route::post('/update-password/{user_timestamp_id}', 'UserController@updatePassword')->name('upd_pwd');
			Route::get('/delete-user/{user_timestamp_id}', 'UserController@deleteUser')->name('del_usr');
			Route::get('/profile', 'UserController@profile')->name('usr_profile');
			Route::post('/profile', 'UserController@profileUpdate')->name('upd_profile');
			Route::get('/change-password', 'UserController@changePassword')->name('cng_pwd');
			Route::post('/change-password', 'UserController@changePasswordSave')->name('save_pwd');

			
			Route::get('/system-permissions', 'UserController@systemPermissions')->name('sysPerm');
			Route::post('/add-system-permissions', 'UserController@systemPermissions_Add')->name('sysPerm_Add');
			Route::get('/edit-system-permissions/{id}', 'UserController@systemPermissions_Edit')->name('sysPerm_Edt');
			Route::post('/update-system-permissions/{id}', 'UserController@systemPermissions_Update')->name('sysPerm_Upd');
			Route::get('/delete-system-permissions/{id}', 'UserController@systemPermissions_Delete')->name('sysPerm_Del');

			Route::get('/roles', 'UserController@allRoles')->name('allRoles');
			Route::get('/create-role', 'UserController@createRole')->name('crtRole');
			Route::post('/add-role', 'UserController@addRole')->name('addRole');
			Route::get('/manage-role-permissions/{role_id}', 'UserController@manageRolePermission')->name('mngRolePerm');
			Route::post('/update-role/{role_id}', 'UserController@updateRole')->name('updRole');
			Route::get('/delete-role/{role_id}', 'UserController@deleteRole')->name('delRole');

			Route::get('/role-permissions/{role_id}', 'UserController@showRolePermissions')->name('shw.role_Perm');
			Route::get('/delete-permission-from-role/{role_id}/{perm_id}', 'UserController@deletePermFromRole')->name('del.permFrole');

			Route::get('/user-permissions/{user_id}', 'UserController@userPermissions')->name('usr.Perm');
			Route::get('/user-manage-direct-permission', 'UserController@userDirectPermission')->name('usr.dirt.perm');

			//Route::get('/roles-manage-permissions/{role_id}', 'UserController@roleManagePermissions')->name('rlMnPer');
			//Route::post('/save-roles-permissions/{role_id}', 'UserController@saveRolePermissions')->name('sveRlPerm');

			Route::post('/users-action', 'UserController@takeAction')->name('usrTKact');
			
		});



		/*************** SOCILA LINKS ******************/
		Route::group(['prefix' => 'social-links'], function() {

			Route::get('/', 'SettingsController@socialLinksList')->name('social_links');
			Route::get('/add', 'SettingsController@addSocialLink')->name('add_social_link');
			Route::post('/add', 'SettingsController@saveSocialLink')->name('sve_social_link');
			Route::get('/edit/{id}', 'SettingsController@addSocialLink')->name('edit_social_link');
			Route::post('/edit/{id}', 'SettingsController@updateSocialLink')->name('upd_social_link');
			Route::get('/delete/{id}', 'SettingsController@deleteSocialLink')->name('del_social_link');
		});



		/*************** ANALYTICAL SCRIPTS ******************/
		Route::group(['prefix' => 'analytic-scripts', 'middleware' => ['permission:access analytic-scripts'] ], function() {

			Route::get('/', 'SettingsController@anaLyticScripts')->name('anaLyticScripts');
			Route::get('/ajax-layout', 'SettingsController@getAjaxLayout')->name('ajax_layout');
			Route::post('/save-script', 'SettingsController@saveScript')->name('save_script');
			Route::post('/delete-script', 'SettingsController@deleteScript')->name('delete_script');
		});



		/*************** EMAIL TEMPLATES ******************/
		Route::group(['prefix' => 'email-templates'], function() {

			Route::get('/', 'EmailTemplateController@index')->name('empTemp_lists');
			Route::get('/add', 'EmailTemplateController@addEmTemplate')->name('add_empTemp');
			Route::post('/save', 'EmailTemplateController@saveEmTemplate')->name('save_empTemp');
			Route::get('/edit/{id}', 'EmailTemplateController@editEmTemplate')->name('edit_empTemp');
			Route::post('/update/{id}', 'EmailTemplateController@updateEmTemplate')->name('update_empTemp');
			Route::get('/delete/{id}', 'EmailTemplateController@deleteEmTemplate')->name('delete_empTemp');
			Route::get('/settings', 'EmailTemplateController@settings')->name('emp_sett');
			Route::post('/settings', 'EmailTemplateController@settingsSave')->name('emp_sett_save');
		});



		/*************** ARTICLE AND NEWS ******************/
		Route::group(['prefix' => 'articles-news'], function() {

			Route::get('/', 'ArticleController@index')->name('allArts');
			Route::get('/create', 'ArticleController@create')->name('addArt');
			Route::post('/save', 'ArticleController@save')->name('sveArt');
			Route::get('/edit/{id}', 'ArticleController@edit')->name('edtArt');
			Route::post('/edit/{id}', 'ArticleController@edit')->name('edtArt');
			Route::post('/update/{id}', 'ArticleController@update')->name('updArt');
			Route::get('/delete/{id}', 'ArticleController@delete')->name('delArt');

			Route::get('/all-categories', 'ArticleController@allCategories')->name('allArtCats');
			Route::get('/add-category', 'ArticleController@addCategory')->name('addArtCats');
			Route::post('/save-category', 'ArticleController@saveCategory')->name('sveArtCats');
			Route::get('/edit-category/{id}', 'ArticleController@editCategory')->name('edtArtCats');
			Route::post('/edit-category/{id}', 'ArticleController@editCategory')->name('edtArtCats');
			Route::post('/update-category/{id}', 'ArticleController@updateCategory')->name('updArtCats');
			Route::get('/delete-category/{id}', 'ArticleController@deleteCategory')->name('delArtCats');

			Route::post('/bulk-action', 'ArticleController@bulkAction')->name('art.blkAct');
			Route::post('/bulk-action-cat', 'ArticleController@bulkActionCat')->name('artc.blkAct');

			Route::get('/extra-content', 'ArticleController@extraConten')->name('art.extra_cont');
			Route::post('/extra-content-save', 'ArticleController@extraContentSave')->name('art.extra_cont_save');



			/****** LANGUAGE ******/
			Route::get('/add-edit-cat-lng/{pid}/{cid?}', 'ArticleController@addEditCatLanguage')->name('arti.adedcatlng');
			Route::post('/add-edit-cat-lng/{pid}/{cid?}', 'ArticleController@addEditCatLanguagePost')->name('arti.adedcatlngPst');
			Route::get('/delete-cat-lng/{pid}/{cid}', 'ArticleController@deleteCatLanguage')->name('arti.adedcatlngDel');

			Route::get('/add-edit-lng/{pid}/{cid?}', 'ArticleController@addEditLanguage')->name('arti.adedlng');
			Route::post('/add-edit-lng/{pid}/{cid?}', 'ArticleController@addEditLanguagePost')->name('arti.adedlngPst');
			Route::get('/delete-lng/{pid}/{cid}', 'ArticleController@deleteLanguage')->name('arti.adedlngDel');
		});




		/*************** REGIONAL SETTINGS ******************/
		Route::group(['prefix' => 'regional-settings'], function() {

			Route::get('/', 'RegionalSettingsController@index')->name('regio_page');
			Route::group(['prefix' => 'continents'], function() {
				Route::get('/', 'RegionalSettingsController@continentsList')->name('continentsList');
				Route::get('/add', 'RegionalSettingsController@continentsAdd')->name('continentsAdd');
				Route::post('/add', 'RegionalSettingsController@continentsSave')->name('continentsSave');
				Route::get('/edit/{id}', 'RegionalSettingsController@continentsEdit')->name('continentsEdit');
				Route::post('/edit/{id}', 'RegionalSettingsController@continentsUpdate')->name('continentsUpdate');
				Route::get('/delete/{id}', 'RegionalSettingsController@continentsDelete')->name('continentsDelete');
			});
			Route::group(['prefix' => 'region'], function() {
				Route::get('/', 'RegionalSettingsController@regionList')->name('regionList');
				Route::get('/add', 'RegionalSettingsController@regionAdd')->name('regionAdd');
				Route::post('/add', 'RegionalSettingsController@regionSave')->name('regionSave');
				Route::get('/edit/{id}', 'RegionalSettingsController@regionEdit')->name('regionEdit');
				Route::post('/edit/{id}', 'RegionalSettingsController@regionUpdate')->name('regionUpdate');
				Route::get('/delete/{id}', 'RegionalSettingsController@regionDelete')->name('regionDelete');
			});
			Route::group(['prefix' => 'country'], function() {
				Route::get('/', 'RegionalSettingsController@countryList')->name('countryList');
				Route::get('/add', 'RegionalSettingsController@countryAdd')->name('countryAdd');
				Route::post('/add', 'RegionalSettingsController@countrySave')->name('countrySave');
				Route::get('/edit/{id}', 'RegionalSettingsController@countryEdit')->name('countryEdit');
				Route::post('/edit/{id}', 'RegionalSettingsController@countryUpdate')->name('countryUpdate');
				Route::get('/delete/{id}', 'RegionalSettingsController@countryDelete')->name('countryDelete');
			});
			Route::group(['prefix' => 'provinces'], function() {
				Route::get('/', 'RegionalSettingsController@provincesList')->name('provincesList');
				Route::get('/add', 'RegionalSettingsController@provincesAdd')->name('provincesAdd');
				Route::post('/add', 'RegionalSettingsController@provincesSave')->name('provincesSave');
				Route::get('/edit/{id}', 'RegionalSettingsController@provincesEdit')->name('provincesEdit');
				Route::post('/edit/{id}', 'RegionalSettingsController@provincesUpdate')->name('provincesUpdate');
				Route::get('/delete/{id}', 'RegionalSettingsController@provincesDelete')->name('provincesDelete');
			});
			Route::group(['prefix' => 'cities'], function() {
				Route::get('/', 'RegionalSettingsController@cityList')->name('cityList');
				Route::get('/add', 'RegionalSettingsController@cityAdd')->name('cityAdd');
				Route::post('/add', 'RegionalSettingsController@citySave')->name('citySave');
				Route::get('/edit/{id}', 'RegionalSettingsController@cityEdit')->name('cityEdit');
				Route::post('/edit/{id}', 'RegionalSettingsController@cityUpdate')->name('cityUpdate');
				Route::get('/delete/{id}', 'RegionalSettingsController@cityDelete')->name('cityDelete');
			});
		});




		/*************** EVENT MANAGEMENT ******************/
		Route::group(['prefix' => 'events'], function() {

			Route::get('/categories', 'EventController@categories')->name('evt_cats');
			Route::get('/create-category', 'EventController@createCategory')->name('evt_crte_cat');
			Route::post('/create-category', 'EventController@saveCategory')->name('evt_sve_cat');
			Route::get('/edit-category/{id}', 'EventController@editCategory')->name('evt_edt_cat');
			Route::post('/edit-category/{id}', 'EventController@updateCategory')->name('evt_upd_cat');
			Route::get('/delete-category/{id}', 'EventController@deleteCategory')->name('evt_del_cat');

			Route::get('/calander', 'EventController@calView')->name('evts_cal');
			Route::get('/', 'EventController@index')->name('evts_lst');
			Route::get('/create', 'EventController@create')->name('evts_crte');
			Route::post('/create', 'EventController@save')->name('evts_save');
			Route::get('/delete/{id}', 'EventController@delete')->name('evts_del');
			Route::get('/edit/{id}', 'EventController@edit')->name('evts_edt');
			Route::post('/edit/{id}', 'EventController@update')->name('evts_update');

			Route::post('/bulk-action', 'EventController@bulkAction')->name('evt.blkAct');
			Route::post('/bulk-action-cat', 'EventController@bulkActionCat')->name('evtc.blkAct');


			Route::get('/extra-content', 'EventController@extraConten')->name('evt.extra_cont');
			Route::post('/extra-content-save', 'EventController@extraContentSave')->name('evt.extra_cont_save');


			/***** LANGUAGE *****/
			Route::get('/add-edit-cat-lng/{pid}/{cid?}', 'EventController@addEditCatLanguage')->name('evt.adedcatlng');
			Route::post('/add-edit-cat-lng/{pid}/{cid?}', 'EventController@addEditCatLanguagePost')->name('evt.adedcatlngPst');
			Route::get('/delete-cat-lng/{pid}/{cid}', 'EventController@deleteCatLanguage')->name('evt.adedcatlngDel');

			Route::get('/add-edit-lng/{pid}/{cid?}', 'EventController@addEditLanguage')->name('evt.adedlng');
			Route::post('/add-edit-lng/{pid}/{cid?}', 'EventController@addEditLanguagePost')->name('evt.adedlngPst');
			Route::get('/delete-lng/{pid}/{cid}', 'EventController@deleteLanguage')->name('evt.adedlngDel');
		});



		/*************** LANGUAGE MANAGEMENT ******************/
		Route::group(['prefix' => 'language'], function() {
			Route::get('/', 'LanguageController@index')->name('langList');
			Route::get('/add', 'LanguageController@add')->name('langCret');
			Route::post('/add', 'LanguageController@save')->name('langSave');
			Route::get('/edit/{id}', 'LanguageController@edit')->name('langEdit');
			Route::post('/edit/{id}', 'LanguageController@update')->name('langUpdate');
			Route::get('/delete/{id}', 'LanguageController@delete')->name('langDel');
		});




		/*************** BANNER MANAGEMENT ******************/
		Route::group(['prefix' => 'banner-management'], function() {
			Route::get('/', 'BannerController@index')->name('bannList');
			Route::get('/add', 'BannerController@add')->name('addBann');
			Route::post('/save', 'BannerController@save')->name('sveBann');
			Route::get('/delete/{imgid}', 'BannerController@delete')->name('delBann');
		});




		/*************** FORM BUILDER ******************/
		Route::group(['prefix' => 'form-builder'], function() {

			Route::get('/','FormBuilder@forms')->name('frms');
			Route::get('/captcha-settings','FormBuilder@captchaSettings')->name('frm_sett');
			Route::post('/form-save-settings', 'FormBuilder@formSaveSettings')->name('frm_sve_sett');
			Route::get('/create','FormBuilder@createForm')->name('crte_frm');
			Route::get('/create/fields/{form_id}','FormBuilder@createFormFields')->name('crte_frm_flds');
			Route::get('/fields/{form_id}','FormBuilder@showFormFields')->name('edt_frm_flds');
			Route::post('/fields/{form_id}','FormBuilder@showFormFields')->name('edt_frm_flds');
			Route::get('/preview/{form_id}','FormBuilder@formPreview')->name('frm_prv');
			Route::post('/manage-fields','FormBuilder@addFields')->name('mng_flds');
			Route::post('/save', 'FormBuilder@saveForm')->name('sv_frm');
			Route::get('/edit/{form_id}','FormBuilder@editForm')->name('edt_frm');
			Route::post('/edit/{form_id}','FormBuilder@editForm')->name('edt_frm');
			Route::post('/edit-form-save/{form_id}','FormBuilder@editFormSave')->name('edt_frm_sve');
			Route::post('/ajax-edit-form-field', 'FormBuilder@ajxEditModal')->name('ajx_edt_modal');
			Route::post('/ajax-fld-order', 'FormBuilder@ajxFieldOrder')->name('ajx_fld_order');
			Route::post('/ajax-fld-delete', 'FormBuilder@ajxFieldDelete')->name('ajx_fld_del');
			Route::get('/delete/{form_id}', 'FormBuilder@formDelete')->name('frm_del');
			Route::get('/records/{form_id}', 'FormBuilder@showFormData')->name('frm_data');
			Route::get('/records/delete/{record_id}', 'FormBuilder@deleteFormData')->name('frm_del_data');
			Route::get('/categories','FormBuilder@categories')->name('frmCats');
			Route::get('/create/category','FormBuilder@addCategory')->name('frmCats_crte');
			Route::post('/create/category','FormBuilder@saveCategory')->name('frmCats_sve');
			Route::get('/edit/category/{id}','FormBuilder@editCategory')->name('frmCats_edt');
			Route::post('/edit/category/{id}','FormBuilder@updateCategory')->name('frmCats_upd');
			Route::get('/delete/category/{id}','FormBuilder@deleteCategory')->name('frmCats_del');

			Route::get('/export-data/{frmid}/{type}','FormBuilder@exportData')->name('frmExpr');

			Route::post('/bulk-action', 'FormBuilder@bulkAction')->name('frm.blkAct');
			Route::post('/bulk-action-cat', 'FormBuilder@bulkActionCat')->name('frmcat.blkAct');
		});



		/*************** REUSABLE CONTENT ******************/
		Route::group(['prefix' => 'reusable-content'], function() {
			Route::get('/', 'ReusableController@index')->name('rsbC_list');
			Route::get('/create', 'ReusableController@create')->name('rsbC_crte');
			Route::post('/create', 'ReusableController@save')->name('rsbC_sve');
			
			Route::get('/edit/{id}', 'ReusableController@edit')->name('rsbC_edt');
			Route::post('/edit1/{id}', 'ReusableController@edit')->name('rsbC_edt1');

			Route::post('/edit/{id}', 'ReusableController@update')->name('rsbC_upd');
			Route::get('/delete/{id}', 'ReusableController@delete')->name('rsbC_del');

			Route::post('/bulk-action', 'ReusableController@bulkAction')->name('reus.blkAct');

			Route::get('/productbox-contents', 'ReusableController@list')->name('pbox_rlist');
			Route::get('/create-productbox-content', 'ReusableController@pboxCreate')->name('pbox_crte');
			Route::post('/create-productbox-content', 'ReusableController@pboxCreateAction')->name('pbox_crte.post');
			Route::get('/edit-productbox-content/{id}', 'ReusableController@pboxEdit')->name('pbox_edit');
			Route::post('/edit-productbox-content/{id}', 'ReusableController@pboxEditAction')->name('pbox_edit.post');
			Route::get('/delete-productbox-content/{id}', 'ReusableController@pboxDelete')->name('pbox_delete');

			Route::post('/productbox-bulk-action', 'ReusableController@pboxBulkAction')->name('pbox.blkAct');
		});

 
		/*************** CAREER MANAGEMENT ******************/
		Route::group(['prefix' => 'careers'], function() {
			Route::get('/', 'CareerController@index')->name('allJobs');
			Route::get('/create', 'CareerController@addJob')->name('addJob');
			Route::post('/save', 'CareerController@saveJob')->name('sveJob');
			Route::get('/delete/{id}', 'CareerController@deleteJob')->name('delJob');
			Route::get('/edit/{id}', 'CareerController@editJob')->name('edtJob');
			Route::post('/update/{id}', 'CareerController@updateJob')->name('updJob');

			Route::post('/bulk-action', 'CareerController@bulkAction')->name('carr.blkAct');


			/***** LANGUAGE *****/
			Route::get('/add-edit-lng/{pid}/{cid?}', 'CareerController@addEditLanguage')->name('carr.adedlng');
			Route::post('/add-edit-lng/{pid}/{cid?}', 'CareerController@addEditLanguagePost')->name('carr.adedlngPst');
			Route::get('/delete-lng/{pid}/{cid}', 'CareerController@deleteLanguage')->name('carr.adedlngDel');
		});



		/*************** INDUSTRY MANAGEMENT ******************/
		Route::group(['prefix' => 'industries'], function() {
			Route::get('/', 'IndustryController@index')->name('allIndus');
			Route::get('/create', 'IndustryController@addIndustry')->name('addIndus');
			Route::post('/save', 'IndustryController@saveIndustry')->name('sveIndus');
			Route::get('/edit/{id}', 'IndustryController@editIndustry')->name('edtIndus');
			Route::post('/edit/{id}', 'IndustryController@editIndustry')->name('edtIndus');
			Route::post('/update/{id}', 'IndustryController@updateIndustry')->name('updIndus');
			Route::get('/delete/{id}', 'IndustryController@deleteIndustry')->name('delIndus');

			Route::post('/bulk-action', 'IndustryController@bulkAction')->name('indus.blkAct');

			/***** LANGUAGE *****/
			Route::get('/add-edit-lng/{pid}/{cid?}', 'IndustryController@addEditLanguage')->name('indus.adedlng');
			Route::post('/add-edit-lng/{pid}/{cid?}', 'IndustryController@addEditLanguagePost')->name('indus.adedlngPst');
			Route::get('/delete-lng/{pid}/{cid}', 'IndustryController@deleteLanguage')->name('indus.adedlngDel');
		});



		/*************** INDUSTRY FLOWSHEET MANAGEMENT ******************/
		Route::group(['prefix' => 'industry-flowsheet'], function() {
			Route::get('/categories', 'IndustryFsController@allCategories')->name('allFSc');
			Route::get('/create-category', 'IndustryFsController@addCategory')->name('addFSc');
			Route::post('/save-category', 'IndustryFsController@saveCategory')->name('sveFSc');
			Route::get('/edit-category/{id}', 'IndustryFsController@editCategory')->name('edtFSc');
			Route::post('/edit-category/{id}', 'IndustryFsController@editCategory')->name('edtFSc');
			Route::post('/update-category/{id}', 'IndustryFsController@updateCategory')->name('updFSc');
			Route::get('/delete-category/{id}', 'IndustryFsController@deleteCategory')->name('delFSc');

			Route::get('/', 'IndustryFsController@index')->name('allFSs');
			Route::get('/create', 'IndustryFsController@create')->name('crteFS');
			Route::post('/save', 'IndustryFsController@save')->name('sveFS');
			Route::get('/edit/{id}', 'IndustryFsController@edit')->name('editFS');
			Route::post('/edit/{id}', 'IndustryFsController@edit')->name('editFS');
			Route::post('/update/{id}', 'IndustryFsController@update')->name('updFS');
			Route::get('/delete/{id}', 'IndustryFsController@delete')->name('delFS');

			Route::get('/add-edit-marker/{id}', 'IndustryFsController@addeditMarker')->name('adedMark');
			Route::post('/add-edit-marker1/{id}', 'IndustryFsController@addeditMarker1')->name('adedMark1');
			Route::post('/add-edit-marker/{id}', 'IndustryFsController@addeditMarkerSave')->name('adedMarkSV');
			Route::post('/get-marker-info', 'IndustryFsController@getMarkerInfo')->name('getMkInfo');
			Route::post('/delete-marker-info', 'IndustryFsController@delMarkerInfo')->name('delMkInfo');
			Route::post('/delete-marker-image', 'IndustryFsController@delMarkerImage')->name('delMkImg');


			Route::post('/bulk-action', 'IndustryFsController@bulkAction')->name('ifs.blkAct');
			Route::post('/bulk-action-cat', 'IndustryFsController@bulkActionCat')->name('ifsc.blkAct');


			/***** LANGUAGE *****/
			Route::get('/add-edit-cat-lng/{pid}/{cid?}', 'IndustryFsController@addEditCatLanguage')->name('indfs.adedcatlng');
			Route::post('/add-edit-cat-lng/{pid}/{cid?}', 'IndustryFsController@addEditCatLanguagePost')->name('indfs.adedcatlngPst');
			Route::get('/delete-cat-lng/{pid}/{cid}', 'IndustryFsController@deleteCatLanguage')->name('indfs.adedcatlngDel');

			Route::get('/add-edit-lng/{pid}/{cid?}', 'IndustryFsController@addEditLanguage')->name('indfs.adedlng');
			Route::post('/add-edit-lng/{pid}/{cid?}', 'IndustryFsController@addEditLanguagePost')->name('indfs.adedlngPst');
			Route::get('/delete-lng/{pid}/{cid}', 'IndustryFsController@deleteLanguage')->name('indfs.adedlngDel');
		});



		/*************** CONTENT MANAGEMENT ******************/
		Route::group(['prefix' => 'contents'], function() {
			
			Route::get('/', 'ContentController@allContentTypes')->name('allContTyps');
			Route::get('/add-type', 'ContentController@addContentType')->name('addContTyp');
			Route::post('/save-type', 'ContentController@saveContentType')->name('sveContTyp');
			Route::get('/edit-type/{id}', 'ContentController@editContentType')->name('edtContTyp');
			Route::post('/update-type/{id}', 'ContentController@updateContentType')->name('updContTyp');
			Route::get('/delete-type/{id}', 'ContentController@deleteContentType')->name('delContTyp');

			Route::get('/all-types', 'ContentController@allTypesList')->name('typeList');
			Route::get('/manage/{type}/{type_id}', 'ContentController@manageList')->name('mngLists');
			Route::get('/add/{type}/{type_id}', 'ContentController@addContent')->name('addDynaCont');
			Route::post('/save/{type}/{type_id}', 'ContentController@saveContent')->name('sveDynaCont');
			Route::post('/edit/{type}/{type_id}/{id}', 'ContentController@editContent')->name('edtDynaCont');
			Route::get('/edit/{type}/{type_id}/{id}', 'ContentController@editContent')->name('edtDynaCont');
			Route::post('/update/{type}/{type_id}/{id}', 'ContentController@updateContent')->name('updDynaCont');
			Route::get('/delete/{type}/{type_id}/{id}', 'ContentController@deleteContent')->name('delDynaCont');

			Route::post('/bulk-action', 'ContentController@bulkAction')->name('cont.blkAct');


			/***** LANGUAGE *****/
			Route::get('/add-edit-language/{type}/{type_id}/{pid}/{cid?}', 'ContentController@addeditLngContent')
			->name('addedtLngDynaCont');
			Route::post('/add-edit-language/{type}/{type_id}/{pid}/{cid?}', 'ContentController@addeditLngContentPost')
			->name('addedtLngDynaCont_post');
			Route::get('/delete-language-content/{type}/{type_id}/{pid}/{cid}', 'ContentController@deleteLngContent')
			->name('delLngDynaCont');
		});



		/*************** PEOPLE PROFILE MANAGEMENT ******************/
		Route::group(['prefix' => 'people-profile'], function() {
			Route::get('/', 'ProfileController@index')->name('allProfiles');
			Route::get('/add', 'ProfileController@addProfile')->name('addProfile');
			Route::post('/save', 'ProfileController@saveProfile')->name('sveProfile');
			Route::get('/edit/{id}', 'ProfileController@editProfile')->name('edtProfile');
			Route::post('/update/{id}', 'ProfileController@updateProfile')->name('updProfile');
			Route::get('/delete/{id}', 'ProfileController@deleteProfile')->name('delProfile');

			Route::get('/categories', 'ProfileController@allCategories')->name('allProfileCats');
			Route::get('/create-category', 'ProfileController@addCategory')->name('addProfileCat');
			Route::post('/save-category', 'ProfileController@saveCategory')->name('sveProfileCat');
			Route::get('/edit-category/{id}', 'ProfileController@editCategory')->name('edtProfileCat');
			Route::post('/update-category/{id}', 'ProfileController@updateCategory')->name('updProfileCat');
			Route::get('/delete-category/{id}', 'ProfileController@deleteCategory')->name('delProfileCat');

			Route::post('/bulk-action', 'ProfileController@bulkAction')->name('pp.blkAct');
			Route::post('/bulk-action-cat', 'ProfileController@bulkActionCat')->name('ppc.blkAct');


			Route::get('/extra-content', 'ProfileController@extraConten')->name('prof.extra_cont');
			Route::post('/extra-content-save', 'ProfileController@extraContentSave')->name('prof.extra_cont_save');

			/***** LANGUAGE ******/
			Route::get('/add-edit-lng/{pid}/{cid?}', 'ProfileController@addEditLngProfile')->name('adedLnProfile');
			Route::post('/add-edit-lng/{pid}/{cid?}', 'ProfileController@addEditLngProfilePost')->name('adedLnProfilePst');
			Route::get('/delete-lng-content/{pid}/{cid}', 'ProfileController@deleteLngCont')->name('delLngCont');

			Route::get('/add-edit-cat-lng/{pid}/{cid?}', 'ProfileController@addEditLngProfileCat')->name('adedLnProfCat');
			Route::post('/add-edit-cat-lng/{pid}/{cid?}', 'ProfileController@addEditLngProfileCatPost')->name('adedLnProfCatPst');
			Route::get('/delete-lng-cat-cont/{pid}/{cid}', 'ProfileController@deleteLngCatCont')->name('delLngCatCont');
		});




		/*************** TECH RESOURCE MANAGEMENT ******************/
		Route::group(['prefix' => 'technical-resources'], function() {
			Route::get('/', 'TechResourceController@index')->name('allResource');
			Route::get('/add', 'TechResourceController@add')->name('addResource');
			Route::post('/save', 'TechResourceController@save')->name('sveResource');
			Route::get('/edit/{id}', 'TechResourceController@edit')->name('editResource');
			Route::post('/update/{id}', 'TechResourceController@update')->name('updResource');
			Route::get('/delete/{id}', 'TechResourceController@delete')->name('delResource');

			Route::get('/personaa', 'TechResourceController@allPersonas')->name('allPersonas');
			Route::get('/add-persona', 'TechResourceController@addPersona')->name('addPersona');
			Route::post('/save-persona', 'TechResourceController@savePersona')->name('svePersona');
			Route::get('/edit-persona/{id}', 'TechResourceController@editPersona')->name('editPersona');
			Route::post('/update-persona/{id}', 'TechResourceController@updatePersona')->name('updatePersona');
			Route::get('/delete-persona/{id}', 'TechResourceController@deletePersona')->name('delPersona');

			Route::post('/bulk-action', 'TechResourceController@bulkAction')->name('tecr.blkAct');
			Route::post('/bulk-action-cat', 'TechResourceController@bulkActionCat')->name('tecrp.blkAct');


			Route::get('/extra-content', 'TechResourceController@extraConten')->name('tres.extra_cont');
			Route::post('/extra-content-save', 'TechResourceController@extraContentSave')->name('tres.extra_cont_save');


			/****** LANGUAGE ******/
			Route::get('/add-edit-cat-lng/{pid}/{cid?}', 'TechResourceController@addEditCatLanguage')->name('techr.adedcatlng');
			Route::post('/add-edit-cat-lng/{pid}/{cid?}', 'TechResourceController@addEditCatLanguagePost')->name('techr.adedcatlngPst');
			Route::get('/delete-cat-lng/{pid}/{cid}', 'TechResourceController@deleteCatLanguage')->name('techr.adedcatlngDel');

			Route::get('/add-edit-lng/{pid}/{cid?}', 'TechResourceController@addEditLanguage')->name('techr.adedlng');
			Route::post('/add-edit-lng/{pid}/{cid?}', 'TechResourceController@addEditLanguagePost')->name('techr.adedlngPst');
			Route::get('/delete-lng/{pid}/{cid}', 'TechResourceController@deleteLanguage')->name('techr.adedlngDel');
		});

		/*************** WEBINAR TOPIC MANAGEMENT ******************/
		Route::group(['prefix' => 'webinar-topic'], function() {
			Route::get('/', 'WebinarTopicController@allWebinarTopics')->name('allWbTp');
			Route::get('/add', 'WebinarTopicController@addWebinarTopic')->name('addWbTp');
			Route::post('/save', 'WebinarTopicController@saveWebinarTopic')->name('saveWbTp');
			Route::get('/delete/{id}', 'WebinarTopicController@deleteWebinarTopic')->name('delWbTp');
			Route::get('/edit/{id}', 'WebinarTopicController@editWebinarTopic')->name('editWbTp');
			Route::post('/edit/{id}', 'WebinarTopicController@editWebinarTopic')->name('editWbTp');
			Route::post('/update/{id}', 'WebinarTopicController@updateWebinarTopic')->name('updateWbTp');

		});

		/*************** WEBINAR INDUSTRY MANAGEMENT ******************/
		Route::group(['prefix' => 'webinar-industry'], function() {
			Route::get('/', 'WebinarIndustryController@allWebinarIndustry')->name('allWbId');
			Route::get('/add', 'WebinarIndustryController@addWebinarIndustry')->name('addWbId');
			Route::post('/save', 'WebinarIndustryController@saveWebinarIndustry')->name('saveWbId');
			Route::get('/delete/{id}', 'WebinarIndustryController@deleteWebinarIndustry')->name('delWbId');
			Route::get('/edit/{id}', 'WebinarIndustryController@editWebinarIndustry')->name('editWbId');
			Route::post('/edit/{id}', 'WebinarIndustryController@editWebinarIndustry')->name('editWbId');
			Route::post('/update/{id}', 'WebinarIndustryController@updateWebinarIndustry')->name('updateWbId');

		});

		/*************** WEBINAR CATEGORY MANAGEMENT ******************/
		Route::group(['prefix' => 'webinar-category'], function() {
			Route::get('/', 'WebinarCategoryController@allWebinarCategorys')->name('allWbCt');
			Route::get('/add', 'WebinarCategoryController@addWebinarCategory')->name('addWbCt');
			Route::post('/save', 'WebinarCategoryController@saveWebinarCategory')->name('saveWbCt');
			Route::get('/delete/{id}', 'WebinarCategoryController@deleteWebinarCategory')->name('delWbCt');
			Route::get('/edit/{id}', 'WebinarCategoryController@editWebinarCategory')->name('editWbCt');
			Route::post('/edit/{id}', 'WebinarCategoryController@editWebinarCategory')->name('editWbCt');
			Route::post('/update/{id}', 'WebinarCategoryController@updateWebinarCategory')->name('updateWbCt');

		});


		/*************** WEBINAR MANAGEMENT ******************/
		Route::group(['prefix' => 'webinar-management'], function() {
			Route::get('/', 'WebinarController@allWebinars')->name('allWb');
			Route::get('/ajax-webinar-list', 'WebinarController@ajaxWebinars')->name('ajaxWebinars');
			Route::get('/ajax-webinar-list-refresh', 'WebinarController@ajaxRefreshWebinars')->name('ajaxRefreshWebinars');
			Route::get('/add', 'WebinarController@addWebinar')->name('addWb');
			Route::post('/save', 'WebinarController@saveWebinar')->name('saveWb');
			Route::get('/delete/{id}', 'WebinarController@deleteWebinar')->name('delWb');
			Route::get('/delWbUser/{id}', 'WebinarController@delWbUser')->name('delWbUser');
			Route::get('/edit/{id}', 'WebinarController@editWebinar')->name('editWb');
			Route::post('/edit/{id}', 'WebinarController@editWebinar')->name('editWb');
			Route::post('/update/{id}', 'WebinarController@updateWebinar')->name('updateWb');
			Route::post('/updateWbContent/{id}', 'WebinarController@updateWbContent')->name('updateWbContent');
			 
			Route::get('/viewWbUser/{id}', 'WebinarController@viewWbUser')->name('viewWbUser');
			Route::post('/WbWebinarblkAction', 'WebinarController@WbWebinarblkAction')->name('WbWebinarblkAction');
			Route::get('/ajax-viewWbUser-list/{id}', 'WebinarController@ajaxViewWbUser')->name('ajaxViewWbUser');
			Route::get('/ajax-viewWbUser-list-refresh/{id}', 'WebinarController@ajaxRefreshViewWbUser')->name('ajaxRefreshViewWbUser');

			Route::get('/allWbContent', 'WebinarController@allWbContent')->name('allWbContent');
			Route::post('/download-webinar-user', 'WebinarController@downloadWebinarUsers')->name('webiDWN');
			  
		});
 

		/********Event Management *********/

		
			Route::group(['prefix' => 'event-management'], function() {
				Route::get('/add', 'EventManagementController@addEvent')->name('addEventManagement');
				Route::post('/save', 'EventManagementController@saveEvent')->name('saveEventManagement');
				Route::post('/update/{id}', 'EventManagementController@updateEvent')->name('updateEventManagement');
				Route::get('/', 'EventManagementController@index')->name('alleventManagement');
				Route::get('/ajax-event-list', 'EventManagementController@ajaxEvents')->name('ajaxEvents');
				Route::get('/ajax-event-list-refresh', 'EventManagementController@ajaxRefreshEvents')->name('ajaxRefreshEvents');
				Route::post('/download-event-user', 'EventManagementController@downloadEventUsers')->name('downloadEventUsers');
				Route::get('/edit/{id}', 'EventManagementController@editEvent')->name('editEvent');
				Route::get('/delete/{id}', 'EventManagementController@deleteEvent')->name('deleteEvent');
				
				  
			});

					/********Event Management Type *********/

					
					Route::group(['prefix' => 'event-management-type'], function() {
						Route::get('/add', 'EventManagementController@addEventManagementType')->name('addEventManagementType');
						Route::post('/save', 'EventManagementController@addEventType')->name('addEventType');
						Route::post('/update/{id}', 'EventManagementController@updateEventType')->name('updateEventType');
						Route::get('/edit/{id}', 'EventManagementController@editEventType')->name('editEventType');
					
						Route::get('/', 'EventManagementController@allEventManagementType')->name('allEventManagementType');
						Route::get('/acInacEventType', 'EventManagementController@acInacEventType')->name('acInacEventType');
					
						
						  
					});
		

		/*************** SOURCE TYPE MANAGEMENT ******************/
		Route::group(['prefix' => 'source-management'], function() {
			Route::get('/', 'SourceTypeController@allSourceTypes')->name('allSrc');
			Route::get('/add', 'SourceTypeController@addSourceType')->name('addSrc');
			Route::post('/save', 'SourceTypeController@saveSourceType')->name('saveSrc');
			Route::get('/delete/{id}', 'SourceTypeController@deleteSourceType')->name('delSrc');
			Route::get('/edit/{id}', 'SourceTypeController@editSourceType')->name('editSrc');
			Route::post('/edit/{id}', 'SourceTypeController@editSourceType')->name('editSrc');
			Route::post('/update/{id}', 'SourceTypeController@updateSourceType')->name('updateSrc');

		});


			/*************** Campaign  MANAGEMENT ******************/
		Route::group(['prefix' => 'campaign-management'], function() {
			Route::get('/', 'CampaignController@allCampaigns')->name('allCp');
			Route::get('/add', 'CampaignController@addCampaign')->name('addCp');
			Route::post('/save', 'CampaignController@saveCampaign')->name('saveCp');
			Route::get('/delete/{id}', 'CampaignController@deleteCampaign')->name('delCp');
			Route::get('/edit/{id}', 'CampaignController@editCampaign')->name('editCp');
			Route::post('/edit/{id}', 'CampaignController@editCampaign')->name('editCp');
			Route::post('/update/{id}', 'CampaignController@updateCampaign')->name('updateCp');

			Route::get('/getReferral/{url}', 'CampaignController@getReferral')->name('getReferral');
			Route::post('/download-refarral', 'CampaignController@downloadReferral')->name('reffDWN');


		});
 
 
		/*************** PRODUCT MANAGEMENT ******************/
		Route::group(['prefix' => 'product-management'], function() {
			Route::get('/', 'ProductController@allProducts')->name('allProds');
			Route::get('/add', 'ProductController@addProduct')->name('addProd');
			Route::post('/save', 'ProductController@saveProduct')->name('saveProd');
			Route::get('/delete/{id}', 'ProductController@deleteProduct')->name('delProd');
			Route::get('/edit/{id}', 'ProductController@editProduct')->name('editProd');
			Route::post('/edit/{id}', 'ProductController@editProduct')->name('editProd');
			Route::post('/update/{id}', 'ProductController@updateProduct')->name('updateProd');


			

			Route::get('/categories', 'ProductController@allCategories')->name('prodCats');
			Route::get('/create-category', 'ProductController@createCategory')->name('prodCrteCat');
			Route::post('/save-category', 'ProductController@saveCategory')->name('prodSveCat');
			Route::get('/delete-category/{id}', 'ProductController@deleteCategory')->name('prodCatsDel');
			Route::get('/edit-category/{id}', 'ProductController@editCategory')->name('prodCatEdt');
			Route::post('/edit-category/{id}', 'ProductController@editCategory')->name('prodCatEdt');
			Route::post('/update-category/{id}', 'ProductController@updateCategory')->name('prodCatUpd');

			Route::get('/download-products/{type}', 'ProductController@downloadProducts')->name('proDWN');

			Route::post('/bulk-action', 'ProductController@bulkAction')->name('prod.blkAct');
			Route::post('/bulk-action-cat', 'ProductController@bulkActionCat')->name('prodcat.blkAct');


			/***** LANGUAGE *****/
			Route::get('/add-edit-lng/{pid}/{cid?}', 'ProductController@addEditLanguage')->name('prod.adedlng');
			Route::post('/add-edit-lng/{pid}/{cid?}', 'ProductController@addEditLanguagePost')->name('prod.adedlngPst');
			Route::get('/delete-lng/{pid}/{cid}', 'ProductController@deleteLanguage')->name('prod.adedlngDel');

			Route::get('/add-edit-cat-lng/{pid}/{cid?}', 'ProductController@addEditCatLanguage')->name('prod.adedcatlng');
			Route::post('/add-edit-cat-lng/{pid}/{cid?}', 'ProductController@addEditCatLanguagePost')->name('prod.adedcatlngPst');
			Route::get('/delete-cat-lng/{pid}/{cid}', 'ProductController@deleteCatLanguage')->name('prod.adedcatlngDel');
		});




		/*************** DISTRIBUTOR MANAGEMENT ******************/
		Route::group(['prefix' => 'distributor-management'], function() {
			Route::get('/', 'DistributorController@allDistributors')->name('allDistrib');
			Route::get('/create', 'DistributorController@createDistributors')->name('crteDistrib');
			Route::post('/save', 'DistributorController@saveDistributors')->name('sveDistrib');
			Route::get('/delete/{id}', 'DistributorController@deleteDistributors')->name('delDistrib');
			Route::get('/edit/{id}', 'DistributorController@editDistributors')->name('edtDistrib');
			Route::post('/edit/{id}', 'DistributorController@editDistributors')->name('edtDistrib');
			Route::post('/update/{id}', 'DistributorController@updateDistributors')->name('updDistrib');

			Route::get('/categories', 'DistributorController@allCats')->name('allDistribCats');
			Route::get('/create-category', 'DistributorController@createCats')->name('crteDistribCats');
			Route::post('/save-category', 'DistributorController@saveCats')->name('sveDistribCats');
			Route::get('/delete-category/{id}', 'DistributorController@deleteCats')->name('delDistribCats');
			Route::get('/edit-category/{id}', 'DistributorController@editCats')->name('editDistribCats');
			Route::post('/update-category/{id}', 'DistributorController@updateCats')->name('updateDistribCats');

			Route::get('/contents', 'DistributorController@allContents')->name('allDistribConts');
			Route::get('/create-contents', 'DistributorController@createContent')->name('crteDistribCont');
			Route::post('/save-content', 'DistributorController@saveContent')->name('sveDistribCont');
			Route::get('/edit-content/{id}', 'DistributorController@editContent')->name('edtDistribCont');
			Route::post('/edit-content/{id}', 'DistributorController@editContent')->name('edtDistribCont');
			Route::post('/update-content/{id}', 'DistributorController@updateContent')->name('updDistribCont');
			Route::get('/delete-content/{id}', 'DistributorController@deleteContent')->name('delDistribCont');

			Route::get('/extra-content', 'DistributorController@extraConten')->name('dis.extra_cont');
			Route::post('/extra-content-save', 'DistributorController@extraContentSave')->name('dis.extra_cont_save');


			Route::post('/bulk-action', 'DistributorController@bulkAction')->name('distr.blkAct');
			Route::post('/bulk-action-cat', 'DistributorController@bulkActionCat')->name('distrcat.blkAct');
			Route::post('/bulk-action-cont', 'DistributorController@bulkActionCont')->name('distrcon.blkAct');
			Route::post('/bulk-action-loc', 'DistributorController@bulkActionLoc')->name('distr.loc.blkAct');


			Route::get('/add-location', 'DistributorController@addLocation')->name('distr.addloc');
			Route::post('/post-add-location', 'DistributorController@addLocationAction')->name('distr.addloc.save');
			Route::get('/all-locations', 'DistributorController@allLocations')->name('distr.allloc');
			Route::get('/delete-location/{id}', 'DistributorController@deleteLocation')->name('distr.delloc');
			Route::get('/edit-location/{id}', 'DistributorController@editLocation')->name('distr.editloc');
			Route::post('/update-location/{id}', 'DistributorController@updateLocation')->name('distr.updloc');


			/***** LANGUAGE *****/
			Route::get('/add-edit-cat-lng/{pid}/{cid?}', 'DistributorController@addEditCatLanguage')->name('distrb.adedcatlng');
			Route::post('/add-edit-cat-lng/{pid}/{cid?}', 'DistributorController@addEditCatLanguagePost')->name('distrb.adedcatlngPst');
			Route::get('/delete-cat-lng/{pid}/{cid}', 'DistributorController@deleteCatLanguage')->name('distrb.adedcatlngDel');

			Route::get('/add-edit-cont-lng/{pid}/{cid?}', 'DistributorController@addEditContLanguage')->name('distrb.adedcontlng');
			Route::post('/add-edit-cont-lng/{pid}/{cid?}', 'DistributorController@addEditContLanguagePost')->name('distrb.adedcontlngPst');
			Route::get('/delete-cont-lng/{pid}/{cid}', 'DistributorController@deleteContLanguage')->name('distrb.adedcontlngDel');

			Route::get('/add-edit-lng/{pid}/{cid?}', 'DistributorController@addEditLanguage')->name('distrb.adedlng');
			Route::post('/add-edit-lng/{pid}/{cid?}', 'DistributorController@addEditLanguagePost')->name('distrb.adedlngPst');
			Route::get('/delete-lng/{pid}/{cid}', 'DistributorController@deleteLanguage')->name('distrb.adedlngDel');
		});




		/*************** MENU MANAGEMENT ******************/
		Route::group(['prefix' => 'navigation'], function() {

			Route::get('/', 'MenuController@index')->name('NaviMan');
			Route::get('/menu', 'MenuController@allMenus')->name('allMnus');
			Route::post('/ajax-get-pages', 'MenuController@getPages')->name('getPages');
			Route::post('/ajax-set-pages', 'MenuController@setPages')->name('setPages');
			Route::post('/ajax-get-menu', 'MenuController@getMenu')->name('getMenu');
			Route::post('/ajax-save-menu', 'MenuController@saveMenu')->name('saveMenu');
			Route::post('/ajax-delete-menu', 'MenuController@deleteMenu')->name('delMenu');
			Route::post('/ajax-add-extlink', 'MenuController@addLink')->name('addLink');
			Route::post('/ajax-search-page', 'MenuController@searchPage')->name('srcPage');
			Route::post('/ajax-get-pagebody', 'MenuController@getPageBody')->name('getPgBd');
			Route::post('/ajax-save-pagebody', 'MenuController@savePageBody')->name('svePgBd');
			Route::post('/ajax-delete-pagebody', 'MenuController@deletePageBody')->name('delPgBd');
			Route::post('/ajax-add-listpage', 'MenuController@addListPage')->name('addListPage');
		});



 


		/*************** MEDIA MANAGEMENT ******************/
		Route::group(['prefix' => 'media-library'], function() {

			Route::group(['prefix' => 'images'], function() {
				Route::get('/', 'MediaController@all_images')->name('media_all_imgs');
				Route::get('/add', 'MediaController@add')->name('media_img_add');
				Route::post('/add', 'MediaController@upload')->name('media_img_upload');
				Route::get('/details/{id}', 'MediaController@imgDetails')->name('media_img_detl');
				Route::post('/details/{id}', 'MediaController@imgDetails')->name('media_img_detl1');
				Route::post('/details/{id}', 'MediaController@imgDetailsUpdate')->name('media_img_Upd');
				Route::get('/delete/{id}', 'MediaController@imgDelete')->name('media_img_del');
				Route::post('/multi-delete', 'MediaController@imgMultiDelete')->name('media_img_multidel');
				Route::post('/ajax-media-img-delete', 'MediaController@ajaxImgDelete')->name('media_img_ajxDel');

				Route::get('/categories', 'MediaController@img_categories')->name('media_all_img_cats');
				Route::get('/categories/create', 'MediaController@imgCat_Create')->name('media_img_cats_crte');
				Route::post('/categories/create', 'MediaController@imgCat_Save')->name('media_img_cats_save');
				Route::get('/categories/edit/{id}', 'MediaController@imgCat_Edit')->name('media_img_cats_edt');
				Route::post('/categories/edit/{id}', 'MediaController@imgCat_Update')->name('media_img_cats_upd');
				Route::get('/categories/delete/{id}', 'MediaController@imgCat_Delete')->name('media_img_cats_del');
				Route::get('/categories/add-image/{id}', 'MediaController@img_categories_addImg')->name('media_all_img_cats_addImg');
				Route::post('/categories/add-image/{id}', 'MediaController@img_categories_upImg')->name('media_all_img_cats_upImg');

				Route::get('/galleries', 'MediaController@img_galleries')->name('media_all_img_gals');
				Route::get('/galleries/create', 'MediaController@imgGal_create')->name('media_img_gals_crte');
				Route::post('/galleries/create', 'MediaController@imgGal_save')->name('media_img_gals_sve');
				Route::get('/galleries/edit/{gallery_id}', 'MediaController@imgGal_edit')->name('media_img_gals_edit');
				Route::post('/galleries/edit/{gallery_id}', 'MediaController@imgGal_update')->name('media_img_gals_upd');
				Route::get('/galleries/delete/{gallery_id}', 'MediaController@imgGal_delete')->name('media_img_gals_del');
				
				Route::get('/galleries/add-images/{gallery_id}', 'MediaController@imgGal_addImg')->name('media_img_gals_addImg');
				Route::post('/galleries/add-images/{gallery_id}', 'MediaController@imgGal_SaveImg')->name('media_img_gals_sveImg');
				Route::get('/galleries/delete-images/{gallery_id}/{gallery_map_id}', 'MediaController@imgGal_delImg')
				->name('media_img_gals_delImg');

				Route::get('/extra-content', 'MediaController@extraContentImg')->name('media.img_extra_cont');
				Route::post('/extra-content-save', 'MediaController@extraContentImgSave')->name('media.img_extra_cont_save');
			});


			Route::group(['prefix' => 'videos'], function() {
				Route::get('/', 'MediaController@all_videos')->name('allVideos');
				Route::get('/add', 'MediaController@add_video')->name('addVideo');
				Route::post('/save', 'MediaController@save_video')->name('saveVideo');
				Route::get('/delete/{id}', 'MediaController@del_video')->name('delVideo');
				Route::get('/edit/{id}', 'MediaController@edit_video')->name('editVideo');
				Route::post('/edit1/{id}', 'MediaController@edit_video')->name('editVideo1');
				Route::post('/edit/{id}', 'MediaController@update_video')->name('updVideo');

				Route::get('/categories', 'MediaController@all_videoCats')->name('videoCats');
				Route::get('/create-category', 'MediaController@add_videoCats')->name('addVideoCats');
				Route::post('/save-category', 'MediaController@save_videoCats')->name('sveVideoCats');
				Route::get('/delete-category/{id}', 'MediaController@del_videoCats')->name('delVideoCats');
				Route::get('/edit-category/{id}', 'MediaController@edit_videoCats')->name('edtVideoCats');
				Route::post('/update-category/{id}', 'MediaController@update_videoCats')->name('updVideoCats');

				Route::get('/extra-content', 'MediaController@extraContentVid')->name('media.vid_extra_cont');
				Route::post('/extra-content-save', 'MediaController@extraContentVidSave')->name('media.vid_extra_cont_save');
			});


			Route::group(['prefix' => 'files'], function() {
				Route::get('/', 'MediaController@all_files')->name('allFiles');
				Route::get('/add', 'MediaController@add_file')->name('addFile');
				Route::post('/upload', 'MediaController@upload_file')->name('uploadFile');
				Route::get('/delete/{id}', 'MediaController@delete_file')->name('delFile');
				Route::get('/edit/{id}', 'MediaController@edit_file')->name('edtFile');
				Route::post('/update/{id}', 'MediaController@update_file')->name('updFile');
				Route::post('/multi-delete', 'MediaController@fileMultiDelete')->name('media_file_multidel');

				Route::get('/categories', 'MediaController@all_flCats')->name('allFlCats');
				Route::get('/create-category', 'MediaController@create_flCat')->name('crteFlCat');
				Route::post('/save-category', 'MediaController@save_flCat')->name('saveFlCat');
				Route::get('/delete-category/{id}', 'MediaController@delete_flCat')->name('delFlCat');
				Route::get('/edit-category/{id}', 'MediaController@edit_flCat')->name('editFlCat');
				Route::post('/update-category/{id}', 'MediaController@update_flCat')->name('updFlCat');

				Route::get('/file-data-delete', 'MediaController@fileDataDelete')->name('flDD');

				Route::get('/extra-content', 'MediaController@extraContentFil')->name('media.fil_extra_cont');
				Route::post('/extra-content-save', 'MediaController@extraContentFilSave')->name('media.fil_extra_cont_save');
			});
		});
		

		/*************** DATABASE BACKUP MANAGEMENT ******************/
		Route::group(['prefix' => 'database'], function() {
			
			Route::get('/backups', 'DbBackUpController@index')->name('dbbacks');
			Route::post('/create-backup', 'DbBackUpController@create')->name('crte_dbbacks');
			Route::post('/backup-delete', 'DbBackUpController@delete')->name('dele_dbbacks');
			Route::get('/delete-all', 'DbBackUpController@deleteAll')->name('dele_alldbbacks');

		} );


		Route::group(['prefix' => 'page-redirection'], function() {
			Route::get('/404-redirection', 'RedirectionController@redir404')->name('r404');
			Route::post('/404-redirection-save', 'RedirectionController@redir404Save')->name('r404.save');

			Route::get('/301/', 'RedirectionController@redir301')->name('r301');
			Route::get('/301/create', 'RedirectionController@redir301Add')->name('r301.add');
			Route::post('/301/save', 'RedirectionController@redir301save')->name('r301.save');
			Route::get('/301/edit/{id}', 'RedirectionController@redir301Edit')->name('r301.edit');
			Route::post('/301/update/{id}', 'RedirectionController@redir301Update')->name('r301.upd');
			Route::get('/301/delete/{id}', 'RedirectionController@redir301Delete')->name('r301.del');

			Route::post('/301/upload', 'RedirectionController@upload')->name('r301.upd');
		} );



		/*************** AJAX ******************/
		Route::group(['prefix' => 'ajax'], function() {
			Route::post('/getRegionList', 'RegionalSettingsController@ajaxRegionList')->name('ajx_regionList');
			Route::post('/getProvinceList', 'RegionalSettingsController@ajaxProvinceList')->name('ajx_provinceList');
			Route::post('/getCityList', 'RegionalSettingsController@ajaxCityList')->name('ajx_cityList');
			Route::post('/checkEventCatName', 'EventController@ajaxchkCatNM')->name('ajaxchkCatNM');
			Route::post('/checkEventCatSlug', 'EventController@ajaxchkCatSlug')->name('ajaxchkCatSlug');
			Route::post('/checkEventName', 'EventController@ajaxchkEvtNM')->name('ajaxchkEvtNM');
			Route::post('/checkEventSlug', 'EventController@ajaxchkEvtSlug')->name('ajaxchkEvtSlug');
			Route::post('/eventCalModify', 'EventController@ajaxevtCalModify')->name('ajaxevtCalModify');
			Route::post('/ckEdtImgUpload', 'EventController@ajaxevtCkEdtUpload')->name('ajaxevtCkEdtUpload');
			Route::post('/GalleryImageUpload', 'MediaController@ajaxGalImgUpload')->name('ajaxGalImgUpload');
			Route::post('/checkSlugUrl', 'AjaxController@checkSlugUrl')->name('checkSlugUrl');

			Route::post('/checkSlugUrlWb', 'AjaxController@checkSlugUrlWb')->name('checkSlugUrlWb');

			Route::post('/checkSlugUrlSelf', 'AjaxController@checkSlugUrlSelf')->name('checkSlugUrlSelf');
			Route::post('/ajax-file-delete', 'AjaxController@fileDelete')->name('ajxFileDel');
			Route::post('/ajax-media-images-upload', 'AjaxElementController@mediaImageUpload')->name('ajxMediaImgUpload');
			Route::get('/ajax-image-library', 'AjaxElementController@mediaImageLibrary')->name('ajxMediaImgLibrary');
			Route::get('/ajax-load-galleries', 'AjaxElementController@mediaLoadImageGalleries')->name('ajxMediaLdImgGals');
			Route::post('/ajax-elemodal-scodes', 'AjaxElementController@eleShortCodes')->name('ajxEleScodes');
			Route::post('/ajax-media-files-upload', 'AjaxElementController@mediaFileUpload')->name('ajxMediaFileUpload');
			Route::get('/ajax-get-media-files', 'AjaxElementController@getMediaFiles')->name('ajxMediaFileLibrary');
			Route::get('/ajax-load-file-categories', 'AjaxElementController@mediaLoadFileCategories')->name('ajxMediaLdFlCats');
			Route::post('/ajax-load-file-subcategories', 'AjaxElementController@mediaLoadFileSubCategories')->name('ajxMediaLdFlSCats');
			Route::post('/ajax-load-file-subcategories_byslug', 'AjaxElementController@mediaLoadFileSubCategoriesSlug')->name('ajxMediaLdFlSCatsSlug');
			Route::post('/ajax-load-image-subcategories', 'AjaxElementController@mediaLoadImageSubCategories')->name('ajxMediaLdImgSCats');

			Route::get('/ajax-load-image-categories', 'AjaxElementController@mediaLoadImgCategories')->name('ajxMediaLdImgCats');
			Route::post('/ajax-load-image-subcategories_byslug', 'AjaxElementController@mediaLoadImgSubCategoriesSlug')->name('ajxMediaLdImgSCatsSlug');

			Route::post('/ajax-load-video-subcategories', 'AjaxElementController@mediaLoadVidSubCategories')->name('ajxMediaLdVdSCats');

			Route::post('/ajax-media-video-add', 'AjaxElementController@mediaVideoAdd')->name('ajxMediaVidAdd');
			Route::get('/ajax-video-library', 'AjaxElementController@mediaVideoLibrary')->name('ajxMediaVidLibrary');
 
			Route::post('get-continent-to-country', 'RegionalSettingsController@getContinentToCountry')->name('ajx_continent_country');
			Route::post('get-country-to-city', 'RegionalSettingsController@getContinentToCity')->name('ajx_country_city');
		});

		Route::get('/active-inactive', 'CommonController@activeInactive')->name('acInac');

		/*************** PAGE BUILDER AJAX ******************/
		Route::group(['prefix' => 'page-builder'], function() {

			Route::post('/content-add-edit', 'PageBuilderController@addEdit')->name('pgbAddEdt');
			Route::post('/content-get', 'PageBuilderController@getContent')->name('pgbGet');
			Route::post('/content-delete', 'PageBuilderController@delete')->name('pgbDel');
			Route::post('/content-order', 'PageBuilderController@ordering')->name('pgbOrd');
			Route::post('/content-position', 'PageBuilderController@position')->name('pgbChng');
			Route::get('/get-form-short-codes', 'PageBuilderController@allForms')->name('pgbAllFrms');
			Route::get('/get-all-reuse', 'PageBuilderController@allReuse')->name('pgbAllReuse');
			Route::post('/delete-image', 'PageBuilderController@deleteImage')->name('pgbDelImg');
			Route::post('/delete-file', 'PageBuilderController@deleteFile')->name('pgbDelFil');
			Route::post('/delete-video', 'PageBuilderController@deleteVideo')->name('pgbDelVid');
			Route::post('/edit-file', 'PageBuilderController@editFile')->name('pgbEdtFil');
			Route::post('/edit-image', 'PageBuilderController@editImage')->name('pgbEdtImg');
			Route::post('/edit-video', 'PageBuilderController@editVideo')->name('pgbEdtVid');

			Route::post('/get-links', 'PageBuilderController@getLinks')->name('pgbgetLnks');
			Route::post('/get-reu', 'PageBuilderController@getPboxReusable')->name('pgbgetPboxReu');
		} );

	}); //end dashboard prefix
	/********** END DASHBOARD ACTION *********/

}); //end admin prefix



