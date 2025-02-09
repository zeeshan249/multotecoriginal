<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CmsLinks;
use App\Models\Media\ImageCategories;
use App\Models\Media\ImageCategoryMap;
use App\Models\Media\ImageGalleries;
use App\Models\Media\ImageGalleryMap;
use App\Models\Media\Images;
use App\Models\Media\VideoCategories;
use App\Models\Media\Videos;
use App\Models\Media\VideoCategoriesMap;
use App\Models\Media\FilesMaster;
use App\Models\Media\FileCategories;
use App\Models\Media\FileCategoriesMap;
use App\Models\Media\MediaExtraContent;
use App\Models\Languages;
use File;
use Storage;
use Image;
use Auth;
use DB;

class MediaController extends Controller
{
    
    public function all_images() {
    	$DataBag = array();
    	$DataBag['parentMenu'] = 'media';
    	$DataBag['subMenu'] = 'image';
    	$DataBag['childMenu'] = 'allImgs';
        $DataBag['groups'] = ImageCategories::where('status', '=', '1')
        ->where('parent_category_id', '=', '0')->orderBy('name', 'asc')->get();

        $query = Images::where('status', '!=', '3');

        if( isset($_GET['status']) && $_GET['status'] != '' && $_GET['status'] != '0' && $_GET['status'] != null ) {
            $query = $query->where('status', '=', trim($_GET['status']));
        } 
        if( isset($_GET['src_txt']) && $_GET['src_txt'] != '' && $_GET['src_txt'] != null ) {
            $query = $query->where( function($query) {
                $query = $query->where('name', 'like', '%'.trim($_GET['src_txt']).'%');
                $query = $query->orWhere('alt_title', 'like', '%'.trim($_GET['src_txt']).'%');
                $query = $query->orWhere('title', 'like', '%'.trim($_GET['src_txt']).'%');
            } );
        }
        if( isset($_GET['group_id']) && $_GET['group_id'] != '' && $_GET['group_id'] != '0' && $_GET['group_id'] != null ) {
            $query = $query->where( function($query) {
                $query = $query->whereHas('Group_Count', function ($query) {
                    $query->where( 'image_category_id', '=', trim($_GET['group_id']) );
                });
            } );
        }
        $allImages = $query->orderBy('id', 'desc')->paginate(25);
    	$DataBag['allImages'] = $allImages;
    	return view('dashboard.media.image.index', $DataBag);
    }

    public function add() {
    	$DataBag = array();
    	$DataBag['parentMenu'] = 'media';
    	$DataBag['subMenu'] = 'image';
    	$DataBag['childMenu'] = 'addImg';
    	
        $DataBag['allImgCats'] = ImageCategories::where('status', '!=', '3')
        ->where('parent_category_id', '=', '0')->orderBy('name', 'asc')->get();

    	return view('dashboard.media.image.add_edit', $DataBag);
    }

    public function upload(Request $request) {

    	if( $request->hasFile('images') ) {
            
    		foreach( $request->file('images') as $img ) {
    			$Images = new Images;
	    		$real_path = $img->getRealPath();
	            $file_orgname = $img->getClientOriginalName();
	            $file_size = $img->getClientSize();
	            $file_ext = strtolower($img->getClientOriginalExtension());
	            $file_newname = "media"."_".md5(microtime(TRUE).rand(123, 999)).".".$file_ext;

	            $destinationPath = public_path('/uploads/files/media_images');
	            $thumb_path = $destinationPath."/thumb";
	            
	            $imgObj = Image::make($real_path);
	        	$imgObj->resize(100, 100, function ($constraint) {
			    	$constraint->aspectRatio();
				})->save($thumb_path.'/'.$file_newname);

	        	$img->move($destinationPath, $file_newname);
	        	
                $Images->image = $file_newname;
	        	$Images->size = $file_size;
	        	$Images->extension = $file_ext;

                $Images->name = trim($request->input('name'));
                $Images->alt_title = trim($request->input('alt_title'));
                $Images->caption = trim($request->input('caption'));
                $Images->title = trim($request->input('title'));
                $Images->description = trim($request->input('description'));
                $Images->status = trim($request->input('status'));

	        	$Images->created_by = Auth::user()->id;
                
                if( $Images->save() ) {
                    $image_id = $Images->id;

                    $ImageCategoryMap = new ImageCategoryMap;
                    $ImageCategoryMap->image_category_id = trim( $request->input('image_category_id') );
                    $ImageCategoryMap->image_subcategory_id = trim( $request->input('image_subcategory_id') );
                    $ImageCategoryMap->image_id = $image_id;
                    $ImageCategoryMap->save();     
                }
    		}
            
            return back()->with('msg', 'Images Uploaded Successfully.')
            ->with('msg_class', 'alert alert-success');
    	}

    	return back();
    }

    public function imgDetails($image_id) {
        $DataBag = array();
        $DataBag['parentMenu'] = 'media';
        $DataBag['subMenu'] = 'image';
        $DataBag['childMenu'] = 'allImgs';
        $DataBag['imgInfo'] = $imgInfo = Images::findOrFail($image_id);
        $DataBag['allImgCats'] = ImageCategories::where('status', '!=', '3')
        ->where('parent_category_id', '=', '0')->orderBy('name', 'asc')->get();
        
        if( !empty($imgInfo) && isset($imgInfo->getCatSubcat) ) {
            
            $DataBag['seleSubCats'] = ImageCategories::where('status', '!=', '3')
            ->where('parent_category_id', '=', $imgInfo->getCatSubcat->image_category_id)
            ->where('parent_category_id', '!=', '0')->orderBy('name', 'asc')->get();
        }
        return view('dashboard.media.image.add_edit', $DataBag);
    }

    public function imgDetailsUpdate(Request $request, $image_id) {

        //dd($request);
        $Images = Images::find($image_id);
        if( isset($Images) && !empty($Images) ) {
            $Images->name = trim($request->input('name'));
            $Images->alt_title = trim($request->input('alt_title'));
            $Images->caption = trim($request->input('caption'));
            $Images->title = trim($request->input('title'));
            $Images->description = trim($request->input('description'));
            $Images->status = trim($request->input('status'));
            $Images->updated_by = Auth::user()->id;
            $Images->updated_at = date('Y-m-d H:i:s');

            if( $request->hasFile('image') ) {

                $img = $request->file('image');
                $real_path = $img->getRealPath();
                $file_orgname = $img->getClientOriginalName();
                $file_size = $img->getClientSize();
                $file_ext = strtolower($img->getClientOriginalExtension());
                $file_newname = "media"."_".md5(microtime(TRUE).rand(123, 999)).".".$file_ext;

                $destinationPath = public_path('/uploads/files/media_images');
                $thumb_path = $destinationPath."/thumb";
                
                $imgObj = Image::make($real_path);
                $imgObj->resize(200, 150, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($thumb_path.'/'.$file_newname);

                $img->move($destinationPath, $file_newname);

                $Images->image = $file_newname;
                $Images->size = $file_size;
                $Images->extension = $file_ext;
            }
            
            if( $Images->save() ) {

                $ck = ImageCategoryMap::where('image_id' ,'=', $image_id)->first();
                if( !empty($ck) ) {

                    $updateArr = array();
                    $updateArr['image_category_id'] = trim( $request->input('image_category_id') );
                    $updateArr['image_subcategory_id'] = trim( $request->input('image_subcategory_id') );
                    ImageCategoryMap::where('image_id', '=', $image_id)->update( $updateArr );
                } else { 
                    $ImageCategoryMap = new ImageCategoryMap;
                    $ImageCategoryMap->image_category_id = trim( $request->input('image_category_id') );
                    $ImageCategoryMap->image_subcategory_id = trim( $request->input('image_subcategory_id') );
                    $ImageCategoryMap->image_id = $image_id;
                    $ImageCategoryMap->save();
                }
                    
                return back()->with('msg', 'Image Details Updated Succesfully.')->with('msg_class', 'alert alert-success');
            } 
        } 

        return back();
    }

    public function imgDelete( $image_id ) {
        $ck = Images::find($image_id);
        if( isset($ck) && !empty($ck) ) {
            $image = $ck->image;
            $res = $ck->delete();
            if( isset($res) && $res == 1 ) {
                ImageGalleryMap::where('image_id', '=', $image_id)->delete();
                ImageCategoryMap::where('image_id', '=', $image_id)->delete();
            }

            File::delete(['public/uploads/files/media_images/thumb/'. $image, 'public/uploads/files/media_images/'. $image]);
            return back()->with('msg', 'Image Deleted Successfully.')->with('msg_class', 'alert alert-success');
        }

        return back();
    }

    public function imgMultiDelete(Request $request) {
        if( $request->has('imgIds') && !empty( $request->input('imgIds') ) ) {
            $imageFileArray = array();
            foreach( $request->input('imgIds') as $id ) {
                $ck = Images::find($id);
                $image = "public/uploads/files/media_images/".$ck->image;
                array_push( $imageFileArray, $image );
                $image_thumb = "public/uploads/files/media_images/thumb/".$ck->image;
                array_push( $imageFileArray, $image_thumb );
                $res = $ck->delete();
                if( isset($res) && $res == 1 ) {
                    ImageGalleryMap::where('image_id', '=', $id)->delete();
                    ImageCategoryMap::where('image_id', '=', $id)->delete();
                }
            }

            File::delete($imageFileArray);
            return back()->with('msg', 'Images Deleted Successfully.')->with('msg_class', 'alert alert-success');
        }

        return back();
    }

    public function img_categories() {
    	$DataBag = array();
    	$DataBag['parentMenu'] = 'media';
    	$DataBag['subMenu'] = 'image';
    	$DataBag['childMenu'] = 'mngImgCats';
    	$DataBag['allCats'] = ImageCategories::with(['parent'])->where('status', '!=', '3')->orderBy('created_at', 'desc')->get();
    	return view('dashboard.media.image.image_categories', $DataBag);
    }

    public function imgCat_Create() {
    	$DataBag = array();
    	$DataBag['parentMenu'] = 'media';
    	$DataBag['subMenu'] = 'image';
    	$DataBag['childMenu'] = 'mngImgCats';
        $DataBag['allFCats'] = ImageCategories::where('status', '!=', '3')->where('parent_category_id', '=', '0')->get();
    	return view('dashboard.media.image.image_category_create', $DataBag);
    }

    public function imgCat_Save(Request $request) {
    	$ImageCategories = new ImageCategories;
    	$ImageCategories->name = trim( ucfirst($request->input('name')) );
        $ImageCategories->slug = trim($request->input('slug'));
        $ImageCategories->description = trim( htmlentities( $request->input('description'), ENT_QUOTES) );
        $ImageCategories->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
        $ImageCategories->mob_page_content = trim( htmlentities($request->input('mob_page_content'), ENT_QUOTES) );

        $display_order = 0;
        $show_in_gallery = 0;

        if( trim($request->input('display_order')) != '' ) {
            $display_order = trim($request->input('display_order'));
        }
        if( $request->has('show_in_gallery') ) {
            $show_in_gallery = trim($request->input('show_in_gallery'));
        }

        $ImageCategories->display_order = $display_order;
        $ImageCategories->show_in_gallery = $show_in_gallery;
        
        $ImageCategories->meta_title = trim($request->input('meta_title'));
        $ImageCategories->meta_desc = trim($request->input('meta_desc'));
        $ImageCategories->meta_keyword = trim($request->input('meta_keyword'));
        $ImageCategories->canonical_url = trim($request->input('canonical_url'));
        $ImageCategories->lng_tag = trim($request->input('lng_tag'));
        $ImageCategories->follow = trim($request->input('follow'));
        $ImageCategories->index_tag = trim($request->input('index_tag'));

        $ImageCategories->parent_category_id = trim($request->input('parent_category_id'));
        $ImageCategories->status = trim($request->input('status'));
        $ImageCategories->created_by = Auth::user()->id;
    	$res = $ImageCategories->save();
    	if( $res ) {

            $image_category_id = $ImageCategories->id;

            $CmsLinks = new CmsLinks;
            $CmsLinks->table_id = $image_category_id;
            $CmsLinks->slug_url = trim($request->input('slug'));
            $CmsLinks->table_type = 'IMAGE_CATEGORY';
            $CmsLinks->save();

    		return back()->with('msg', 'Image Category Created Successfully.')
    		->with('msg_class', 'alert alert-success');
    	} else {
    		return back()->with('msg', 'Something Went Wrong.')
    		->with('msg_class', 'alert alert-danger');
    	}

        return back();
    }

    public function imgCat_Edit($id) {
    	$DataBag = array();
    	$DataBag['parentMenu'] = 'media';
    	$DataBag['subMenu'] = 'image';
    	$DataBag['childMenu'] = 'mngImgCats';
    	$DataBag['imgcat'] = ImageCategories::findOrFail($id);
        $DataBag['allFCats'] = ImageCategories::where('status', '!=', '3')->where('parent_category_id', '=', '0')
        ->where('id', '!=', $id)->get();
    	return view('dashboard.media.image.image_category_create', $DataBag);
    }

    public function imgCat_Update(Request $request, $id) {
    	$ImageCategories = ImageCategories::find($id);
    	$ImageCategories->name = trim( ucfirst($request->input('name')) );
        $ImageCategories->slug = trim($request->input('slug'));
        $ImageCategories->description = trim($request->input('description'));
        $ImageCategories->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
        $ImageCategories->mob_page_content = trim( htmlentities($request->input('mob_page_content'), ENT_QUOTES) );

        $display_order = 0;
        $show_in_gallery = 0;

        if( trim($request->input('display_order')) != '' ) {
            $display_order = trim($request->input('display_order'));
        }
        if( $request->has('show_in_gallery') ) {
            $show_in_gallery = trim($request->input('show_in_gallery'));
        }
        
        $ImageCategories->display_order = $display_order;
        $ImageCategories->show_in_gallery = $show_in_gallery;
        
        $ImageCategories->meta_title = trim($request->input('meta_title'));
        $ImageCategories->meta_desc = trim($request->input('meta_desc'));
        $ImageCategories->meta_keyword = trim($request->input('meta_keyword'));
        $ImageCategories->canonical_url = trim($request->input('canonical_url'));
        $ImageCategories->lng_tag = trim($request->input('lng_tag'));
        $ImageCategories->follow = trim($request->input('follow'));
        $ImageCategories->index_tag = trim($request->input('index_tag'));

        $ImageCategories->parent_category_id = trim($request->input('parent_category_id'));
        $ImageCategories->status = trim($request->input('status'));
        $ImageCategories->updated_by = Auth::user()->id;
        if( $ImageCategories->save() ) {
            CmsLinks::where('table_type', '=', 'IMAGE_CATEGORY')->where('table_id', '=', $id)
            ->update( ['slug_url' => trim($request->input('slug'))] );

            return back()->with('msg', 'Image Category Updated Successfully.')
            ->with('msg_class', 'alert alert-success');
        }
        return back()->with('msg', 'Something Went Wrong')
        ->with('msg_class', 'alert alert-danger');
    }

    public function imgCat_Delete($id) {
    	$res = ImageCategories::findOrFail($id);
    	$res->status = '3';
    	$del = $res->save(); 
    	if( $res ) {

            delete_navigation($id, 'IMAGE_CATEGORY');

            CmsLinks::where('table_type', '=', 'IMAGE_CATEGORY')->where('table_id', '=', $id)->delete();
            
            ImageCategoryMap::where('image_category_id', '=', $id)->orWhere('image_subcategory_id', '=', $id)->delete();

    		return back()->with('msg', 'Image Category Deleted Successfully.')
    		->with('msg_class', 'alert alert-success');
    	} else {
    		return back()->with('msg', 'Something Went Wrong.')
    		->with('msg_class', 'alert alert-danger');
    	}
    }

    /************************************** VIDEO **************************************************/

    public function all_videoCats() {
        $DataBag = array();
        $DataBag['parentMenu'] = 'media';
        $DataBag['subMenu'] = 'video';
        $DataBag['childMenu'] = 'vidCats';
        $DataBag['allVidCats'] = VideoCategories::where('status', '!=', '3')->orderBy('created_at', 'desc')->get();
        return view('dashboard.media.video.all_categories', $DataBag);
    }

    public function add_videoCats() {
        $DataBag = array();
        $DataBag['parentMenu'] = 'media';
        $DataBag['subMenu'] = 'video';
        $DataBag['childMenu'] = 'vidCats';
        $DataBag['parentCats'] = VideoCategories::where('status', '!=', '3')->where('parent_category_id', '=', '0')
        ->orderBy('name', 'asc')->get();
        return view('dashboard.media.video.create_category', $DataBag); 
    }

    public function save_videoCats(Request $request) {
        $VideoCategories = new VideoCategories;
        $VideoCategories->name = trim( ucfirst($request->input('name')) );
        $VideoCategories->slug = trim( ucfirst($request->input('slug')) );
        $VideoCategories->description = htmlentities( trim($request->input('description')), ENT_QUOTES );
        $VideoCategories->page_content = htmlentities( trim($request->input('page_content')), ENT_QUOTES );
        $VideoCategories->mob_page_content = htmlentities( trim($request->input('mob_page_content')), ENT_QUOTES );
        $VideoCategories->status = $request->input('status');
        $VideoCategories->created_by = Auth::user()->id;

        $display_order = 0;
        $show_in_gallery = 0;

        if( trim($request->input('display_order')) != '' ) {
            $display_order = trim($request->input('display_order'));
        }
        if( $request->has('show_in_gallery') ) {
            $show_in_gallery = trim($request->input('show_in_gallery'));
        }
        
        $VideoCategories->display_order = $display_order;
        $VideoCategories->show_in_gallery = $show_in_gallery;

        $VideoCategories->meta_title = trim($request->input('meta_title'));
        $VideoCategories->meta_desc = trim($request->input('meta_desc'));
        $VideoCategories->meta_keyword = trim($request->input('meta_keyword'));
        $VideoCategories->canonical_url = trim($request->input('canonical_url'));
        $VideoCategories->lng_tag = trim($request->input('lng_tag'));
        $VideoCategories->follow = trim($request->input('follow'));
        $VideoCategories->index_tag = trim($request->input('index_tag'));

        $VideoCategories->parent_category_id = trim($request->input('parent_category_id'));

        if( $VideoCategories->save() ) {

            $video_cat_id = $VideoCategories->id;

            $CmsLinks = new CmsLinks;
            $CmsLinks->table_id = $video_cat_id;
            $CmsLinks->slug_url = trim($request->input('slug'));
            $CmsLinks->table_type = 'VIDEO_CATEGORY';
            $CmsLinks->save();

            return back()->with('msg', 'Video Category Created Successfully.')
            ->with('msg_class', 'alert alert-success');
        }
        return back()->with('msg', 'Something Went Wrong.')
        ->with('msg_class', 'alert alert-danger');
    }

    public function del_videoCats($vid_category_id) {
        $VideoCategories = VideoCategories::findOrFail($vid_category_id);
        $VideoCategories->status = '3';
        if( $VideoCategories->save() ) {

            delete_navigation($vid_category_id, 'VIDEO_CATEGORY');
            CmsLinks::where('table_type', '=', 'VIDEO_CATEGORY')->where('table_id', '=', $vid_category_id)->delete();
            VideoCategoriesMap::where('video_category_id', '=', $vid_category_id)->delete();
            return back()->with('msg', 'Video Category Deleted Successfully.')
            ->with('msg_class', 'alert alert-success');
        }

        return back()->with('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger');
    }

    public function edit_videoCats($vid_category_id) {
        $DataBag = array();
        $DataBag['parentMenu'] = 'media';
        $DataBag['subMenu'] = 'video';
        $DataBag['childMenu'] = 'vidCats';
        $DataBag['vidCat'] = VideoCategories::findOrFail($vid_category_id);
        $DataBag['parentCats'] = VideoCategories::where('status', '!=', '3')->where('parent_category_id', '=', '0')
        ->where('id', '!=', $vid_category_id)->get();
        return view('dashboard.media.video.create_category', $DataBag);  
    }

    public function update_videoCats(Request $request, $vid_category_id) {
        $VideoCategories = VideoCategories::find( $vid_category_id );
        $VideoCategories->slug = trim( ucfirst($request->input('slug')) );
        $VideoCategories->description = htmlentities( trim($request->input('description')), ENT_QUOTES );
        $VideoCategories->page_content = htmlentities( trim($request->input('page_content')), ENT_QUOTES );
        $VideoCategories->mob_page_content = htmlentities( trim($request->input('mob_page_content')), ENT_QUOTES );
        $VideoCategories->status = $request->input('status');
        $VideoCategories->updated_by = Auth::user()->id;

        $display_order = 0;
        $show_in_gallery = 0;

        if( trim($request->input('display_order')) != '' ) {
            $display_order = trim($request->input('display_order'));
        }
        if( $request->has('show_in_gallery') ) {
            $show_in_gallery = trim($request->input('show_in_gallery'));
        }
        
        $VideoCategories->display_order = $display_order;
        $VideoCategories->show_in_gallery = $show_in_gallery;

        $VideoCategories->meta_title = trim($request->input('meta_title'));
        $VideoCategories->meta_desc = trim($request->input('meta_desc'));
        $VideoCategories->meta_keyword = trim($request->input('meta_keyword'));
        $VideoCategories->canonical_url = trim($request->input('canonical_url'));
        $VideoCategories->lng_tag = trim($request->input('lng_tag'));
        $VideoCategories->follow = trim($request->input('follow'));
        $VideoCategories->index_tag = trim($request->input('index_tag'));

        $VideoCategories->parent_category_id = trim($request->input('parent_category_id'));

        if( $VideoCategories->save() ) {

            CmsLinks::where('table_type', '=', 'VIDEO_CATEGORY')->where('table_id', '=', $vid_category_id)
            ->update( ['slug_url' => trim($request->input('slug'))] );

            return back()->with('msg', 'Video Category Updated Successfully.')
            ->with('msg_class', 'alert alert-success');
        }
        return back()->with('msg', 'Something Went Wrong.')
        ->with('msg_class', 'alert alert-danger');
    }

    public function all_videos() {
        $DataBag = array();
        $DataBag['parentMenu'] = 'media';
        $DataBag['subMenu'] = 'video';
        $DataBag['childMenu'] = 'allVids';
        $DataBag['allVideos'] = Videos::where('status', '!=', '3')->orderBy('created_at', 'desc')->get();
        return view('dashboard.media.video.index', $DataBag);
    }

    public function add_video() {
        $DataBag = array();
        $DataBag['parentMenu'] = 'media';
        $DataBag['subMenu'] = 'video';
        $DataBag['childMenu'] = 'addVid';
        $DataBag['Cats'] = VideoCategories::where('status', '!=', '3')->where('parent_category_id', '=', '0')
        ->orderBy('name', 'asc')->get();
        return view('dashboard.media.video.add', $DataBag);
    }

    public function save_video(Request $request) {

        $Videos = new Videos;
        $Videos->name = trim( ucfirst($request->input('name')) );
        $Videos->title = trim( ucfirst($request->input('title')) );
        $Videos->slug = trim($request->input('slug'));
        $Videos->video_type = trim($request->input('video_type'));
        $Videos->video_script = htmlentities( trim($request->input('video_script')), ENT_QUOTES );
        $Videos->ytb_full_link = trim($request->input('video_link'));
        $youtubeVideo = explode( '?v=', trim($request->input('video_link')) );
        if( !empty($youtubeVideo) ) {
            $Videos->video_link = end( $youtubeVideo );
        }
        $Videos->video_caption = trim($request->input('video_caption'));
        $Videos->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
        $Videos->mob_page_content = trim( htmlentities($request->input('mob_page_content'), ENT_QUOTES) );
        
        $Videos->status = trim($request->input('status'));
        $Videos->created_by = Auth::user()->id;
        $Videos->short_code = "[#Video_".md5(microtime(TRUE).rand(123,999))."]";

        /*$Videos->meta_title = trim($request->input('meta_title'));
        $Videos->meta_desc = trim($request->input('meta_desc'));
        $Videos->meta_keyword = trim($request->input('meta_keyword'));
        $Videos->canonical_url = trim($request->input('canonical_url'));
        $Videos->lng_tag = trim($request->input('lng_tag'));
        $Videos->follow = trim($request->input('follow'));
        $Videos->index_tag = trim($request->input('index_tag'));*/
        

        if( $Videos->save() ) {
            $video_id = $Videos->id;

            /*$CmsLinks = new CmsLinks;
            $CmsLinks->table_id = $video_id;
            $CmsLinks->slug_url = trim($request->input('slug'));
            $CmsLinks->table_type = 'VIDEO';
            $CmsLinks->save();*/

            $VideoCategoriesMap = new VideoCategoriesMap;
            $VideoCategoriesMap->video_category_id = trim( $request->input('video_category_id') );
            $VideoCategoriesMap->video_subcategory_id = trim( $request->input('video_subcategory_id') );
            $VideoCategoriesMap->video_id = $video_id;
            $VideoCategoriesMap->save();

            
        return back()->with('msg', 'Video Created Successfully.')
        ->with('msg_class', 'alert alert-success');
        }
    return back()->with('msg', 'Something Went Wrong')
    ->with('msg_class', 'alert alert-danger');
    }

    public function del_video($video_id) {
        $Videos = Videos::findOrFail($video_id);
        $Videos->status = '3';
        if( $Videos->save() ) {
            CmsLinks::where('table_type', '=', 'VIDEO')->where('table_id', '=', $video_id)->delete();
            VideoCategoriesMap::where('video_id', '=', $video_id)->delete();
            return back()->with('msg', 'Video Deleted Successfully.')
            ->with('msg_class', 'alert alert-success');
        }

        return back()->with('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger');
    }

    public function edit_video($video_id) {
        $DataBag = array();
        $DataBag['parentMenu'] = 'media';
        $DataBag['subMenu'] = 'video';
        $DataBag['childMenu'] = 'addVid';
        $DataBag['video'] = $video = Videos::findOrFail($video_id);
        $DataBag['Cats'] = VideoCategories::where('status', '!=', '3')->where('parent_category_id', '=', '0')
        ->orderBy('name', 'asc')->get();

        if( !empty($video) && isset($video->getCatSubcat) ) {
            
            $DataBag['seleSubCats'] = VideoCategories::where('status', '!=', '3')
            ->where('parent_category_id', '=', $video->getCatSubcat->video_category_id)
            ->where('parent_category_id', '!=', '0')->orderBy('name', 'asc')->get();
        }

        return view('dashboard.media.video.add', $DataBag);
    }

    public function update_video(Request $request, $video_id) {

        $Videos = Videos::find($video_id);
        $Videos->name = trim( ucfirst($request->input('name')) );
        $Videos->title = trim( ucfirst($request->input('title')) );
        $Videos->slug = trim($request->input('slug'));
        $Videos->video_type = trim($request->input('video_type'));
        $Videos->video_script = htmlentities( trim($request->input('video_script')), ENT_QUOTES );
        $Videos->ytb_full_link = trim($request->input('video_link'));
        $youtubeVideo = explode( '?v=', trim($request->input('video_link')) );
        if( !empty($youtubeVideo) ) {
            $Videos->video_link = end( $youtubeVideo );
        }
        $Videos->video_caption = trim($request->input('video_caption'));
        $Videos->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
        $Videos->mob_page_content = trim( htmlentities($request->input('mob_page_content'), ENT_QUOTES) );
        
        $Videos->status = trim($request->input('status'));
        $Videos->updated_by = Auth::user()->id;

        /*$Videos->meta_title = trim($request->input('meta_title'));
        $Videos->meta_desc = trim($request->input('meta_desc'));
        $Videos->meta_keyword = trim($request->input('meta_keyword'));
        $Videos->canonical_url = trim($request->input('canonical_url'));
        $Videos->lng_tag = trim($request->input('lng_tag'));
        $Videos->follow = trim($request->input('follow'));
        $Videos->index_tag = trim($request->input('index_tag'));*/
        

        if( $Videos->save() ) {

            /*CmsLinks::where('table_type', '=', 'VIDEO')->where('table_id', '=', $video_id)
            ->update( ['slug_url' => trim($request->input('slug'))] );*/

            $ck = VideoCategoriesMap::where('video_id' ,'=', $video_id)->first();
            if( !empty($ck) ) {
                $updateArr = array();
                $updateArr['video_category_id'] = trim( $request->input('video_category_id') );
                $updateArr['video_subcategory_id'] = trim( $request->input('video_subcategory_id') );
                VideoCategoriesMap::where('video_id', '=', $video_id)->update( $updateArr );
            } else {
                $VideoCategoriesMap = new VideoCategoriesMap;
                $VideoCategoriesMap->video_category_id = trim( $request->input('video_category_id') );
                $VideoCategoriesMap->video_subcategory_id = trim( $request->input('video_subcategory_id') );
                $VideoCategoriesMap->video_id = $video_id;
                $VideoCategoriesMap->save();
            }

            
        return back()->with('msg', 'Video Updated Successfully.')
        ->with('msg_class', 'alert alert-success');
        }
    return back()->with('msg', 'Something Went Wrong')
    ->with('msg_class', 'alert alert-danger');
    }

/*********************************************** FILES ***************************************************/

    public function all_files() {
        $DataBag = array();
        $DataBag['parentMenu'] = 'media';
        $DataBag['subMenu'] = 'file';
        $DataBag['childMenu'] = 'allFls';
        $DataBag['allFcats'] = FileCategories::where('status', '=', '1')
        ->where('parent_category_id', '=', '0')->orderBy('name', 'asc')->get();

        $query = FilesMaster::where('status', '!=', '3')->where('file_type', '=', '0');

        if( isset($_GET['status']) && $_GET['status'] != '' && $_GET['status'] != '0' && $_GET['status'] != null ) {
            $query = $query->where('status', '=', trim($_GET['status']));
        } 
        if( isset($_GET['src_txt']) && $_GET['src_txt'] != '' && $_GET['src_txt'] != null ) {
            $query = $query->where('name', 'like', '%'.trim($_GET['src_txt']).'%')
            ->orWhere('title', 'like', '%'.trim($_GET['src_txt']).'%')
            ->orWhere('details', 'like', '%'.trim($_GET['src_txt']).'%')
            ->orWhere('caption', 'like', '%'.trim($_GET['src_txt']).'%');
        }
        if( isset($_GET['category_id']) && $_GET['category_id'] != '' && $_GET['category_id'] != '0' && $_GET['category_id'] != null ) {
            $query = $query->whereHas('Categories', function ($query) {
                $query->where( 'file_category_id', '=', trim($_GET['category_id']) );
            });
        }
        
        $DataBag['allFiles'] = $query->orderBy('created_at', 'desc')->paginate(25);
        
        return view('dashboard.media.file.index', $DataBag);
    }

    public function add_file() {
        $DataBag = array();
        $DataBag['parentMenu'] = 'media';
        $DataBag['subMenu'] = 'file';
        $DataBag['childMenu'] = 'addFl';
        $DataBag['allFlCats'] = FileCategories::where('status', '!=', '3')
        ->where('parent_category_id', '=', '0')->orderBy('name', 'asc')->get();
        $DataBag['languages'] = Languages::where('status', '=', '1')->get();
        return view('dashboard.media.file.add_edit', $DataBag);
    }

    public function upload_file(Request $request) {

        $a4_file_id = 0;
        $tema_file_id = 0;
        $img_thumb_name = 'pdf_icon.png';
        $file_newname = '';
        $file_ext = '';
        $file_size = '000';

        if( $request->hasFile('a4file') ) {
            $a4file = $request->file('a4file');
            $real_path = $a4file->getRealPath();
            $file_orgname = $a4file->getClientOriginalName();
            $file_size = $a4file->getClientSize();
            $file_ext = strtolower($a4file->getClientOriginalExtension());
            $file_newname = "file"."_".md5(microtime(TRUE).rand(123, 999)).".".$file_ext;
            $destinationPath = public_path('/uploads/files/media_files');
            $a4file->move($destinationPath, $file_newname);
            
            $FilesMaster1 = new FilesMaster;
            $FilesMaster1->file = $file_newname;
            $FilesMaster1->size = $file_size;
            $FilesMaster1->extension = $file_ext;
            $FilesMaster1->created_by = Auth::user()->id;
            $FilesMaster1->file_type = '1';
            if( $FilesMaster1->save() ) {
                $a4_file_id = $FilesMaster1->id;
            }
        }

        if( $request->hasFile('temafile') ) {
            $temafile = $request->file('temafile');
            $real_path = $temafile->getRealPath();
            $file_orgname = $temafile->getClientOriginalName();
            $file_size = $temafile->getClientSize();
            $file_ext = strtolower($temafile->getClientOriginalExtension());
            $file_newname = "file"."_".md5(microtime(TRUE).rand(123, 999)).".".$file_ext;
            $destinationPath = public_path('/uploads/files/media_files');
            $temafile->move($destinationPath, $file_newname);
            
            $FilesMaster2 = new FilesMaster;
            $FilesMaster2->file = $file_newname;
            $FilesMaster2->size = $file_size;
            $FilesMaster2->extension = $file_ext;
            $FilesMaster2->created_by = Auth::user()->id;
            $FilesMaster2->file_type = '1';
            if( $FilesMaster2->save() ) {
                $tema_file_id = $FilesMaster2->id;
            }
        }

        if( $request->hasFile('img_thumb') ) {
            $img_thumb = $request->file('img_thumb');
            $real_path = $img_thumb->getRealPath();
            $file_orgname = $img_thumb->getClientOriginalName();
            $file_size = $img_thumb->getClientSize();
            $file_ext = strtolower($img_thumb->getClientOriginalExtension());
            $file_newname = "file_img_thumb"."_".md5(microtime(TRUE).rand(123, 999)).".".$file_ext;
            $destinationPath = public_path('/uploads/files/media_images');
            $img_thumb->move($destinationPath, $file_newname);
            $img_thumb_name = $file_newname;     
        }

        if( $request->hasFile('main_file') ) {
            $main_file = $request->file('main_file');
            $real_path = $main_file->getRealPath();
            $file_orgname = $main_file->getClientOriginalName();
            $file_size = $main_file->getClientSize();
            $file_ext = strtolower($main_file->getClientOriginalExtension());
            $file_newname = "file"."_".md5(microtime(TRUE).rand(123, 999)).".".$file_ext;
            $destinationPath = public_path('/uploads/files/media_files');
            $main_file->move($destinationPath, $file_newname);
        }
        
        $FilesMaster = new FilesMaster;

        $FilesMaster->file = $file_newname;
        $FilesMaster->size = $file_size;
        $FilesMaster->extension = $file_ext;

        $FilesMaster->name = trim($request->input('name'));
        $FilesMaster->title = trim($request->input('title'));
        $FilesMaster->caption = trim($request->input('caption'));
        $FilesMaster->details = trim($request->input('details'));
        $FilesMaster->language_id = trim($request->input('language_id'));

        $FilesMaster->status = trim($request->input('status'));
        $FilesMaster->created_by = Auth::user()->id;

        $FilesMaster->a4_file_id = $a4_file_id;
        $FilesMaster->tema_file_id = $tema_file_id;
        $FilesMaster->img_thumb_name = $img_thumb_name;

        if( $FilesMaster->save() ) {
            $file_id = $FilesMaster->id;
            $FileCategoriesMap = new FileCategoriesMap;
            $FileCategoriesMap->file_category_id = trim( $request->input('file_category_id') );
            $FileCategoriesMap->file_subcategory_id = trim( $request->input('file_subcategory_id') );
            $FileCategoriesMap->file_id = $file_id;
            $FileCategoriesMap->save();

            return back()->with('msg', 'Files Uploaded Successfully.')
            ->with('msg_class', 'alert alert-success');
        }

        return back()->with('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger');
    }

    public function delete_file($file_id) {
        $ck = FilesMaster::find($file_id);
        if( isset($ck) && !empty($ck) ) {
            $file = $ck->file;
            if( $ck->delete() ) {
                FileCategoriesMap::where('file_id', '=', $file_id)->delete();
            }

            File::delete(['public/uploads/files/media_files/'. $file]);
            return back()->with('msg', 'File Deleted Successfully.')->with('msg_class', 'alert alert-success');
        }

        return back();
    }

    public function all_flCats() {
        $DataBag = array();
        $DataBag['parentMenu'] = 'media';
        $DataBag['subMenu'] = 'file';
        $DataBag['childMenu'] = 'flCats';
        $DataBag['allFCats'] = FileCategories::where('status', '!=', '3')->orderBy('created_at', 'desc')->get();
        return view('dashboard.media.file.all_categories', $DataBag);
    }

    public function create_flCat() {
        $DataBag = array();
        $DataBag['parentMenu'] = 'media';
        $DataBag['subMenu'] = 'file';
        $DataBag['childMenu'] = 'flCats';
        $DataBag['allFCats'] = FileCategories::where('status', '!=', '3')->where('parent_category_id', '=', '0')->get();
        return view('dashboard.media.file.create_category', $DataBag);
    }

    public function save_flCat(Request $request) {
        $FileCategories = new FileCategories;
        $FileCategories->name = trim( ucfirst($request->input('name')) );
        $FileCategories->slug = trim($request->input('slug'));
        $FileCategories->description = trim( htmlentities( $request->input('description'), ENT_QUOTES) );
        $FileCategories->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
        $FileCategories->mob_page_content = trim( htmlentities($request->input('mob_page_content'), ENT_QUOTES) );

        $display_order = 0;
        $show_in_gallery = 0;

        if( trim($request->input('display_order')) != '' ) {
            $display_order = trim($request->input('display_order'));
        }
        if( $request->has('show_in_gallery') ) {
            $show_in_gallery = trim($request->input('show_in_gallery'));
        }
        
        $FileCategories->display_order = $display_order;
        $FileCategories->show_in_gallery = $show_in_gallery;
        $FileCategories->tab_section = trim($request->input('tab_section'));
        
        $FileCategories->meta_title = trim($request->input('meta_title'));
        $FileCategories->meta_desc = trim($request->input('meta_desc'));
        $FileCategories->meta_keyword = trim($request->input('meta_keyword'));
        $FileCategories->canonical_url = trim($request->input('canonical_url'));
        $FileCategories->lng_tag = trim($request->input('lng_tag'));
        $FileCategories->follow = trim($request->input('follow'));
        $FileCategories->index_tag = trim($request->input('index_tag'));

        $FileCategories->parent_category_id = trim($request->input('parent_category_id'));
        $FileCategories->status = trim($request->input('status'));
        $FileCategories->created_by = Auth::user()->id;
        if( $FileCategories->save() ) {
            $file_category_id = $FileCategories->id;

            $CmsLinks = new CmsLinks;
            $CmsLinks->table_id = $file_category_id;
            $CmsLinks->slug_url = trim($request->input('slug'));
            $CmsLinks->table_type = 'FILE_CATEGORY';
            $CmsLinks->save();

            return back()->with('msg', 'File Category Created Successfully.')
            ->with('msg_class', 'alert alert-success');
        }
    return back()->with('msg', 'Something Went Wrong')
    ->with('msg_class', 'alert alert-danger');
    }

    public function delete_flCat($id) {
        $FileCategories = FileCategories::findOrFail($id);
        $FileCategories->status = '3';
        if( $FileCategories->save() ) {
            
            delete_navigation($id, 'FILE_CATEGORY');

            CmsLinks::where('table_type', '=', 'FILE_CATEGORY')->where('table_id', '=', $id)->delete();
            
            FileCategoriesMap::where('file_category_id', '=', $id)->orWhere('file_subcategory_id', '=', $id)->delete();

            return back()->with('msg', 'File Category Deleted Successfully.')
            ->with('msg_class', 'alert alert-success');
        }

        return back()->with('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger');
    }

    public function edit_flCat($file_category_id) {
        $DataBag = array();
        $DataBag['parentMenu'] = 'media';
        $DataBag['subMenu'] = 'file';
        $DataBag['childMenu'] = 'flCats';
        $DataBag['fileCat'] = FileCategories::findOrFail($file_category_id);
        $DataBag['allFCats'] = FileCategories::where('status', '!=', '3')->where('parent_category_id', '=', '0')
        ->where('id', '!=', $file_category_id)->get();
        return view('dashboard.media.file.create_category', $DataBag);
    }

    public function update_flCat(Request $request, $file_category_id) {
        $FileCategories = FileCategories::find($file_category_id);
        $FileCategories->name = trim( ucfirst($request->input('name')) );
        $FileCategories->slug = trim($request->input('slug'));
        $FileCategories->description = trim($request->input('description'));
        $FileCategories->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
        $FileCategories->mob_page_content = trim( htmlentities($request->input('mob_page_content'), ENT_QUOTES) );

        $display_order = 0;
        $show_in_gallery = 0;

        if( trim($request->input('display_order')) != '' ) {
            $display_order = trim($request->input('display_order'));
        }
        if( $request->has('show_in_gallery') ) {
            $show_in_gallery = trim($request->input('show_in_gallery'));
        }
        
        $FileCategories->display_order = $display_order;
        $FileCategories->show_in_gallery = $show_in_gallery;
        $FileCategories->tab_section = trim($request->input('tab_section'));
        
        $FileCategories->meta_title = trim($request->input('meta_title'));
        $FileCategories->meta_desc = trim($request->input('meta_desc'));
        $FileCategories->meta_keyword = trim($request->input('meta_keyword'));
        $FileCategories->canonical_url = trim($request->input('canonical_url'));
        $FileCategories->lng_tag = trim($request->input('lng_tag'));
        $FileCategories->follow = trim($request->input('follow'));
        $FileCategories->index_tag = trim($request->input('index_tag'));

        $FileCategories->parent_category_id = trim($request->input('parent_category_id'));
        $FileCategories->status = trim($request->input('status'));
        $FileCategories->updated_by = Auth::user()->id;
        if( $FileCategories->save() ) {
            CmsLinks::where('table_type', '=', 'FILE_CATEGORY')->where('table_id', '=', $file_category_id)
            ->update( ['slug_url' => trim($request->input('slug'))] );

            return back()->with('msg', 'File Category Updated Successfully.')
            ->with('msg_class', 'alert alert-success');
        }
        return back()->with('msg', 'Something Went Wrong')
        ->with('msg_class', 'alert alert-danger');
    }

    public function edit_file($file_id) {
        $DataBag = array();
        $DataBag['parentMenu'] = 'media';
        $DataBag['subMenu'] = 'file';
        $DataBag['childMenu'] = 'addFl';
        $DataBag['fileInfo'] = $fileInfo = FilesMaster::findOrFail($file_id);
        
        $DataBag['allFlCats'] = FileCategories::where('status', '!=', '3')
        ->where('parent_category_id', '=', '0')->orderBy('name', 'asc')->get();
        
        if( !empty($fileInfo) && isset($fileInfo->getCatSubcat) ) {
            
            $DataBag['seleSubCats'] = FileCategories::where('status', '!=', '3')
            ->where('parent_category_id', '=', $fileInfo->getCatSubcat->file_category_id)
            ->where('parent_category_id', '!=', '0')->orderBy('name', 'asc')->get();
        }

        $DataBag['languages'] = Languages::where('status', '=', '1')->get();
        
        return view('dashboard.media.file.add_edit', $DataBag);
    }



    public function update_file(Request $request, $file_id) {

        $FilesMaster = FilesMaster::find($file_id);

        $FilesMaster->name = trim($request->input('name'));
        $FilesMaster->title = trim($request->input('title'));
        $FilesMaster->caption = trim($request->input('caption'));
        $FilesMaster->details = trim($request->input('details'));
        $FilesMaster->status = trim($request->input('status'));
        $FilesMaster->language_id = trim($request->input('language_id'));
        $FilesMaster->updated_by = Auth::user()->id;

        /* if main_file update */
        if( $request->hasFile('main_file') ) {
            $main_file = $request->file('main_file');
            $real_path = $main_file->getRealPath();
            $file_orgname = $main_file->getClientOriginalName();
            $file_size = $main_file->getClientSize();
            $file_ext = strtolower($main_file->getClientOriginalExtension());
            $file_newname = "file"."_".md5(microtime(TRUE).rand(123, 999)).".".$file_ext;
            $destinationPath = public_path('/uploads/files/media_files');
            $main_file->move($destinationPath, $file_newname);

            $FilesMaster->file = $file_newname; /* Set New Value */
            $FilesMaster->size = $file_size; /* Set New Value */
            $FilesMaster->extension = $file_ext; /* Set New Value */
        }

        /* if a4_file update */
        if( $request->hasFile('a4file') ) {
            $a4file = $request->file('a4file');
            $real_path = $a4file->getRealPath();
            $file_orgname = $a4file->getClientOriginalName();
            $file_size = $a4file->getClientSize();
            $file_ext = strtolower($a4file->getClientOriginalExtension());
            $file_newname = "file"."_".md5(microtime(TRUE).rand(123, 999)).".".$file_ext;
            $destinationPath = public_path('/uploads/files/media_files');
            $a4file->move($destinationPath, $file_newname);
            
            $FilesMaster1 = new FilesMaster;
            $FilesMaster1->file = $file_newname;
            $FilesMaster1->size = $file_size;
            $FilesMaster1->extension = $file_ext;
            $FilesMaster1->created_by = Auth::user()->id;
            $FilesMaster1->file_type = '1';
            if( $FilesMaster1->save() ) {
                $a4_file_id = $FilesMaster1->id;
                $FilesMaster->a4_file_id = $a4_file_id; /* Insert & Set New Value */
            }
        }

        /* if letter_file update */
        if( $request->hasFile('temafile') ) {
            $temafile = $request->file('temafile');
            $real_path = $temafile->getRealPath();
            $file_orgname = $temafile->getClientOriginalName();
            $file_size = $temafile->getClientSize();
            $file_ext = strtolower($temafile->getClientOriginalExtension());
            $file_newname = "file"."_".md5(microtime(TRUE).rand(123, 999)).".".$file_ext;
            $destinationPath = public_path('/uploads/files/media_files');
            $temafile->move($destinationPath, $file_newname);
            
            $FilesMaster2 = new FilesMaster;
            $FilesMaster2->file = $file_newname;
            $FilesMaster2->size = $file_size;
            $FilesMaster2->extension = $file_ext;
            $FilesMaster2->created_by = Auth::user()->id;
            $FilesMaster2->file_type = '1';
            if( $FilesMaster2->save() ) {
                $tema_file_id = $FilesMaster2->id;
                $FilesMaster->tema_file_id = $tema_file_id; /* Insert & Set New Value */
            }
        }

        /* if thumb_file update */
        if( $request->hasFile('img_thumb') ) {
            $img_thumb = $request->file('img_thumb');
            $real_path = $img_thumb->getRealPath();
            $file_orgname = $img_thumb->getClientOriginalName();
            $file_size = $img_thumb->getClientSize();
            $file_ext = strtolower($img_thumb->getClientOriginalExtension());
            $file_newname = "file_img_thumb"."_".md5(microtime(TRUE).rand(123, 999)).".".$file_ext;
            $destinationPath = public_path('/uploads/files/media_images');
            $img_thumb->move($destinationPath, $file_newname);
            $img_thumb_name = $file_newname;
            $FilesMaster->img_thumb_name = $img_thumb_name; /* Set New Value */
        }
        
        if( $FilesMaster->save() ) {

            $ck = FileCategoriesMap::where('file_id' ,'=', $file_id)->first();
            if( !empty($ck) ) {
                $updateArr = array();
                $updateArr['file_category_id'] = trim( $request->input('file_category_id') );
                $updateArr['file_subcategory_id'] = trim( $request->input('file_subcategory_id') );
                FileCategoriesMap::where('file_id', '=', $file_id)->update( $updateArr );
            } else {
                $FileCategoriesMap = new FileCategoriesMap;
                $FileCategoriesMap->file_category_id = trim( $request->input('file_category_id') );
                $FileCategoriesMap->file_subcategory_id = trim( $request->input('file_subcategory_id') );
                $FileCategoriesMap->file_id = $file_id;
                $FileCategoriesMap->save();
            }

            return back()->with('msg', 'File Information Updated Successfully.')
            ->with('msg_class', 'alert alert-success');
        }
        return back()->with('msg', 'Something Went Wrong.')
        ->with('msg_class', 'alert alert-success');
    }

    public function fileMultiDelete(Request $request) {

        if( $request->has('fileIds') && !empty( $request->input('fileIds') ) ) {
            $FileArray = array();
            foreach( $request->input('fileIds') as $id ) {
                $ck = FilesMaster::find($id);
                $file = "public/uploads/files/media_files/".$ck->file;
                array_push( $FileArray, $file );
                $res = $ck->delete();
                if( isset($res) && $res == 1 ) {
                    FileCategoriesMap::where('file_id', '=', $id)->delete();
                }
            }

            File::delete($FileArray);
            return back()->with('msg', 'Files Deleted Successfully.')->with('msg_class', 'alert alert-success');
        }

        return back();
    }

    public function fileDataDelete(Request $request) {

        if( isset($_GET['id']) && isset($_GET['field']) ) {
            
           $id = $_GET['id'];
           $field = $_GET['field'];

           $fileRow = FilesMaster::find($id);

           if(!empty($fileRow)) {

               if( $field == 'file' ) {
                FilesMaster::where('id', '=', $id)->update(['file' => '']);
               }

               if( $field == 'img_thumb_name' ) {
                FilesMaster::where('id', '=', $id)->update(['img_thumb_name' => 'pdf_icon.png']);
               }

               if( $field == 'a4_file_id' ) {
                FilesMaster::find( $fileRow->a4_file_id )->delete();
                FilesMaster::where('id', '=', $id)->update(['a4_file_id' => '0']);
               }

               if( $field == 'tema_file_id' ) {
                FilesMaster::find( $fileRow->tema_file_id )->delete();
                FilesMaster::where('id', '=', $id)->update(['tema_file_id' => '0']);
               }
            }
        }

        return back();
    }


    /******************** Extra Content **********************/

    public function extraContentImg() {

        $DataBag = array();
        $DataBag['parentMenu'] = 'media';
        $DataBag['subMenu'] = 'image';
        $DataBag['childMenu'] = 'imgExCont';
        $DataBag['extraCont'] = MediaExtraContent::where('type', '=', 'IMAGE')->first();
        return view('dashboard.media.image.extra_content', $DataBag);
    }

    public function extraContentImgSave(Request $request) {

        $MediaExtraContent = MediaExtraContent::where('type', '=', 'IMAGE')->first();

        if( !empty($MediaExtraContent) ) {

            $MediaExtraContent->page_content = htmlentities(trim($request->input('page_content')), ENT_QUOTES);
            $MediaExtraContent->meta_title = trim($request->input('meta_title'));
            $MediaExtraContent->meta_desc = trim($request->input('meta_desc'));
            $MediaExtraContent->meta_keyword = trim($request->input('meta_keyword'));
            $MediaExtraContent->canonical_url = trim($request->input('canonical_url'));
            $MediaExtraContent->lng_tag = trim($request->input('lng_tag'));
            $MediaExtraContent->follow = trim($request->input('follow'));
            $MediaExtraContent->index_tag = trim($request->input('index_tag'));
            $MediaExtraContent->json_markup = trim( htmlentities($request->input('json_markup'), ENT_QUOTES) );

            $MediaExtraContent->title = trim($request->input('title'));
            $MediaExtraContent->image_title = trim($request->input('image_title'));
            $MediaExtraContent->image_alt = trim($request->input('image_alt'));
            $MediaExtraContent->image_caption = trim($request->input('image_caption'));

            if( $request->hasFile('page_banner') ) {
            
                $img = $request->file('page_banner');
                $real_path = $img->getRealPath();
                $file_orgname = $img->getClientOriginalName();
                $file_size = $img->getClientSize();
                $file_ext = strtolower($img->getClientOriginalExtension());
                $file_newname = "banner"."_".md5(microtime(TRUE).rand(123, 999)).".".$file_ext;
                
                $destinationPath = public_path('/uploads/files/media_images');
                $thumb_path = $destinationPath."/thumb";
                
                $imgObj = Image::make($real_path);
                $imgObj->resize(100, 100, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($thumb_path.'/'.$file_newname);

                $img->move($destinationPath, $file_newname);

                $Images = new Images;
                $Images->image = $file_newname;
                $Images->size = $file_size;
                $Images->extension = $file_ext;

                $Images->name = "Banner Image";
                $Images->alt_title = trim($request->input('image_alt'));
                $Images->caption = trim($request->input('image_caption'));
                $Images->title = trim($request->input('image_title'));

                $Images->updated_by = Auth::user()->id;

                if($Images->save()) {

                    $MediaExtraContent->image_id = $Images->id;  
                }
            }


            if( $MediaExtraContent->save() ) {
                return back()->with('msg', 'Content Saved Successfully.')->with('msg_class', 'alert alert-success');
            }
        } else {

            $MediaExtraContent = new MediaExtraContent;
            $MediaExtraContent->page_content = htmlentities(trim($request->input('page_content')), ENT_QUOTES);
            $MediaExtraContent->meta_title = trim($request->input('meta_title'));
            $MediaExtraContent->meta_desc = trim($request->input('meta_desc'));
            $MediaExtraContent->meta_keyword = trim($request->input('meta_keyword'));
            $MediaExtraContent->canonical_url = trim($request->input('canonical_url'));
            $MediaExtraContent->lng_tag = trim($request->input('lng_tag'));
            $MediaExtraContent->follow = trim($request->input('follow'));
            $MediaExtraContent->index_tag = trim($request->input('index_tag'));
            $MediaExtraContent->json_markup = trim( htmlentities($request->input('json_markup'), ENT_QUOTES) );
            $MediaExtraContent->type = 'IMAGE';

            $MediaExtraContent->title = trim($request->input('title'));
            $MediaExtraContent->image_title = trim($request->input('image_title'));
            $MediaExtraContent->image_alt = trim($request->input('image_alt'));
            $MediaExtraContent->image_caption = trim($request->input('image_caption'));

            if( $request->hasFile('page_banner') ) {
            
                $img = $request->file('page_banner');
                $real_path = $img->getRealPath();
                $file_orgname = $img->getClientOriginalName();
                $file_size = $img->getClientSize();
                $file_ext = strtolower($img->getClientOriginalExtension());
                $file_newname = "banner"."_".md5(microtime(TRUE).rand(123, 999)).".".$file_ext;
                $destinationPath = public_path('/uploads/files/media_images');
                $thumb_path = $destinationPath."/thumb";
                
                $imgObj = Image::make($real_path);
                $imgObj->resize(100, 100, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($thumb_path.'/'.$file_newname);

                $img->move($destinationPath, $file_newname);

                $Images = new Images;
                $Images->image = $file_newname;
                $Images->size = $file_size;
                $Images->extension = $file_ext;

                $Images->name = "Banner Image";
                $Images->alt_title = trim($request->input('image_alt'));
                $Images->caption = trim($request->input('image_caption'));
                $Images->title = trim($request->input('image_title'));

                $Images->created_by = Auth::user()->id;

                if($Images->save()) {

                    $MediaExtraContent->image_id = $Images->id;  
                }
            }

            if( $MediaExtraContent->save() ) {
                return back()->with('msg', 'Content Saved Successfully.')->with('msg_class', 'alert alert-success');
            }
        }

        return back();
    }




    public function extraContentVid() {

        $DataBag = array();
        $DataBag['parentMenu'] = 'media';
        $DataBag['subMenu'] = 'video';
        $DataBag['childMenu'] = 'vidExCont';
        $DataBag['extraCont'] = MediaExtraContent::where('type', '=', 'VIDEO')->first();
        return view('dashboard.media.video.extra_content', $DataBag);
    }

    public function extraContentVidSave(Request $request) {

        $MediaExtraContent = MediaExtraContent::where('type', '=', 'VIDEO')->first();

        if( !empty($MediaExtraContent) ) {

            $MediaExtraContent->page_content = htmlentities(trim($request->input('page_content')), ENT_QUOTES);
            $MediaExtraContent->meta_title = trim($request->input('meta_title'));
            $MediaExtraContent->meta_desc = trim($request->input('meta_desc'));
            $MediaExtraContent->meta_keyword = trim($request->input('meta_keyword'));
            $MediaExtraContent->canonical_url = trim($request->input('canonical_url'));
            $MediaExtraContent->lng_tag = trim($request->input('lng_tag'));
            $MediaExtraContent->follow = trim($request->input('follow'));
            $MediaExtraContent->index_tag = trim($request->input('index_tag'));
            $MediaExtraContent->json_markup = trim( htmlentities($request->input('json_markup'), ENT_QUOTES) );

            $MediaExtraContent->title = trim($request->input('title'));
            $MediaExtraContent->image_title = trim($request->input('image_title'));
            $MediaExtraContent->image_alt = trim($request->input('image_alt'));
            $MediaExtraContent->image_caption = trim($request->input('image_caption'));

            if( $request->hasFile('page_banner') ) {
            
                $img = $request->file('page_banner');
                $real_path = $img->getRealPath();
                $file_orgname = $img->getClientOriginalName();
                $file_size = $img->getClientSize();
                $file_ext = strtolower($img->getClientOriginalExtension());
                $file_newname = "banner"."_".md5(microtime(TRUE).rand(123, 999)).".".$file_ext;
                $destinationPath = public_path('/uploads/files/media_images');
                $thumb_path = $destinationPath."/thumb";
                
                $imgObj = Image::make($real_path);
                $imgObj->resize(100, 100, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($thumb_path.'/'.$file_newname);

                $img->move($destinationPath, $file_newname);

                $Images = new Images;
                $Images->image = $file_newname;
                $Images->size = $file_size;
                $Images->extension = $file_ext;

                $Images->name = "Banner Image";
                $Images->alt_title = trim($request->input('image_alt'));
                $Images->caption = trim($request->input('image_caption'));
                $Images->title = trim($request->input('image_title'));

                $Images->updated_by = Auth::user()->id;

                if($Images->save()) {

                    $MediaExtraContent->image_id = $Images->id;  
                }
            }

            if( $MediaExtraContent->save() ) {
                return back()->with('msg', 'Content Saved Successfully.')->with('msg_class', 'alert alert-success');
            }
        } else {

            $MediaExtraContent = new MediaExtraContent;
            $MediaExtraContent->page_content = htmlentities(trim($request->input('page_content')), ENT_QUOTES);
            $MediaExtraContent->meta_title = trim($request->input('meta_title'));
            $MediaExtraContent->meta_desc = trim($request->input('meta_desc'));
            $MediaExtraContent->meta_keyword = trim($request->input('meta_keyword'));
            $MediaExtraContent->canonical_url = trim($request->input('canonical_url'));
            $MediaExtraContent->lng_tag = trim($request->input('lng_tag'));
            $MediaExtraContent->follow = trim($request->input('follow'));
            $MediaExtraContent->index_tag = trim($request->input('index_tag'));
            $MediaExtraContent->json_markup = trim( htmlentities($request->input('json_markup'), ENT_QUOTES) );
            $MediaExtraContent->type = 'VIDEO';

            $MediaExtraContent->title = trim($request->input('title'));
            $MediaExtraContent->image_title = trim($request->input('image_title'));
            $MediaExtraContent->image_alt = trim($request->input('image_alt'));
            $MediaExtraContent->image_caption = trim($request->input('image_caption'));

            if( $request->hasFile('page_banner') ) {
            
                $img = $request->file('page_banner');
                $real_path = $img->getRealPath();
                $file_orgname = $img->getClientOriginalName();
                $file_size = $img->getClientSize();
                $file_ext = strtolower($img->getClientOriginalExtension());
                $file_newname = "banner"."_".md5(microtime(TRUE).rand(123, 999)).".".$file_ext;
                $destinationPath = public_path('/uploads/files/media_images');
                $thumb_path = $destinationPath."/thumb";
                
                $imgObj = Image::make($real_path);
                $imgObj->resize(100, 100, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($thumb_path.'/'.$file_newname);

                $img->move($destinationPath, $file_newname);

                $Images = new Images;
                $Images->image = $file_newname;
                $Images->size = $file_size;
                $Images->extension = $file_ext;

                $Images->name = "Banner Image";
                $Images->alt_title = trim($request->input('image_alt'));
                $Images->caption = trim($request->input('image_caption'));
                $Images->title = trim($request->input('image_title'));

                $Images->created_by = Auth::user()->id;

                if($Images->save()) {

                    $MediaExtraContent->image_id = $Images->id;  
                }
            }


            if( $MediaExtraContent->save() ) {
                return back()->with('msg', 'Content Saved Successfully.')->with('msg_class', 'alert alert-success');
            }
        }

        return back();
    }





    public function extraContentFil() {

        $DataBag = array();
        $DataBag['parentMenu'] = 'media';
        $DataBag['subMenu'] = 'file';
        $DataBag['childMenu'] = 'filExCont';
        $DataBag['extraCont'] = MediaExtraContent::where('type', '=', 'FILE')->first();
        return view('dashboard.media.file.extra_content', $DataBag);
    }

    public function extraContentFilSave(Request $request) {

        $MediaExtraContent = MediaExtraContent::where('type', '=', 'FILE')->first();

        if( !empty($MediaExtraContent) ) {

            $MediaExtraContent->page_content = htmlentities(trim($request->input('page_content')), ENT_QUOTES);
            $MediaExtraContent->meta_title = trim($request->input('meta_title'));
            $MediaExtraContent->meta_desc = trim($request->input('meta_desc'));
            $MediaExtraContent->meta_keyword = trim($request->input('meta_keyword'));
            $MediaExtraContent->canonical_url = trim($request->input('canonical_url'));
            $MediaExtraContent->lng_tag = trim($request->input('lng_tag'));
            $MediaExtraContent->follow = trim($request->input('follow'));
            $MediaExtraContent->index_tag = trim($request->input('index_tag'));
            $MediaExtraContent->json_markup = trim( htmlentities($request->input('json_markup'), ENT_QUOTES) );

            $MediaExtraContent->title = trim($request->input('title'));
            $MediaExtraContent->image_title = trim($request->input('image_title'));
            $MediaExtraContent->image_alt = trim($request->input('image_alt'));
            $MediaExtraContent->image_caption = trim($request->input('image_caption'));

            if( $request->hasFile('page_banner') ) {
            
                $img = $request->file('page_banner');
                $real_path = $img->getRealPath();
                $file_orgname = $img->getClientOriginalName();
                $file_size = $img->getClientSize();
                $file_ext = strtolower($img->getClientOriginalExtension());
                $file_newname = "banner"."_".md5(microtime(TRUE).rand(123, 999)).".".$file_ext;
                $destinationPath = public_path('/uploads/files/media_images');
                $thumb_path = $destinationPath."/thumb";
                
                $imgObj = Image::make($real_path);
                $imgObj->resize(100, 100, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($thumb_path.'/'.$file_newname);

                $img->move($destinationPath, $file_newname);

                $Images = new Images;
                $Images->image = $file_newname;
                $Images->size = $file_size;
                $Images->extension = $file_ext;

                $Images->name = "Banner Image";
                $Images->alt_title = trim($request->input('image_alt'));
                $Images->caption = trim($request->input('image_caption'));
                $Images->title = trim($request->input('image_title'));

                $Images->updated_by = Auth::user()->id;

                if($Images->save()) {

                    $MediaExtraContent->image_id = $Images->id;  
                }
            }


            if( $MediaExtraContent->save() ) {
                return back()->with('msg', 'Content Saved Successfully.')->with('msg_class', 'alert alert-success');
            }
        } else {

            $MediaExtraContent = new MediaExtraContent;
            $MediaExtraContent->page_content = htmlentities(trim($request->input('page_content')), ENT_QUOTES);
            $MediaExtraContent->meta_title = trim($request->input('meta_title'));
            $MediaExtraContent->meta_desc = trim($request->input('meta_desc'));
            $MediaExtraContent->meta_keyword = trim($request->input('meta_keyword'));
            $MediaExtraContent->canonical_url = trim($request->input('canonical_url'));
            $MediaExtraContent->lng_tag = trim($request->input('lng_tag'));
            $MediaExtraContent->follow = trim($request->input('follow'));
            $MediaExtraContent->index_tag = trim($request->input('index_tag'));
            $MediaExtraContent->json_markup = trim( htmlentities($request->input('json_markup'), ENT_QUOTES) );
            $MediaExtraContent->type = 'FILE';

            $MediaExtraContent->title = trim($request->input('title'));
            $MediaExtraContent->image_title = trim($request->input('image_title'));
            $MediaExtraContent->image_alt = trim($request->input('image_alt'));
            $MediaExtraContent->image_caption = trim($request->input('image_caption'));

            if( $request->hasFile('page_banner') ) {
            
                $img = $request->file('page_banner');
                $real_path = $img->getRealPath();
                $file_orgname = $img->getClientOriginalName();
                $file_size = $img->getClientSize();
                $file_ext = strtolower($img->getClientOriginalExtension());
                $file_newname = "banner"."_".md5(microtime(TRUE).rand(123, 999)).".".$file_ext;
                $destinationPath = public_path('/uploads/files/media_images');
                $thumb_path = $destinationPath."/thumb";
                
                $imgObj = Image::make($real_path);
                $imgObj->resize(100, 100, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($thumb_path.'/'.$file_newname);
                
                $img->move($destinationPath, $file_newname);

                $Images = new Images;
                $Images->image = $file_newname;
                $Images->size = $file_size;
                $Images->extension = $file_ext;

                $Images->name = "Banner Image";
                $Images->alt_title = trim($request->input('image_alt'));
                $Images->caption = trim($request->input('image_caption'));
                $Images->title = trim($request->input('image_title'));

                $Images->created_by = Auth::user()->id;

                if($Images->save()) {

                    $MediaExtraContent->image_id = $Images->id;  
                }
            }

            
            if( $MediaExtraContent->save() ) {
                return back()->with('msg', 'Content Saved Successfully.')->with('msg_class', 'alert alert-success');
            }
        }

        return back();
    }

}
