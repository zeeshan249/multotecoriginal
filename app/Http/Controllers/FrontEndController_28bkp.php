<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Http\Request;
use App\Models\Menu\MenuMaster;
use App\Models\Menu\NaviMaster;
use App\Models\CmsLinks;
use Jenssegers\Agent\Agent;
use Carbon\Carbon;
use Redirect;
use Session;
use View;
use DB;
use File;

class FrontEndController extends Controller
{

	public function __construct(Request $request) 
	{	

        $requestURL = $request->url();
        $ckRed = DB::table('redirection')->where('type', '=', '301')->where('status', '=', '1')
        ->where('source_url', '=', $requestURL)->first();

        if( !empty($ckRed) && $ckRed->destination_url != '' ) {
            
            return Redirect::to($ckRed->destination_url, 301)->send(); 
        }

        $currlngid = '1';
        $currlngcode = 'en';
        
        if(Session::has('current_lng')) {
            $currlngid = Session::get('current_lng');
            $currlngcode = Session::get('current_lngcode');
        }

        $shareData = array();

        $shareData['currlngid'] = $currlngid;
        $shareData['currlngcode'] = $currlngcode;

		$mainMenu = NaviMaster::where('menu_id', '=', '2')->where('parent_page_id', '=', '0')
		->where('lng_id', '=', $currlngid)->orderBy('oid', 'asc')->get();
        $shareData['mainMenu'] = $mainMenu;

        $stickyFooter = NaviMaster::where('menu_id', '=', '4')->where('parent_page_id', '=', '0')
        ->where('lng_id', '=', $currlngid)->orderBy('oid', 'asc')->get();
        $shareData['stickyFooter'] = $stickyFooter;

        $footerMenu = NaviMaster::where('menu_id', '=', '3')->where('lng_id', '=', $currlngid)->orderBy('oid', 'asc')->get();
        $shareData['footerMenu'] = $footerMenu;

        $socialLinks = \App\Models\SocialLinks::where('status', '=', '1')->orderBy('display_order', 'asc')->get();
        $shareData['socialLinks'] = $socialLinks;        
	    
        View::share($shareData);
	}



    
    public function home( Request $request, $lng = '' ) {

       
        $currlngcode = 'en';
        $currlngid = '1';

        if(Session::has('current_lng')) {
            $currlngid = Session::get('current_lng');
            $currlngcode = Session::get('current_lngcode');
        } 

        if( $lng == '' && $lng == NULL && count($request->segments()) == 0 ) {
            
            $forceURL = $request->url().'/'.$currlngcode;
            return redirect($forceURL);
        }

        $device = 1;
        $agent = new Agent();
        
        if( $agent->isMobile() ) {
            
            $device = 2;
        }
        $DataBag['device'] = $device;

        $DataBag = array();

        $DataBag['lng'] = $lng;

        $DataBag['home_banners'] = \App\Models\Banners::with(['BannerImages'])->get();

        $data = \App\Models\HomeContent::where('language_id', '=', $currlngid)->first();

        $DataBag['mps'] = \App\Models\MineralProcess::where('status', '=', '1')->orderBy('id', 'desc')->get();
        $DataBag['minerals'] = \App\Models\Mineral::where('status', '=', '1')->orderBy('id', 'desc')->get();
        
        $DataBag['allData'] = $data;
        $DataBag['page_metadata'] = $DataBag['allData'];

    	return view('front_end.home', $DataBag);
    }



    
    /********************************************************************************************************************/
    public function cmsPage( $lng, $slug ) {

    	$device = 1;

    	$DataBag = array();
        $breadcrumbs = array();

    	$agent = new Agent();
    	
    	if( $agent->isMobile() ) {
    		
    		$device = 2;
    	}

        $DataBag['device'] = $device;
        
        $DataBag['lng'] = $lng;

        $getlngid = getLngIDbyCode( $lng );

        $view = 'front_end.home';
    	
        $cms = CmsLinks::where('slug_url', '=', trim($slug))->first();
    	if( !empty($cms) ) {

            $table_id = $cms->table_id;
            $table_type = $cms->table_type;





            /** PRODUCT **/
            if( $table_type == 'PRODUCT' ) {
                
                $data = \App\Models\Product\Products::with(['pageBuilderContent'])
                ->where('language_id', '=', $getlngid)->where('id', '=', $table_id)->first();
                
                $DataBag['allData'] = $data;

                $DataBag['page_metadata'] = $DataBag['allData'];

                $DataBag['breadcrumbs'] = $breadcrumbs;
                
                $view = 'front_end.product.product_page';
            }



            /** PRODUCT CATEGORY **/
            if( $table_type == 'PRODUCT_CATEGORY' ) {
                
                $data = \App\Models\Product\ProductCategories::with(['pageBuilderContent'])
                ->where('language_id', '=', $getlngid)->where('id', '=', $table_id)->first();
                
                $DataBag['allData'] = $data;

                $DataBag['page_metadata'] = $DataBag['allData'];

                $DataBag['breadcrumbs'] = $breadcrumbs;
                
                $view = 'front_end.product.product_category_page';
            }

            /** TECHNICAL RESOURCE **/
            if( $table_type == 'TECH_RESOURCE' ) {
                
                $data = \App\Models\TechResource\TechResource::with(['pageBuilderContent'])
                ->where('language_id', '=', $getlngid)->where('id', '=', $table_id)->first();
                
                $DataBag['allData'] = $data;

                $DataBag['page_metadata'] = $DataBag['allData'];

                $DataBag['breadcrumbs'] = $breadcrumbs;
                
                $view = 'front_end.tech_resource.tech_resource_content';
            }

            /** CONTENTS **/
            if( $table_type == 'DYNA_CONTENT' ) {
                
                $data = \App\Models\Content\Contents::with(['pageBuilderContent'])
                ->where('language_id', '=', $getlngid)->where('id', '=', $table_id)->first();
                
                $DataBag['allData'] = $data;

                $DataBag['page_metadata'] = $DataBag['allData'];

                $DataBag['breadcrumbs'] = $breadcrumbs;
                
                $view = 'front_end.dyna_content.content_page';
            }




            /** ARTICLE **/
            if( $table_type == 'ARTICLE' ) {
                
                $data = \App\Models\Article\Articles::with(['pageBuilderContent'])
                ->where('language_id', '=', $getlngid)->where('id', '=', $table_id)->first();

                $DataBag['allData'] = $data;

                $DataBag['page_metadata'] = $DataBag['allData'];

                $articlesCats = \App\Models\Article\ArticleCategories::where('status', '=', '1')->orderBy('created_at', 'desc')->paginate( 20 );
                $DataBag['listCats'] = $articlesCats;

                $yearArr = array();
                $createdAt = \App\Models\Article\Articles::where('status', '=', '1')
                ->where('parent_language_id', '=', '0')->orderBy('created_at', 'desc')->pluck('created_at')->toArray();
                if( !empty($createdAt) ) {
                    foreach( $createdAt as $v ) {
                        $onlyYear = Carbon::createFromFormat('Y-m-d H:i:s', $v)->year;
                        array_push( $yearArr , $onlyYear );
                    }
                }
                $uniqueYear = array_unique( $yearArr );
                $DataBag['yearList'] = $uniqueYear;
                
                $view = 'front_end.news_article.content_page';
            }



            /** ARTICLE CATEGORY **/
            if( $table_type == 'ARTICLE_CATEGORY' ) {
                
                return redirect()->route('newsArticleList', array('lng' => $lng));
            }




            /** EVENT **/
            if( $table_type == 'EVENT' ) {
                
                $data = \App\Models\Events::with(['pageBuilderContent'])
                ->where('language_id', '=', $getlngid)->where('id', '=', $table_id)->first();
                
                $DataBag['allData'] = $data;

                $DataBag['page_metadata'] = $DataBag['allData'];

                $eventCats = \App\Models\EventCategories::where('status', '=', '1')->orderBy('created_at', 'desc')->paginate( 20 );
                $DataBag['listCats'] = $eventCats;

                $yearArr = array();
                $createdAt = \App\Models\Events::where('status', '=', '1')
                ->where('parent_language_id', '=', '0')->orderBy('created_at', 'desc')->pluck('created_at')->toArray();
                if( !empty($createdAt) ) {
                    foreach( $createdAt as $v ) {
                        $onlyYear = Carbon::createFromFormat('Y-m-d H:i:s', $v)->year;
                        array_push( $yearArr , $onlyYear );
                    }
                }
                $uniqueYear = array_unique( $yearArr );
                $DataBag['yearList'] = $uniqueYear;
                
                $view = 'front_end.event.content_page';
            }



            /** EVENT CATEGORY **/
            if( $table_type == 'EVENT_CATEGORY' ) {
                
                return redirect()->route('eventLists', array('lng' => $lng));
            }




            /** INDUSTRY **/
            if( $table_type == 'INDUSTRY' ) {
                
                $data = \App\Models\Industry\Industries::with(['pageBuilderContent'])
                ->where('language_id', '=', $getlngid)->where('id', '=', $table_id)->first();
                
                $DataBag['allData'] = $data;

                $DataBag['page_metadata'] = $DataBag['allData'];

                $DataBag['breadcrumbs'] = $breadcrumbs;
                
                $view = 'front_end.industry.industry';
            }




            /** FLOWSHEET CATEGORY **/
            if( $table_type == 'FLOWSHEET_CATEGORY' ) {
                
                $data = \App\Models\IndustryFlowsheet\FlowsheetCategories::with(['pageBuilderContent'])
                ->where('language_id', '=', $getlngid)->where('id', '=', $table_id)->first();
                
                $DataBag['allData'] = $data;

                $DataBag['page_metadata'] = $DataBag['allData'];

                $DataBag['breadcrumbs'] = $breadcrumbs;
                
                $view = 'front_end.industry.flowsheet_category';
            }




            /** FLOWSHEET **/
            if( $table_type == 'FLOWSHEET' ) {
                
                $data = \App\Models\IndustryFlowsheet\Flowsheet::with(['pageBuilderContent'])
                ->where('language_id', '=', $getlngid)->where('id', '=', $table_id)->first();
                
                $DataBag['allData'] = $data;

                $DataBag['page_metadata'] = $DataBag['allData'];

                $DataBag['fsmarkers'] = \App\Models\IndustryFlowsheet\FlowsheetMarker::where('flowsheet_id', '=', $table_id)->get();

                $DataBag['breadcrumbs'] = $breadcrumbs;
                
                $view = 'front_end.industry.flowsheet';
            }




            /** DISTRIBUTOR CATEGORY **/
            if( $table_type == 'DISTRIBUTOR_CATEGORY' ) {
                
                /*$data = \App\Models\Distributor\DistributorCategories::with(['pageBuilderContent', 'DistributorIds'])
                ->where('language_id', '=', $getlngid)->where('id', '=', $table_id)->first();
                
                $DataBag['allData'] = $data;

                $DataBag['page_metadata'] = $DataBag['allData'];

                $DataBag['breadcrumbs'] = $breadcrumbs;
                
                $view = 'front_end.distributor.distributor_category';*/

                return redirect()->route('front.distrbCat', array('lng' => $lng, 'catslug' => $slug));
            }




            /** DISTRIBUTOR **/
            if( $table_type == 'DISTRIBUTOR' ) {
                
                /*$data = \App\Models\Distributor\Distributor::with(['pageBuilderContent'])
                ->where('language_id', '=', $getlngid)->where('language_id', '=', $getlngid)->where('id', '=', $table_id)->first();

                $DataBag['allData'] = $data;

                $DataBag['page_metadata'] = $DataBag['allData'];

                $DataBag['breadcrumbs'] = $breadcrumbs;
                
                $view = 'front_end.distributor.distributor';*/

                $distrb = \App\Models\Distributor\Distributor::where('id', '=', $table_id)->first();
                if( !empty($distrb) ) {
                    if( isset($distrb->distrCategorytOne) && isset($distrb->distrCategorytOne->catInfo) ) {
                        $catslug = $distrb->distrCategorytOne->catInfo->slug;
                        return redirect()->route('front.distrb', array('lng' => $lng, 'catslug' => $catslug, 'slug' => $slug));
                    }
                }
            }



            /** DISTRIBUTOR CONTENT **/
            if( $table_type == 'DISTRIBUTOR_CONTENT' ) {
                
                /*$data = \App\Models\Distributor\DistributorContents::with(['pageBuilderContent'])
                ->where('language_id', '=', $getlngid)->where('id', '=', $table_id)->first();
                
                $DataBag['allData'] = $data;

                $DataBag['page_metadata'] = $DataBag['allData'];

                $DataBag['breadcrumbs'] = $breadcrumbs;
                
                $view = 'front_end.distributor.distributor_content';*/

                $disCont = \App\Models\Distributor\DistributorContents::where('id', '=', $table_id)->first();
                if( !empty($disCont) ) {
                    if( isset($disCont->distributorInfo) && isset($disCont->distributorInfo->distrCategorytOne) && isset($disCont->distributorInfo->distrCategorytOne->catInfo) ) {
                        $disslug = $disCont->distributorInfo->slug;
                        $catslug = $disCont->distributorInfo->distrCategorytOne->catInfo->slug;
                    return redirect()->route('front.distrbCont', array('lng' => $lng, 'catslug' => $catslug, 'disslug' => $disslug, 'slug' => $slug));
                    }
                }
            }




            /** PEOPLES PROFILE CATEGORY **/
            if( $table_type == 'PEOPLE_PROFILE_CATEGORY' ) {
                
                $data = \App\Models\PeoplesProfile\PeopleProfileCategories::with(['pageBuilderContent'])
                ->where('language_id', '=', $getlngid)->where('id', '=', $table_id)->first();

                $DataBag['allData'] = $data;

                $DataBag['page_metadata'] = $DataBag['allData'];

                $DataBag['breadcrumbs'] = $breadcrumbs;
                
                $view = 'front_end.people_profile.profile_category';
            }




            /** PEOPLES PROFILE **/
            if( $table_type == 'PEOPLE_PROFILE' ) {
                
                return redirect()->route('front.profCont', array('lng' => $lng, 'slug' => $slug));
            }
			
		} else {
            abort(404);
        }

        return view ( $view, $DataBag );
    }
    /********************************************************************************************************************/






    /**********************************RESOURCE FILES*************************************/
    /************************************************************************************/

    public function allFileCategory( $lng ) {

        $DataBag = array();  
        $DataBag['lng'] = $lng;
        $getlngid = getLngIDbyCode( $lng );
        $DataBag['lng_id'] = $getlngid;
        
        $fileCategories = \App\Models\Media\FileCategories::where('parent_category_id', '=', '0')
        ->where('status', '=', '1')
        ->where('show_in_gallery', '=', '1')
        ->orderBy('display_order','asc')->get();

        $DataBag['fileCategories'] = $fileCategories;

        $DataBag['extraContent'] = \App\Models\Media\MediaExtraContent::where('type', '=', 'FILE')->first();

        $DataBag['page_metadata'] = $DataBag['extraContent'];
        
        return view('front_end.file_categories', $DataBag);
    }


    /** File Download with Category Subcategory with search **/
    public function fileSubcategory( Request $request, $lng, $category_slug, $subcategory_slug = null ) {

        $DataBag = array();
        $page_data = array();
        $category_id = 0;
        $subcategory_id = 0;
        
        $DataBag['lng'] = $lng;
        $getlngid = getLngIDbyCode( $lng );
        $DataBag['lng_id'] = $getlngid;

        $DataBag['catSlug'] = $category_slug;

        $findCatId = \App\Models\Media\FileCategories::where('slug', '=', trim($category_slug))->first();
        
        if( !empty($findCatId) ) {
            $category_id = $findCatId->id;
            $DataBag['catName'] = $findCatId->name;
            $DataBag['breadcrumb_cat_name'] = $findCatId->name;
            $DataBag['breadcrumb_cat_slug'] = $findCatId->slug;
        }

        if( $subcategory_slug != '' && $subcategory_slug != null ) {

            $findSubCatId = \App\Models\Media\FileCategories::where('slug', '=', trim($subcategory_slug))->first(); 
            if( !empty($findSubCatId) ) {
                $subcategory_id = $findSubCatId->id;
                $DataBag['catName'] = $findSubCatId->name;
                $DataBag['breadcrumb_subcat_name'] = $findSubCatId->name;
                $DataBag['breadcrumb_subcat_slug'] = $findSubCatId->slug;
            }   
        }
        
        if( $subcategory_id != 0 ) {

            $query = DB::table('file_categories_map as fcm')->where('fcm.file_category_id', '=', $category_id)
            ->where('fcm.file_subcategory_id', '=', $subcategory_id)->join('files_master', 'files_master.id', '=', 'fcm.file_id');

            $query = $query->when($request->get('search'), function($q) use($request) {

                return $q->where('files_master.name', 'LIKE', '%'.$request->get('search').'%')
                ->orWhere('files_master.title', 'LIKE', '%'.$request->get('search').'%')
                ->orWhere('files_master.details', 'LIKE', '%'.$request->get('search').'%')
                ->orWhere('files_master.caption', 'LIKE', '%'.$request->get('search').'%');
            } );

            $downloadBrochures = $query->where('files_master.status', '=', '1')->orderBy('files_master.created_at', 'desc')
            ->select('files_master.*')->get();

            $page_data = \App\Models\Media\FileCategories::where('id', '=', $subcategory_id)->first();

        } else {

            $query = DB::table('file_categories_map as fcm')->where('fcm.file_category_id', '=', $category_id)
            ->join('files_master', 'files_master.id', '=', 'fcm.file_id');

            $query = $query->when($request->get('search'), function($q) use($request) {

                return $q->where('files_master.name', 'LIKE', '%'.$request->get('search').'%')
                ->orWhere('files_master.title', 'LIKE', '%'.$request->get('search').'%')
                ->orWhere('files_master.details', 'LIKE', '%'.$request->get('search').'%')
                ->orWhere('files_master.caption', 'LIKE', '%'.$request->get('search').'%');
            } );

            $downloadBrochures = $query->where('files_master.status', '=', '1')->orderBy('files_master.created_at', 'desc')
            ->select('files_master.*')->get();

            $page_data = \App\Models\Media\FileCategories::where('id', '=', $category_id)->first();
        }  

        $fileSubCategories = \App\Models\Media\FileCategories::where('parent_category_id', '!=', '0')
        ->where('parent_category_id', '=', $category_id)->where('status', '=', '1')->orderBy('name','asc')->get();

        $DataBag['fileSubCategories'] = $fileSubCategories;
        $DataBag['downloadBrochures'] = $downloadBrochures;
        $DataBag['page_data'] = $page_data;

        $DataBag['page_metadata'] = $DataBag['page_data'];
        
        return view('front_end.file_download', $DataBag);
    }

    /*************************************************************************************************************************/
    /*************************************************************************************************************************/

    

    /*********************** RESOURCE IMAGE ********************************************/
    /**********************************************************************************/
    public function allImgGalCats( $lng ) {

    	$DataBag = array();
        $DataBag['lng'] = $lng;
        $getlngid = getLngIDbyCode( $lng );
        $DataBag['lng_id'] = $getlngid;

        $DataBag['tab_tag'] = 'image_gallery';
        
        if(isset($_GET['search']) && $_GET['search'] != '') {

            $DataBag['allcats'] = \App\Models\Media\ImageCategories::where('parent_category_id', '=', '0')
            ->where('status', '=', '1')->where('name', 'LIKE', '%'.$_GET['search'].'%')
            ->where('show_in_gallery', '=', '1')
            ->orderBy('display_order','asc')->paginate(12);

        } else {
    	
            $DataBag['allcats'] = \App\Models\Media\ImageCategories::where('parent_category_id', '=', '0')
            ->where('status', '=', '1')
            ->where('show_in_gallery', '=', '1')
            ->orderBy('display_order','asc')->paginate(12);
        }
        
        $DataBag['extraContent'] = \App\Models\Media\MediaExtraContent::where('type', '=', 'IMAGE')->first();

        $DataBag['page_metadata'] = $DataBag['extraContent'];
    	
    	return view('front_end.image_video_cats', $DataBag);
    }

    /** Image Display with Category Subcategory with search **/
    public function galSubcategory( Request $request, $lng, $category_slug, $subcategory_slug = null ) {

        $DataBag = array();
        $page_data = array();
        $category_id = 0;
        $subcategory_id = 0;
        
        $DataBag['lng'] = $lng;
        $getlngid = getLngIDbyCode( $lng );
        $DataBag['lng_id'] = $getlngid;
        
        $DataBag['catSlug'] = $category_slug;
        $DataBag['tab_tag'] = 'image_gallery';

        $findCatId = \App\Models\Media\ImageCategories::where('slug', '=', trim($category_slug))->first();
        
        if( !empty($findCatId) ) {
            $category_id = $findCatId->id;
            $DataBag['catName'] = $findCatId->name;
            $DataBag['breadcrumb_cat_name'] = $findCatId->name;
            $DataBag['breadcrumb_cat_slug'] = $findCatId->slug;
        }

        if( $subcategory_slug != '' && $subcategory_slug != null ) {

            $findSubCatId = \App\Models\Media\ImageCategories::where('slug', '=', trim($subcategory_slug))->first(); 
            if( !empty($findSubCatId) ) {
                $subcategory_id = $findSubCatId->id;
                $DataBag['catName'] = $findSubCatId->name;
                $DataBag['breadcrumb_subcat_name'] = $findSubCatId->name;
                $DataBag['breadcrumb_subcat_slug'] = $findSubCatId->slug;
            }   
        }
        
        if( $subcategory_id != 0 ) {

            $query = DB::table('image_category_map as icm')->where('icm.image_category_id', '=', $category_id)
            ->where('icm.image_subcategory_id', '=', $subcategory_id)->join('image', 'image.id', '=', 'icm.image_id')
            ->where('image.status', '=', '1');

            $query = $query->when($request->get('search'), function($q) use($request) {

                return $q->where('image.name', 'LIKE', '%'.$request->get('search').'%')
                ->orWhere('image.title', 'LIKE', '%'.$request->get('search').'%')
                ->orWhere('image.alt_title', 'LIKE', '%'.$request->get('search').'%')
                ->orWhere('image.caption', 'LIKE', '%'.$request->get('search').'%');
            } );

            $viewImages = $query->orderBy('image.created_at', 'desc')->select('image.*')->paginate(9);

            $page_data = \App\Models\Media\ImageCategories::where('id', '=', $subcategory_id)->first();

        } else {

            $query = DB::table('image_category_map as icm')->where('icm.image_category_id', '=', $category_id)
            ->join('image', 'image.id', '=', 'icm.image_id')->where('image.status', '=', '1');

            $query = $query->when($request->get('search'), function($q) use($request) {

                return $q->where('image.name', 'LIKE', '%'.$request->get('search').'%')
                ->orWhere('image.title', 'LIKE', '%'.$request->get('search').'%')
                ->orWhere('image.alt_title', 'LIKE', '%'.$request->get('search').'%')
                ->orWhere('image.caption', 'LIKE', '%'.$request->get('search').'%');
            } );

            $viewImages = $query->orderBy('image.created_at', 'desc')->select('image.*')->paginate(9);

            $page_data = \App\Models\Media\ImageCategories::where('id', '=', $category_id)->first();
        }  

        $imgSubCategories = \App\Models\Media\ImageCategories::where('parent_category_id', '!=', '0')
        ->where('parent_category_id', '=', $category_id)->where('status', '=', '1')->orderBy('display_order','asc')->get();

        $DataBag['imgSubCategories'] = $imgSubCategories;
        $DataBag['viewImages'] = $viewImages;
        $DataBag['page_data'] = $page_data;
        $DataBag['page_metadata'] = $DataBag['page_data'];
        
        return view('front_end.view_image_gallery', $DataBag);
    }

    /*******************************************************************************************************************/
    /*******************************************************************************************************************/



    /*************************** RESOURCE VIDEO ***************************************/
    /*********************************************************************************/
    public function allVidGalCats( $lng ) {

        $DataBag = array();
        $DataBag['lng'] = $lng;
        $getlngid = getLngIDbyCode( $lng );
        $DataBag['lng_id'] = $getlngid;

        $DataBag['tab_tag'] = 'video_gallery';

        if(isset($_GET['search']) && $_GET['search'] != '') {

            $DataBag['allcats'] = \App\Models\Media\VideoCategories::where('parent_category_id', '=', '0')
            ->where('status', '=', '1')
            ->where('show_in_gallery', '=', '1')
            ->where('name', 'LIKE', '%'.$_GET['search'].'%')
            ->orderBy('display_order','asc')->paginate(12);
            
        } else {
            
            $DataBag['allcats'] = \App\Models\Media\VideoCategories::where('parent_category_id', '=', '0')
            ->where('status', '=', '1')
            ->where('show_in_gallery', '=', '1')
            ->orderBy('display_order','asc')->paginate(12);
        }

        $DataBag['extraContent'] = \App\Models\Media\MediaExtraContent::where('type', '=', 'VIDEO')->first();

        $DataBag['page_metadata'] = $DataBag['extraContent'];
        
        return view('front_end.image_video_cats', $DataBag);
    }


    public function videoGalSubcategory( $lng, $category_slug, $subcategory_slug = null ) {

        $DataBag = array();
        $page_data = array();
        $category_id = 0;
        $subcategory_id = 0;
        
        $DataBag['lng'] = $lng;
        $getlngid = getLngIDbyCode( $lng );
        $DataBag['lng_id'] = $getlngid;

        $DataBag['catSlug'] = $category_slug;

        $DataBag['tab_tag'] = 'video_gallery';

        $findCatId = \App\Models\Media\VideoCategories::where('slug', '=', trim($category_slug))->first();
        
        if( !empty($findCatId) ) {
            $category_id = $findCatId->id;
            $DataBag['catName'] = $findCatId->name;
            $DataBag['breadcrumb_cat_name'] = $findCatId->name;
            $DataBag['breadcrumb_cat_slug'] = $findCatId->slug;
        }

        if( $subcategory_slug != '' && $subcategory_slug != null ) {

            $findSubCatId = \App\Models\Media\VideoCategories::where('slug', '=', trim($subcategory_slug))->first(); 
            if( !empty($findSubCatId) ) {
                $subcategory_id = $findSubCatId->id;
                $DataBag['catName'] = $findSubCatId->name; /** Page heading replace when subcat show, display subcat name */
                $DataBag['breadcrumb_subcat_name'] = $findSubCatId->name;
                $DataBag['breadcrumb_subcat_slug'] = $findSubCatId->slug;
            }   
        }
        
        if( $subcategory_id != 0 ) {
            $viewVideos = DB::table('video_categories_map as vcm')->where('vcm.video_category_id', '=', $category_id)
            ->where('vcm.video_subcategory_id', '=', $subcategory_id)->join('videos', 'videos.id', '=', 'vcm.video_id')
            ->where('videos.status', '=', '1')->orderBy('videos.created_at', 'desc')->select('videos.*')->paginate(9);

            $page_data = \App\Models\Media\VideoCategories::where('id', '=', $subcategory_id)->first();

        } else {
            $viewVideos = DB::table('video_categories_map as vcm')->where('vcm.video_category_id', '=', $category_id)
            ->join('videos', 'videos.id', '=', 'vcm.video_id')
            ->where('videos.status', '=', '1')->orderBy('videos.created_at', 'desc')->select('videos.*')->paginate(9);

            $page_data = \App\Models\Media\VideoCategories::where('id', '=', $category_id)->first();
        }  

        $vidSubCategories = \App\Models\Media\VideoCategories::where('parent_category_id', '!=', '0')
        ->where('parent_category_id', '=', $category_id)->where('status', '=', '1')->orderBy('name','asc')->get();

        $DataBag['vidSubCategories'] = $vidSubCategories;
        $DataBag['viewVideos'] = $viewVideos;
        $DataBag['page_data'] = $page_data;

        $DataBag['page_metadata'] = $DataBag['page_data'];
        
        return view('front_end.view_video_gallery', $DataBag);
    }

    /*********************************************************************************************************************/
    /*********************************************************************************************************************/




    
    /************************************************TECHNICAL RESOURCE*************************************************/
    public function viewTechnicalResourceList( $lng ) {

        $DataBag = array();
        
        $DataBag['lng'] = $lng;
        $getlngid = getLngIDbyCode( $lng );
        $DataBag['lng_id'] = $getlngid;

        $data = \App\Models\TechResource\TechResource::with(['procatIds', 'FileIds'])
        ->where('status', '=', '1')->orderBy('id', 'desc')->get();

        $DataBag['allData'] = $data;

        $resFiles = \App\Models\TechResource\TechResource::with(['FileIds', 'ImageIds', 'procatIds'])
        ->where('status', '=', '1')->where('tab_section', '=', 'PRODUCT')->orderBy('display_order', 'asc')->paginate( 20 );

        $DataBag['resFiles'] = $resFiles;

        $DataBag['extraContent'] = \App\Models\Media\MediaExtraContent::where('type', '=', 'TECHRES')->first();

        $DataBag['page_metadata'] = $DataBag['extraContent'];
        
        return view('front_end.tech_resource.tech_resource_list', $DataBag);   
    }

    public function ajxTechnicalResourceList(Request $request, $lng) {

        $DataBag = array();

        $DataBag['lng'] = $lng;

        $pcid = trim($request->input('pcid'));
        $seletab = trim($request->input('seletab'));

        $findTR = DB::table('tech_resource_procat_map')->where('product_category_id', '=', $pcid)->get();
        $idsArr = array();

        if( !empty($findTR) ) {
            foreach($findTR as $v) {
                if( $v->tech_resource_id != '0' ) {
                    array_push($idsArr, $v->tech_resource_id);
                }
            }
        }

        $unqIdsArr = array_unique( $idsArr );

        $resFiles = \App\Models\TechResource\TechResource::with(['FileIds', 'ImageIds'])->where('tab_section', '=', $seletab)
        ->whereIn('id', $unqIdsArr)->where('status', '=', '1')->orderBy('display_order', 'asc')->paginate( 20 );

        $DataBag['resFiles'] = $resFiles;

        $render = view( 'front_end.render.tech_resource_files', $DataBag )->render();

        return response()->json(['html_view' => $render, 'status' => 'ok']);
    } 

    public function ajxTechnicalResourceTab(Request $request, $lng) {

        $DataBag = array();

        $DataBag['lng'] = $lng;

        $seletab = trim($request->input('seletab'));

        $resFiles = \App\Models\TechResource\TechResource::with(['FileIds', 'ImageIds'])
        ->where('tab_section', '=', $seletab)->where('status', '=', '1')->orderBy('display_order', 'asc')->paginate( 20 );

        $DataBag['resFiles'] = $resFiles;

        $render = view( 'front_end.render.tech_resource_files', $DataBag )->render();

        return response()->json(['html_view' => $render, 'status' => 'ok']);
    }

    public function ajxTechnicalResourceSrc(Request $request, $lng) {

        $DataBag = array();

        $DataBag['lng'] = $lng;

        $search = trim($request->input('search'));

        $query = \App\Models\TechResource\TechResource::with(['FileIds', 'ImageIds'])->where('status', '=', '1');

        $query = $query->where( function($query) use ($search) {
            $query->where('name', 'LIKE', '%'. $search.'%'); 
        } );

        $query = $query->orderBy('display_order', 'asc')->paginate( 20 );

        $DataBag['resFiles'] = $query;

        $render = view( 'front_end.render.tech_resource_files', $DataBag )->render();

        return response()->json(['html_view' => $render, 'status' => 'ok']);
    }

    /************************************************END TECHNICAL RESOURCE***************************************/


    /************************************************NEWS AND ARTICLES*************************************************/
    public function newsArticleLists( $lng ) {

        $DataBag = array();
        
        $DataBag['lng'] = $lng;
        $getlngid = getLngIDbyCode( $lng );
        $DataBag['lng_id'] = $getlngid;

        $query = \App\Models\Article\Articles::where('status', '=', '1')->where('parent_language_id', '=', '0');

        if( isset($_GET['catid']) && $_GET['catid'] != '' ) {
            $query = $query->where( function($query) {
                $query = $query->whereHas('categoryIds', function ($query) {
                    $query->where( 'article_category_id', '=', trim($_GET['catid']) );
                });
            } );
        }

        if( isset($_GET['year']) && isset($_GET['month']) ) {
            $query = $query->where( function($query) {
                $query = $query->whereMonth( 'publish_date', '=', trim($_GET['month']) );
                $query = $query->whereYear( 'publish_date', '=', trim($_GET['year']) );
            } );
        }

        if( isset($_GET['search']) && $_GET['search'] != '' ) {
            $query = $query->where( function($query) {
                $query = $query->where( 'name', 'LIKE', '%'.trim($_GET['search']).'%' );
            } );
        }

        $articlesData = $query->orderBy('display_order', 'desc')->paginate( 20 );
        $DataBag['listData'] = $articlesData;
        
        $DataBag['page_tag'] = 'Articles & News';

        $articlesCats = \App\Models\Article\ArticleCategories::where('status', '=', '1')->orderBy('created_at', 'desc')->get();
        $DataBag['listCats'] = $articlesCats;

        $yearArr = array();
        $createdAt = \App\Models\Article\Articles::where('status', '=', '1')
        ->where('parent_language_id', '=', '0')->where('publish_date', '!=', '')
        ->orderBy('publish_date', 'desc')->pluck('publish_date')->toArray();

        if( !empty($createdAt) ) {
            foreach( $createdAt as $v ) {
                $onlyYear = Carbon::createFromFormat('Y-m-d H:i:s', $v)->year;
                array_push( $yearArr , $onlyYear );
            }
        }
        
        $uniqueYear = array_unique( $yearArr );
        $DataBag['yearList'] = $uniqueYear;

        $DataBag['extraContent'] = \App\Models\Media\MediaExtraContent::where('type', '=', 'ARTICLE')->first();

        $DataBag['page_metadata'] = $DataBag['extraContent'];

        return view('front_end.news_article.news_articles_list', $DataBag); 
    }

    public function articleContent( $lng, $slug ) {

        $DataBag = array();
        
        $DataBag['lng'] = $lng;
        $getlngid = getLngIDbyCode( $lng );
        $DataBag['lng_id'] = $getlngid;


        $data = \App\Models\Article\Articles::with(['pageBuilderContent'])
        ->where('language_id', '=', $getlngid)->where('slug', '=', $slug)->first();

        $DataBag['allData'] = $data;

        $DataBag['page_metadata'] = $DataBag['allData'];

        $articlesCats = \App\Models\Article\ArticleCategories::where('status', '=', '1')->orderBy('created_at', 'desc')->get();
        $DataBag['listCats'] = $articlesCats;

        $yearArr = array();
        
        $createdAt = \App\Models\Article\Articles::where('status', '=', '1')
        ->where('parent_language_id', '=', '0')->where('publish_date', '!=', '')
        ->orderBy('publish_date', 'desc')->pluck('publish_date')->toArray();

        if( !empty($createdAt) ) {
            foreach( $createdAt as $v ) {
                $onlyYear = Carbon::createFromFormat('Y-m-d H:i:s', $v)->year;
                array_push( $yearArr , $onlyYear );
            }
        }
        $uniqueYear = array_unique( $yearArr );
        $DataBag['yearList'] = $uniqueYear;
        
        return view('front_end.news_article.content_page', $DataBag); 
    }
    /************************************************END NEWS & ARTICLES*************************************************/


    /************************************************EVENTS*************************************************/
    public function eventLists( $lng ) {

        $DataBag = array();
        
        $DataBag['lng'] = $lng;

        $getlngid = getLngIDbyCode( $lng );

        $query = \App\Models\Events::where('status', '=', '1')->where('parent_language_id', '=', '0');

        if( isset($_GET['catid']) && $_GET['catid'] != '' ) {
            $query = $query->where( function($query) {
                $query = $query->whereHas('categoryIds', function ($query) {
                    $query->where( 'event_category_id', '=', trim($_GET['catid']) );
                });
            } );
        }

        if( isset($_GET['year']) && isset($_GET['month']) ) {
            $query = $query->where( function($query) {
                $query = $query->whereMonth( 'publish_date', '=', trim($_GET['month']) );
                $query = $query->whereYear( 'publish_date', '=', trim($_GET['year']) );
            } );
        }

        if( isset($_GET['search']) && $_GET['search'] != '' ) {
            $query = $query->where( function($query) {
                $query = $query->where( 'name', 'LIKE', '%'.trim($_GET['search']).'%' );
            } );
        }

        
        $eventsData = $query->orderBy('display_order', 'desc')->paginate( 20 );

        $DataBag['listData'] = $eventsData;
        
        $DataBag['page_tag'] = 'Events';

        $eventCats = \App\Models\EventCategories::where('status', '=', '1')->orderBy('created_at', 'desc')->get();
        $DataBag['listCats'] = $eventCats;

        $yearArr = array();
        $createdAt = \App\Models\Events::where('status', '=', '1')
        ->where('parent_language_id', '=', '0')->where('publish_date', '!=', '')
        ->orderBy('publish_date', 'desc')->pluck('publish_date')->toArray();
        if( !empty($createdAt) ) {
            foreach( $createdAt as $v ) {
                $onlyYear = Carbon::createFromFormat('Y-m-d H:i:s', $v)->year;
                array_push( $yearArr , $onlyYear );
            }
        }
        $uniqueYear = array_unique( $yearArr );
        $DataBag['yearList'] = $uniqueYear;

        $DataBag['extraContent'] = \App\Models\Media\MediaExtraContent::where('type', '=', 'EVENT')->first();

        $DataBag['page_metadata'] = $DataBag['extraContent'];

        return view('front_end.event.event_list', $DataBag);   
    }


    public function eventContent($lng, $slug) {

        $DataBag = array();
        
        $DataBag['lng'] = $lng;
        $getlngid = getLngIDbyCode( $lng );
        $DataBag['lng_id'] = $getlngid;

        $data = \App\Models\Events::with(['pageBuilderContent'])
        ->where('language_id', '=', $getlngid)->where('slug', '=', $slug)->first();
        
        $DataBag['allData'] = $data;

        $DataBag['page_metadata'] = $DataBag['allData'];

        $eventCats = \App\Models\EventCategories::where('status', '=', '1')->orderBy('created_at', 'desc')->get();
        $DataBag['listCats'] = $eventCats;

        $yearArr = array();
        $createdAt = \App\Models\Events::where('status', '=', '1')
        ->where('parent_language_id', '=', '0')->where('publish_date', '!=', '')
        ->orderBy('publish_date', 'desc')->pluck('publish_date')->toArray();
        if( !empty($createdAt) ) {
            foreach( $createdAt as $v ) {
                $onlyYear = Carbon::createFromFormat('Y-m-d H:i:s', $v)->year;
                array_push( $yearArr , $onlyYear );
            }
        }
        $uniqueYear = array_unique( $yearArr );
        $DataBag['yearList'] = $uniqueYear;
        
        return view('front_end.event.content_page', $DataBag);   
    }
    /************************************************END EVENTS*************************************************/


    /************************************************PROFILE*************************************************/
    public function profileLists( $lng ) {

        $DataBag = array();
        
        $DataBag['lng'] = $lng;
        $getlngid = getLngIDbyCode( $lng );
        $DataBag['lng_id'] = $getlngid;

        $data = \App\Models\PeoplesProfile\PeopleProfileCategories::with(['orderByDisplay'])->where('language_id', '=', $getlngid)
        ->where('status', '=', '1')->orderBy('display_order', 'asc')->get();
        
        $DataBag['allData'] = $data;

        $DataBag['extraContent'] = \App\Models\Media\MediaExtraContent::where('type', '=', 'PROFILE')->first();

        $DataBag['page_metadata'] = $DataBag['extraContent'];
        
        return view('front_end.people_profile.profile_list', $DataBag);
    }

    public function profileContent( $lng, $slug ) {
        
        $DataBag = array();
        
        $DataBag['lng'] = $lng;
        $getlngid = getLngIDbyCode( $lng );
        $DataBag['lng_id'] = $getlngid; 

        $device = 1;
        $agent = new Agent();
        
        if( $agent->isMobile() ) {
            
            $device = 2;
        }
        $DataBag['device'] = $device;
        
        $data = \App\Models\PeoplesProfile\PeoplesProfile::with(['pageBuilderContent'])
        ->where('language_id', '=', $getlngid)->where('slug', '=', $slug)->first();
        
        $DataBag['allData'] = $data;

        $DataBag['page_metadata'] = $DataBag['allData'];
        
        return view('front_end.people_profile.profile', $DataBag);
        
    }
    /************************************************END PROFILE*************************************************/


    /************************************************Distributor Section****************************************/
    public function distributorMap($lng) {
        
        $DataBag = array();
        
        $DataBag['lng'] = $lng;
        $getlngid = getLngIDbyCode( $lng );
        $DataBag['lng_id'] = $getlngid;

        $device = 1;
        $agent = new Agent();
        if( $agent->isMobile() ) {
            
            $device = 2;
        }
        $DataBag['device'] = $device;

        $DataBag['extraContent'] = \App\Models\Media\MediaExtraContent::where('type', '=', 'DISTRIBUTOR')->first();
        $DataBag['page_metadata'] = $DataBag['extraContent'];

        $DataBag['allContinents'] = \App\Models\Distributor\DistributorCategories::where('status', '=', '1')
        ->where('parent_language_id', '=', '0')->orderBy('name', 'asc')->get();

        $map = DB::table('distributor_categories_map as dcm')
        ->join('distributor_category as dcat', 'dcat.id', '=', 'dcm.distributor_category_id')
        ->join('distributor', 'distributor.id', '=', 'dcm.distributor_id')
        ->join('distributor_contents as dc', 'dc.distributor_id', '=', 'distributor.id')
        ->where('dc.latitude', '!=', '')->where('dc.longitude', '!=', '');
        
        $mapData = $map->select('dc.name as name', 'dc.latitude as lat', 'dc.longitude as lng', 'dc.address as address', 'dc.slug as branch_slug', 'dc.branch_type', 'distributor.slug as country_slug', 'dcat.slug as continent_slug')->get();

        $DataBag['map_data'] = json_encode($mapData);

        return view('front_end.distributor.distributor_category', $DataBag);
    }

    public function distributorMapFilter($lng, Request $request) {

        $DataBag = array();

        $continent_id = 0;
        $country_id = 0;
        $branch_id = 0;

        $countries = array();
        $branches = array();

        $click_on = trim($request->get('click_on'));

        if( $request->has('continent_id') ) {
            $continent_id = trim($request->get('continent_id'));
            $query = \App\Models\Distributor\Distributor::where('status', '=', '1');
            $query = $query->where( function($query) use($continent_id) {
                $query = $query->whereHas('distrCategorytIds', function ($query) use($continent_id) {
                    $query->where( 'distributor_category_id', '=', $continent_id);
                });
            } );
            $countries = $query->orderBy('name', 'asc')->get();
        }
        if( $request->has('country_id') ) {
            $country_id = trim($request->get('country_id'));
            $branches = \App\Models\Distributor\DistributorContents::where('status', '=', '1')
            ->where('distributor_id', '=', $country_id)->orderBy('name', 'asc')->get();
        }
        if( $request->has('branch_id') ) {
            $branch_id = trim($request->get('branch_id'));
        }

        $map = DB::table('distributor_categories_map as dcm')->where('dcm.distributor_category_id', '=', $continent_id)
        ->join('distributor_category as dcat', 'dcat.id', '=', 'dcm.distributor_category_id')
        ->join('distributor', 'distributor.id', '=', 'dcm.distributor_id')
        ->join('distributor_contents as dc', 'dc.distributor_id', '=', 'distributor.id')
        ->where('dc.latitude', '!=', '')->where('dc.longitude', '!=', '');

        if($country_id != 0) {
            $map = $map->where('dc.distributor_id', '=', $country_id);
        }

        if($branch_id != 0) {
            $map = $map->where('dc.id', '=', $branch_id);
        }
        
        $mapData = $map->select('dc.name as name', 'dc.latitude as lat', 'dc.longitude as lng', 'dc.address as address', 'dc.branch_type', 'dc.slug as branch_slug', 'distributor.slug as country_slug', 'dcat.slug as continent_slug')->get();

        $DataBag['countries'] = $countries;
        $DataBag['branches'] = $branches;
        $DataBag['click_on'] = $click_on;
        $DataBag['map_data'] = $mapData;

        return json_encode($DataBag);
    }

    public function distributorCategory($lng, $cat_slug) {

        $DataBag = array();
        
        $DataBag['lng'] = $lng;
        $getlngid = getLngIDbyCode( $lng );
        $DataBag['lng_id'] = $getlngid;

        $device = 1;
        $agent = new Agent();
        
        if( $agent->isMobile() ) {
            
            $device = 2;
        }
        $DataBag['device'] = $device;

        $DataBag['extraContent'] = \App\Models\Media\MediaExtraContent::where('type', '=', 'DISTRIBUTOR')->first();
        $DataBag['page_metadata'] = $DataBag['extraContent'];

        $DataBag['allContinents'] = \App\Models\Distributor\DistributorCategories::where('status', '=', '1')
        ->where('parent_language_id', '=', '0')->orderBy('name', 'asc')->get();

        $map = DB::table('distributor_categories_map as dcm')
        ->join('distributor_category as dcat', 'dcat.id', '=', 'dcm.distributor_category_id')
        ->join('distributor', 'distributor.id', '=', 'dcm.distributor_id')
        ->join('distributor_contents as dc', 'dc.distributor_id', '=', 'distributor.id')
        ->where('dc.latitude', '!=', '')->where('dc.longitude', '!=', '');
        
        $mapData = $map->select('dc.name as name', 'dc.latitude as lat', 'dc.longitude as lng', 'dc.address as address', 'dc.slug as branch_slug', 'dc.branch_type', 'distributor.slug as country_slug', 'dcat.slug as continent_slug')->get();

        $DataBag['map_data'] = json_encode($mapData);

        return view('front_end.distributor.distributor_category', $DataBag);
    }

    public function distributor($lng, $cat_slug, $distbr_slug) {
        $DataBag = array();
        
        $DataBag['lng'] = $lng;
        $getlngid = getLngIDbyCode( $lng );
        $DataBag['lng_id'] = $getlngid;

        $device = 1;
        $agent = new Agent();
        
        if( $agent->isMobile() ) {
            
            $device = 2;
        }
        $DataBag['device'] = $device;

        $data = \App\Models\Distributor\Distributor::with(['pageBuilderContent'])
        ->where('language_id', '=', $getlngid)->where('language_id', '=', $getlngid)->where('slug', '=', $distbr_slug)->first();

        $DataBag['allData'] = $data;

        $DataBag['page_metadata'] = $DataBag['allData'];
        
        return view('front_end.distributor.distributor', $DataBag);
    }

    public function distributorContent($lng, $cat_slug, $distbr_slug, $cont_slug) {
        $DataBag = array();
        
        $DataBag['lng'] = $lng;
        $getlngid = getLngIDbyCode( $lng );
        $DataBag['lng_id'] = $getlngid;

        $device = 1;
        $agent = new Agent();
        
        if( $agent->isMobile() ) {
            
            $device = 2;
        }
        $DataBag['device'] = $device;

        $data = \App\Models\Distributor\DistributorContents::with(['pageBuilderContent'])
        ->where('language_id', '=', $getlngid)->where('slug', '=', $cont_slug)->first();
        
        $DataBag['allData'] = $data;

        $DataBag['page_metadata'] = $DataBag['allData'];
        
        return view('front_end.distributor.distributor_content', $DataBag);
    }
    /************************************************END Distributor Section**********************************/

    public function landingPages( $lng, $slug ) {

        $page = \App\Models\LandingPages::where('slug', '=', $slug)->first();
        if( !empty($page) ) {
            $dirName = $page->dir_name;
            $landingPages_folder = public_path('landing_pages/' . $dirName);
            $asset = asset('public/landing_pages/' . $dirName);            

            $pageContent = file_get_contents($landingPages_folder.'/index.html');

            preg_match_all('/href=["\']?([^"\'>]+)["\']?/', $pageContent, $arr, PREG_PATTERN_ORDER);

            if( !empty( $arr ) ) {
                foreach( array_unique($arr[1]) as $v ) {
                    if ( (strpos($v, 'http') === false) && (strpos($v, '#') === false) ) {
                        $pageContent = str_replace($v, $asset.'/'.$v, $pageContent);
                    }
                }
            }

            preg_match_all('/src=["\']?([^"\'>]+)["\']?/', $pageContent, $arr, PREG_PATTERN_ORDER);

            if( !empty( $arr ) ) {
                foreach( array_unique($arr[1]) as $v ) {
                    if ( (strpos($v, 'http') === false) && (strpos($v, '#') === false) ) {
                        $pageContent = str_replace($v, $asset.'/'.$v, $pageContent);
                    }
                }
            }

            echo $pageContent;
        }
    }

    /************ GLOBAL SEARCH ******************/

    public function globalSearch(Request $request, $lng) {

        $DataBag = array();

        if( isset($_GET['q']) ){
            $search_string = trim( $request->get('q') );
        } else {
            $search_string = '';
        }
        $search_string = str_replace('"','',$search_string);

        $results_per_page = 25;
        $current_page = ((isset($_GET['page']) && !empty($_GET['page'])) ? $_GET['page'] : 1);
        $offset = (($current_page > 1) ? ($current_page - 1) * $results_per_page : 0);


        $query = "SELECT * FROM 
                    (
                        (SELECT pro.id, pro.name, pro.description, pro.slug, pro.status, 'product' as type FROM products as pro )
                        UNION
                        (SELECT prc.id, prc.name, prc.description, prc.slug, prc.status, 'product_cat' as type FROM product_categories as prc )
                        UNION
                        (SELECT con.id, con.name, con.description, con.slug, con.status, 'content' as type FROM contents as con )
                        UNION
                        (SELECT indus.id, indus.name, indus.description, indus.slug, indus.status, 'industry' as type FROM industries as indus )
                        UNION
                        (SELECT distrb.id, distrb.name, distrb.description, distrb.slug, distrb.status, 'distributor' as type FROM distributor as distrb )
                        UNION
                        (SELECT art.id, art.name, art.description, art.slug, art.status, 'article' as type FROM articles as art )
                        UNION
                        (SELECT evt.id, evt.name, evt.description, evt.slug, evt.status, 'event' as type FROM events as evt )
                        UNION
                        (SELECT pepro.id, pepro.name, pepro.description, pepro.slug, pepro.status, 'people' as type FROM peoples_profile as pepro )
                        UNION
                        (SELECT discat.id, discat.name, discat.description, discat.slug, discat.status, 'distributor_cat' as type FROM distributor_category as discat )
                    ) results WHERE status = '1' AND name LIKE '%$search_string%' ORDER BY name,'$search_string' ASC";

        $query_page = "SELECT * FROM 
                    (
                        (SELECT pro.id, pro.name, pro.description, pro.slug, pro.status, 'product' as type FROM products as pro )
                        UNION
                        (SELECT prc.id, prc.name, prc.description, prc.slug, prc.status, 'product_cat' as type FROM product_categories as prc )
                        UNION
                        (SELECT con.id, con.name, con.description, con.slug, con.status, 'content' as type FROM contents as con )
                        UNION
                        (SELECT indus.id, indus.name, indus.description, indus.slug, indus.status, 'industry' as type FROM industries as indus )
                        UNION
                        (SELECT distrb.id, distrb.name, distrb.description, distrb.slug, distrb.status, 'distributor' as type FROM distributor as distrb )
                        UNION
                        (SELECT art.id, art.name, art.description, art.slug, art.status, 'article' as type FROM articles as art )
                        UNION
                        (SELECT evt.id, evt.name, evt.description, evt.slug, evt.status, 'event' as type FROM events as evt )
                        UNION
                        (SELECT pepro.id, pepro.name, pepro.description, pepro.slug, pepro.status, 'people' as type FROM peoples_profile as pepro )
                        UNION
                        (SELECT discat.id, discat.name, discat.description, discat.slug, discat.status, 'distributor_cat' as type FROM distributor_category as discat )
                    ) results WHERE status = '1' AND name LIKE '%$search_string%' ORDER BY name,'$search_string' ASC LIMIT $offset,$results_per_page";


        $results = DB::select(DB::raw($query));
        $results_page = DB::select(DB::raw($query_page));
        $options['path'] = 'search';
        $pagination = new Paginator($results, count($results), $results_per_page,$current_page,$options);

        $DataBag['allData'] = $results_page;
        $DataBag['pagination'] = $pagination;

        return view('front_end.search', $DataBag);
    }


    public function notFound($lng) {

        return view('errors.404');
    } 
}
