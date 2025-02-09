<?php

namespace App\Http\Controllers;

use App\Exports\WebinarExport;
use App\Exports\WebinarUserExport;

use Illuminate\Http\Request;
use App\Models\Media\FilesMaster;
use App\Models\Media\Images;
use App\Models\CmsLinks;

use App\Models\Webinar;
use App\Models\WebinarCategory;
use App\Models\WebinarTopic;
use App\Models\WebinarIndustry;

use App\Models\WebinarUser;
use App\Models\WebinarContent;


use Auth;
use Image;
use DB;
use Excel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;

class WebinarController extends Controller
{

	public function downloadReferral(Request $request)
	{


		$actBtnValue = trim($request->input('action_btn'));



		if ($actBtnValue == 'download') {


			$type = 'xls';

			$url = $request->input('url');
			$idsArr = $request->input('ids');
			$start_date = $request->input('start_date');
			$end_date = $request->input('end_date');

			// if(isset($url) && $url!=''){
			// 	$Data = Referral::where('referral','like', '%'.$url.'%')->orderBy('created_at', 'desc')->get();
			// }
			// else if(isset($idsArr) && count($idsArr)>0){
			// 	$Data = Referral::whereIn('id', $idsArr)->orderBy('created_at', 'desc')->get();
			// }
			// else{
			// 	$Data = Referral::orderBy('created_at', 'desc')->get();
			// }


			if (isset($url)  && $url != ''  && isset($start_date)  && $start_date != '' &&  isset($end_date)  && $end_date != '') {
				$Data = Referral::where('referral', 'like', '%' . $url . '%')->whereBetween('created_at', [$start_date, $end_date])->orderBy('id', 'desc')->get();
			} else if (isset($start_date)  && $start_date != '' &&  isset($end_date)  && $end_date != '') {
				$Data = Referral::whereBetween('created_at', [$start_date, $end_date])->orderBy('id', 'desc')->get();
			} else if (isset($url)  && $url != '') {
				$Data = Referral::where('referral', 'like', '%' . $url . '%')->orderBy('id', 'desc')->get();
			} else if (isset($idsArr) && count($idsArr) > 0) {
				$Data = Referral::whereIn('id', $idsArr)->orderBy('created_at', 'desc')->get();
			} else {
				$Data = Referral::orderBy('id', 'desc')->get();
			}




			foreach ($Data as $key => $row) {

				$r = explode('/', $row->referral);


				if (isset($r[2])) {


					$pattern = '/https/i';
					$r[2] = preg_replace($pattern, '', $r[2]);

					$pattern = '/http/i';
					$r[2] = preg_replace($pattern, '', $r[2]);

					$pattern = '/www./i';
					$r[2] = preg_replace($pattern, '', $r[2]);



					$hits = DB::table('webinar')
						->selectRaw('name,url,webinar_caegory')
						->where('url', 'like', '%' . $r[2] . '%')
						->first();
				}


				if (isset($hits->name)) {

					$webinar_caegory = DB::table('webinar_caegory')
						->selectRaw('name')
						->where('id', '=', $hits->webinar_caegory)
						->first();

					$Data[$key]['webinar_caegory'] = $webinar_caegory->name;
					$Data[$key]['webinar'] = $hits->name;
				} else {
					$Data[$key]['webinar_caegory'] = 'N/A';
					$Data[$key]['webinar'] = 'N/A';
				}
			}



			$filename = 'Multotec_Referral_' . date('m-d-Y');
			$excelArr = array();
			foreach ($Data as $v) {
				$arr = array();
				$arr['Referral URL'] = $v->referral;
				$arr['Webinar'] = $v->webinar_caegory;

				$arr['Webinar'] = $v->webinar;

				$arr['IP Address'] = $v->ip;
				array_push($excelArr, $arr);
			}

			return Excel::create($filename, function ($excel) use ($excelArr) {

				$excel->setTitle('Multotec');
				$excel->setCreator('Multotec');
				$excel->setCompany('Multotec');
				$excel->setDescription('Multotec Referral');

				$excel->sheet('All Referral', function ($sheet) use ($excelArr) {
					$sheet->fromArray($excelArr);
				});
			})->download($type);
		} else {

			$start_date = $request->input('start_date');
			$end_date = $request->input('end_date');
			$url = $request->input('url');
			$name = $request->input('name');


			$DataBag = array();
			$DataBag['parentMenu'] = 'Webinar';
			$DataBag['childMenu'] = 'allWb';

			$DataBag['url'] = $url;
			$DataBag['start_date'] = $start_date;
			$DataBag['end_date'] = $end_date;


			if (isset($url)  && $url != ''  && isset($start_date)  && $start_date != '' &&  isset($end_date)  && $end_date != '') {
				$DataBag['allReferral'] = Referral::where('referral', 'like', '%' . $url . '%')->whereBetween('created_at', [$start_date, $end_date])->orderBy('id', 'desc')->get();
			} else if (isset($url)  && $url != ''  && isset($start_date)  && $start_date != '' &&  isset($end_date)  && $end_date != '') {
				$DataBag['allReferral'] = Referral::where('referral', 'like', '%' . $url . '%')->whereBetween('created_at', [$start_date, $end_date])->orderBy('id', 'desc')->get();
			} else if (isset($start_date)  && $start_date != '' &&  isset($end_date)  && $end_date != '') {
				$DataBag['allReferral'] = Referral::whereBetween('created_at', [$start_date, $end_date])->orderBy('id', 'desc')->get();
			} else if (isset($url)  && $url != '') {
				$DataBag['allReferral'] = Referral::where('referral', 'like', '%' . $url . '%')->orderBy('id', 'desc')->get();
			} else {
				$DataBag['allReferral'] = Referral::orderBy('id', 'desc')->get();
			}

			foreach ($DataBag['allReferral'] as $key => $row) {


				if (isset($r[2])) {


					$r = explode('/', $row->referral);


					$pattern = '/https/i';
					$r[2] = preg_replace($pattern, '', $r[2]);

					$pattern = '/http/i';
					$r[2] = preg_replace($pattern, '', $r[2]);

					$pattern = '/www./i';
					$r[2] = preg_replace($pattern, '', $r[2]);



					$hits = DB::table('webinar')
						->selectRaw('name,url,webinar_caegory')
						->where('url', 'like', '%' . $r[2] . '%')
						->first();
				}


				if (isset($hits->name)) {

					$webinar_caegory = DB::table('webinar_caegory')
						->selectRaw('name')
						->where('id', '=', $hits->webinar_caegory)
						->first();

					$DataBag['allReferral'][$key]['webinar_caegory'] = $webinar_caegory->name;
					$DataBag['allReferral'][$key]['webinar'] = $hits->name;
				} else {
					$DataBag['allReferral'][$key]['webinar_caegory'] = 'N/A';
					$DataBag['allReferral'][$key]['webinar'] = 'N/A';
				}
			}

			return view('dashboard.list_referral', $DataBag);
		}
	}



	public function getReferral($url)
	{
		$DataBag = array();
		$DataBag['parentMenu'] = 'Webinar';
		$DataBag['childMenu'] = 'allWb';
		$DataBag['allReferral'] = Referral::where('referral', 'like', '%' . $url . '%')->orderBy('id', 'desc')->get();

		$DataBag['url'] = $url;

		foreach ($DataBag['allReferral'] as $key => $row) {

			$r = explode('/', $row->referral);


			$pattern = '/https/i';
			$r[2] = preg_replace($pattern, '', $r[2]);

			$pattern = '/http/i';
			$r[2] = preg_replace($pattern, '', $r[2]);

			$pattern = '/www./i';
			$r[2] = preg_replace($pattern, '', $r[2]);


			$hits = DB::table('webinar')
				->selectRaw('name,url,webinar_caegory')
				->where('url', 'like', '%' . $r[2] . '%')
				->first();


			if (isset($hits->name)) {

				$webinar_caegory = DB::table('webinar_caegory')
					->selectRaw('name')
					->where('id', '=', $hits->webinar_caegory)
					->first();

				$DataBag['allReferral'][$key]['webinar_caegory'] = $webinar_caegory->name;
				$DataBag['allReferral'][$key]['webinar'] = $hits->name;
			} else {
				$DataBag['allReferral'][$key]['webinar_caegory'] = 'N/A';
				$DataBag['allReferral'][$key]['webinar'] = 'N/A';
			}
		}

		return view('dashboard.list_referral', $DataBag);
	}

	public function allWebinars()
	{
		$DataBag = array();
		$DataBag['parentMenu'] = 'Webinar';
		$DataBag['childMenu'] = 'allWb';
		// $DataBag['allProdCats'] = Webinar::with(['WebinarCategory','WebinarReferral'])
		// ->where('status', '!=', '3')
		// ->skip(0)->take(3)
		// ->orderBy('id', 'asc')->get();

		$DataBag['allProdCats'] = DB::select('SELECT t1.*, 
		(SELECT COUNT(*) FROM referral t2 WHERE t2.referral = t1.url) AS hits
 FROM webinar t1 where t1.status!=3 order by t1.id desc');


		// foreach($DataBag['allProdCats'] as $key=>$row){

		// 	$hits=DB::table('referral') 
		//     ->selectRaw('count(id) as hits')  
		//     ->where('referral','like', '%'.$row->url.'%')
		//     ->first();

		// 	$DataBag['allProdCats'][$key]['hits']= $hits->hits;

		// }


		

			// dd($results);
			// dd($DataBag['allProdCats'][2]->WebinarReferral);
		return view('dashboard.webinar.index', $DataBag);
	}

	public function ajaxWebinars(Request $request)
	{
		
		$from_date = $request->input('from_date');
		$to_date = !empty($request->to_date)?$request->to_date:Carbon::now()->format('m-d-Y');
		$convert_from_date = Carbon::createFromFormat('m-d-Y', $from_date)->format('Y-m-d');
		$convert_to_date = Carbon::createFromFormat('m-d-Y', $to_date)->format('Y-m-d');
		

		Session::put('from_date', $convert_from_date);
		Session::put('to_date', $convert_to_date);

		$DataBag['allProdCats'] = Webinar::with(['WebinarCategory'])
			->whereBetween('created_at',[$convert_from_date,$convert_to_date])	
			->where('status', '!=', '3')->orderBy('id', 'desc')
			->get();
		foreach($DataBag['allProdCats'] as $key=>$row){

			$hits=DB::table('referral') 
			->selectRaw('count(id) as hits')  
			->where('referral','like', '%'.$row->url.'%')
			->first();

			$DataBag['allProdCats'][$key]['hits']= $hits->hits;

		}

		$returnHTML = view('dashboard.webinar.webinarFilter',$DataBag)->render();
		return response()->json(array('success' => true, 'html'=>$returnHTML));	
	}

	public function ajaxRefreshWebinars(Request $request)
	{
		$DataBag['allProdCats'] = Webinar::with(['WebinarCategory'])
			->where('status', '!=', '3')->orderBy('id', 'desc')
			->get();
		// dd($DataBag);	
		foreach($DataBag['allProdCats'] as $key=>$row){

			$hits=DB::table('referral') 
			->selectRaw('count(id) as hits')  
			->where('referral','like', '%'.$row->url.'%')
			->first();

			$DataBag['allProdCats'][$key]['hits']= $hits->hits;

		}

		$returnHTML = view('dashboard.webinar.webinarFilter',$DataBag)->render();
		return response()->json(array('success' => true, 'html'=>$returnHTML));	
	}

	public function allWbContent()
	{
		$DataBag = array();
		$DataBag['parentMenu'] = 'Webinar';
		$DataBag['childMenu'] = 'allWbContent';
		$DataBag['prodCat'] = WebinarContent::where('id', '=', '1')->first();

		return view('dashboard.webinar.webinarContent', $DataBag);
	}


	public function updateWbContent(Request $request)
	{


		$Webinar = WebinarContent::find(1);
		$Webinar->heading = trim(ucfirst($request->input('heading')));

		$Webinar->description = trim(htmlentities($request->input('description'), ENT_QUOTES));


		$resx = $Webinar->save();

		if (isset($resx) && $resx == 1) {


			return back()->with('msg', 'Webinar Content Updated Successfully.')
				->with('msg_class', 'alert alert-success');
		}

		return back()->with('msg', 'Something Went Wrong')
			->with('msg_class', 'alert alert-danger');
	}


	public function viewWbUser($id)
	{
		$DataBag = array();
		$DataBag['parentMenu'] = 'Webinar';
		$DataBag['childMenu'] = 'allWb';
		$DataBag['webinar_id'] = $id;
		$DataBag['allProdCats'] = WebinarUser::where('webinar_id', '=', $id)->where('status', '!=', 3)->orderBy('id', 'desc')->get();

		return view('dashboard.webinar.view', $DataBag);
	}

	public function ajaxViewWbUser(Request $request,$id)
	{
		$DataBag = array();
		$DataBag['parentMenu'] = 'Webinar';
		$DataBag['childMenu'] = 'allWb';
		$DataBag['webinar_id'] = $id;
		
		$from_date = $request->input('from_date');
		$to_date = !empty($request->to_date)?$request->to_date:Carbon::now()->format('m-d-Y');

		$convert_from_date = Carbon::createFromFormat('m-d-Y', $from_date)->format('Y-m-d');
		$convert_to_date = Carbon::createFromFormat('m-d-Y', $to_date)->format('Y-m-d');
		
		Session::put('from_date', $convert_from_date);
		Session::put('to_date', $convert_to_date);

		$DataBag['allProdCats'] = WebinarUser::where('webinar_id', '=', $id)
		->whereBetween('created_at',[$convert_from_date,$convert_to_date])		
		->where('status', '!=', 3)->orderBy('id', 'desc')->get();
		// dd($DataBag);
		$returnHTML = view('dashboard.webinar.webinarviewFilter',$DataBag)->render();
		return response()->json(array('success' => true, 'html'=>$returnHTML));	

		
	}

	public function ajaxRefreshViewWbUser(Request $request,$id)
	{
		$DataBag = array();
		$DataBag['parentMenu'] = 'Webinar';
		$DataBag['childMenu'] = 'allWb';
		$DataBag['webinar_id'] = $id;
		$DataBag['allProdCats'] = WebinarUser::where('webinar_id', '=', $id)->where('status', '!=', 3)->orderBy('id', 'desc')->get();

		$returnHTML = view('dashboard.webinar.webinarviewFilter',$DataBag)->render();
		return response()->json(array('success' => true, 'html'=>$returnHTML));	
	}

	public function WbWebinarblkAction(Request $request)
	{

		$actBtnValue = trim($request->input('action_btn'));

		$webinar_id = trim($request->input('webinar_id'));

		$from_date = $request->has('from_date')&& !empty($request->from_date)?$request->from_date:'01-01-2020';
		$to_date = $request->to_date;

		$from_date = $request->has('from_date') && !empty($from_date) ?Carbon::createFromFormat('m-d-Y', $from_date)->format('Y-m-d'):'';
		$to_date = $request->has('to_date') && !empty($to_date)?Carbon::createFromFormat('m-d-Y', $to_date)->format('Y-m-d'):Carbon::now()->format('Y-m-d');

		if ($actBtnValue == 'download') {

			$type = 'xls';

			$idsArr = $request->input('ids');
			if (isset($idsArr) && count($idsArr) > 0) {
				$Data = WebinarUser::whereIn('id', $idsArr)->orderBy('created_at', 'desc')->get();
			} else {
				$Data = WebinarUser::where('webinar_id', $webinar_id)->orderBy('id', 'desc')->get();
			}
			
			if($from_date){
				if (isset($idsArr) && count($idsArr) > 0) {
					$Data = WebinarUser::whereIn('id', $idsArr)
					->whereBetween('created_at',[$from_date,$to_date])
					->orderBy('created_at', 'desc')->get();
				} else {
					$Data = WebinarUser::where('webinar_id', $webinar_id)
					->whereBetween('created_at',[$from_date,$to_date])
					->orderBy('id', 'desc')->get();
				}

			}
			// dd($Data);
			$filename = 'Multotec_Webinar_User_' . date('m-d-Y');
			$excelArr = array();
			foreach ($Data as $v) {
				$arr = array();
				$arr['Webinar'] = $v->webinar->name;
				$arr['Name'] = $v->name;
				$arr['Email'] = $v->email_id;
				$arr['Contact'] = $v->contact_no;
				$arr['Company'] = $v->company;
				$arr['Attended date'] = date('m-d-Y', strtotime($v->created_at));

				array_push($excelArr, $arr);
			}
			// dd($excelArr);
			return Excel::download(new WebinarUserExport($excelArr), $filename . '.' . $type);

			return Excel::create($filename, function ($excel) use ($excelArr) {

				$excel->setTitle('Multotec');
				$excel->setCreator('Multotec');
				$excel->setCompany('Multotec');
				$excel->setDescription('Multotec Webinar Users');

				$excel->sheet('All Webinar Users', function ($sheet) use ($excelArr) {
					$sheet->fromArray($excelArr);
				});
			})->download($type);
		}
	}

	public function addWebinar()
	{
		$DataBag = array();
		$DataBag['parentMenu'] = 'Webinar';
		$DataBag['childMenu'] = 'allWb';

		$DataBag['allSource'] = WebinarCategory::where('status', '=', '1')->select('name', 'id')
			->orderBy('name', 'asc')->get();

		$DataBag['alltopics'] = WebinarTopic::where('status', '=', '1')->select('name', 'id')
			->orderBy('name', 'asc')->get();

		$DataBag['allindustry'] = WebinarIndustry::where('status', '=', '1')->select('name', 'id')
			->orderBy('name', 'asc')->get();

		$DataBag['insert_id'] = md5(microtime(TRUE));
		return view('dashboard.webinar.add', $DataBag);
	}

	/**** SAVE PRODUCT CATEGORY ***/

	public function saveWebinar(Request $request)
	{


		$slug_url = trim($request->input('slug'));
		$table_id = trim($request->input('id'));
		$ck = Webinar::where('slug', '=', $slug_url)->where('id', '!=', $table_id)->count();
		if ($ck > 0) {
			return back()->with('msg', 'This URL Already Exist, Try Another.')
				->with('msg_class', 'alert alert-danger');
		} else {


			$insert_id = trim($request->input('insert_id')); // Page Builder -- Insert Time

			$Webinar = new Webinar;
			$Webinar->name = trim(ucfirst($request->input('name')));


			$webinar_category = $request->input('webinar_category');
			$webinarcatval = '';

			foreach ($webinar_category as $row) {
				$webinarcatval .= $row . ',';
			}

			$Webinar->webinar_category = $webinarcatval;

			$webinar_topic = $request->input('webinar_topic');
			$webinartopicval = '';

			foreach ($webinar_topic as $row) {
				$webinartopicval .= $row . ',';
			}

			$Webinar->webinar_topic = $webinartopicval;

			$webinar_industry = $request->input('webinar_industry');
			$webinarindustryval = '';

			foreach ($webinar_industry as $row) {
				$webinarindustryval .= $row . ',';
			}

			$Webinar->webinar_industry = $webinarindustryval;


			$Webinar->url = trim(ucfirst($request->input('url')));
			$Webinar->description = trim(ucfirst($request->input('description')));
			$Webinar->short_description = trim(ucfirst($request->input('short_description')));
			$Webinar->speaker = trim(ucfirst($request->input('speaker')));
			$Webinar->sub_heading = trim(ucfirst($request->input('sub_heading')));

			$Webinar->duration = trim($request->input('duration'));

			$Webinar->webinar_type = trim($request->input('webinar_type'));

			$webinar_start_date = trim(ucfirst($request->input('webinar_start_date')));
			$webinar_end_date = trim(ucfirst($request->input('webinar_end_date')));

			$Webinar->webinar_start_date = date('Y-m-d', strtotime($webinar_start_date));
			$Webinar->webinar_end_date = date('Y-m-d', strtotime($webinar_end_date));

			$Webinar->slug = trim($request->input('slug'));

			if ($request->hasFile('image')) {

				$image = $request->file('image');
				$real_path = $image->getRealPath();
				$file_orgname = $image->getClientOriginalName();
				$file_size = $image->getSize();
				$file_ext = strtolower($image->getClientOriginalExtension());
				$file_newname = "user" . "_" . time() . "." . $file_ext;

				$destinationPath = public_path('/uploads/user_images');
				$original_path = $destinationPath . "/original";
				$thumb_path = $destinationPath . "/thumb";

				$img = Image::make($real_path);
				$img->resize(150, 150, function ($constraint) {
					$constraint->aspectRatio();
				})->save($thumb_path . '/' . $file_newname);

				$image->move($original_path, $file_newname);
				$Webinar->image = $file_newname;
			}

			if ($request->hasFile('video_image')) {

				$image = $request->file('video_image');
				$real_path = $image->getRealPath();
				$file_orgname = $image->getClientOriginalName();
				$file_size = $image->getSize();
				$file_ext = strtolower($image->getClientOriginalExtension());
				$file_newname = "user" . "_" . time() . "." . $file_ext;

				$destinationPath = public_path('/uploads/user_images');
				$original_path = $destinationPath . "/original";
				$thumb_path = $destinationPath . "/thumb";

				$img = Image::make($real_path);
				$img->resize(150, 150, function ($constraint) {
					$constraint->aspectRatio();
				})->save($thumb_path . '/' . $file_newname);

				$image->move($original_path, $file_newname);
				$Webinar->video_image = $file_newname;
			}
			$resx = $Webinar->save();
			if (isset($resx) && $resx == 1) {

				return back()->with('msg', 'Webinar Created Successfully.')
					->with('msg_class', 'alert alert-success');
			}

			return back()->with('msg', 'Something Went Wrong')
				->with('msg_class', 'alert alert-danger');
		}
	}


	public function deleteWebinar($category_id)
	{
		$ck = Webinar::find($category_id);
		if (isset($ck) && !empty($ck)) {
			$ck->status = '3';
			$res = $ck->save();
			if (isset($res) && $res == 1) {

				return back()->with('msg', 'Webinar Deleted Successfully.')
					->with('msg_class', 'alert alert-success');
			}
		}

		return back('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger');
	}

	public function delWbUser($category_id)
	{
		$ck = WebinarUser::find($category_id);
		if (isset($ck) && !empty($ck)) {
			$ck->status = '3';
			$res = $ck->save();
			if (isset($res) && $res == 1) {

				return back()->with('msg', 'Webinar User Deleted Successfully.')
					->with('msg_class', 'alert alert-success');
			}
		}

		return back('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger');
	}

	public function editWebinar($category_id, Request $request)
	{


		$DataBag = array();

		$DataBag['parentMenu'] = 'Webinar';
		$DataBag['childMenu'] = 'allWb';

		$DataBag['allSource'] = WebinarCategory::where('status', '=', '1')->select('name', 'id')
			->orderBy('name', 'asc')->get();
		$DataBag['alltopics'] = WebinarTopic::where('status', '=', '1')->select('name', 'id')
			->orderBy('name', 'asc')->get();

		$DataBag['allindustry'] = WebinarIndustry::where('status', '=', '1')->select('name', 'id')
			->orderBy('name', 'asc')->get();

		$DataBag['content_id'] = $category_id;

		$DataBag['prodCat'] = Webinar::where('status', '=', '1')->where('id', $category_id)->orderBy('name', 'asc')->first();

		return view('dashboard.webinar.add', $DataBag);
	}


	/**** UPDATE PRODUCT CATEGORY ***/

	public function updateWebinar(Request $request, $category_id)
	{

		$slug_url = trim($request->input('slug'));
		$table_id = $category_id;
		$ck = Webinar::where('slug', '=', $slug_url)->where('id', '!=', $table_id)->count();
		if ($ck > 0) {
			return back()->with('msg', 'This URL Already Exist, Try Another.')
				->with('msg_class', 'alert alert-danger');
		} else {



			$Webinar = Webinar::find($category_id);
			$Webinar->name = trim(ucfirst($request->input('name')));


			$webinar_category = $request->input('webinar_category');
			$webinarcatval = '';

			foreach ($webinar_category as $row) {
				$webinarcatval .= $row . ',';
			}

			$Webinar->webinar_category = $webinarcatval;

			$webinar_topic = $request->input('webinar_topic');
			$webinartopicval = '';

			foreach ($webinar_topic as $row) {
				$webinartopicval .= $row . ',';
			}

			$Webinar->webinar_topic = $webinartopicval;



			$webinar_industry = $request->input('webinar_industry');
			$webinarindustryval = '';

			foreach ($webinar_industry as $row) {
				$webinarindustryval .= $row . ',';
			}

			$Webinar->webinar_industry = $webinarindustryval;


			$Webinar->url = trim(ucfirst($request->input('url')));
			$Webinar->description = trim(ucfirst($request->input('description')));
			$Webinar->short_description = trim(ucfirst($request->input('short_description')));
			$Webinar->speaker = trim(ucfirst($request->input('speaker')));
			$Webinar->sub_heading = trim(ucfirst($request->input('sub_heading')));
			$Webinar->slug = trim($request->input('slug'));

			$Webinar->duration = trim($request->input('duration'));

			$Webinar->webinar_type = trim($request->input('webinar_type'));

			$webinar_start_date = trim(ucfirst($request->input('webinar_start_date')));
			$webinar_end_date = trim(ucfirst($request->input('webinar_end_date')));

			$Webinar->webinar_start_date = date('Y-m-d', strtotime($webinar_start_date));
			$Webinar->webinar_end_date = date('Y-m-d', strtotime($webinar_end_date));

			if ($request->hasFile('video_image')) {

				$image = $request->file('video_image');
				$real_path = $image->getRealPath();
				$file_orgname = $image->getClientOriginalName();
				$file_size = $image->getSize();
				$file_ext = strtolower($image->getClientOriginalExtension());
				$file_newname = "user" . "_" . time() . "." . $file_ext;

				$destinationPath = public_path('/uploads/user_images');
				$original_path = $destinationPath . "/original";
				$thumb_path = $destinationPath . "/thumb";

				$img = Image::make($real_path);
				$img->resize(150, 150, function ($constraint) {
					$constraint->aspectRatio();
				})->save($thumb_path . '/' . $file_newname);

				$image->move($original_path, $file_newname);
				$Webinar->video_image = $file_newname;
			}
			if ($request->hasFile('image')) {

				$image = $request->file('image');
				$real_path = $image->getRealPath();
				$file_orgname = $image->getClientOriginalName();
				$file_size = $image->getSize();
				$file_ext = strtolower($image->getClientOriginalExtension());
				$file_newname = "user" . "_" . time() . "." . $file_ext;

				$destinationPath = public_path('/uploads/user_images');
				$original_path = $destinationPath . "/original";
				$thumb_path = $destinationPath . "/thumb";

				$img = Image::make($real_path);
				$img->resize(150, 150, function ($constraint) {
					$constraint->aspectRatio();
				})->save($thumb_path . '/' . $file_newname);

				$image->move($original_path, $file_newname);
				$Webinar->image = $file_newname;
			}


			$resx = $Webinar->save();

			if (isset($resx) && $resx == 1) {

				return redirect()->route('allWb')->with('msg', 'Webinar Updated Successfully.')
					->with('msg_class', 'alert alert-success');
			}

			return back()->with('msg', 'Something Went Wrong')
				->with('msg_class', 'alert alert-danger');
		}
	}


	public function downloadWebinarUsers(Request $request)
	{


		//$Data = WebinarUser::where('status', '!=', '3')->orderBy('created_at', 'desc')->get();

		// dd($request->all());

		$from_date = $request->has('from_date')&& !empty($request->from_date)?$request->from_date:'01-01-2020';
		$to_date = $request->to_date;

		$from_date = $request->has('from_date') && !empty($from_date) ?Carbon::createFromFormat('m-d-Y', $from_date)->format('Y-m-d'):'';
		$to_date = $request->has('to_date') && !empty($to_date)?Carbon::createFromFormat('m-d-Y', $to_date)->format('Y-m-d'):Carbon::now()->format('Y-m-d');
		
		if($from_date){
			$Data = DB::table('webinar_users as wbu')
			->join('webinar as wb', 'wb.id', '=', 'wbu.webinar_id')->select('wbu.*', 'wb.name as wbname')
			->whereBetween('wbu.created_at',[$from_date,$to_date])	
			->where('wbu.status', '!=', '3')
			->orderBy('wb.name', 'asc')
			->orderBy('wbu.created_at', 'desc')
			
			// ->groupBy('wb.name')
			->get();
		}else{

			$Data = DB::table('webinar_users as wbu')->where('wbu.status', '!=', '3')->orderBy('wbu.created_at', 'desc')
				->join('webinar as wb', 'wb.id', '=', 'wbu.webinar_id')->select('wbu.*', 'wb.name as wbname')->get();
		}
		// dd($Data);
		$filename = 'Multotec_Webinar_Users_' . date('m-d-Y');
		$type = 'csv';
		$excelArr = array();
		foreach ($Data as $v) {
			//dd($v);
			$arr = array();
			$arr['Webinar'] = $v->wbname;
			$arr['Name'] = $v->name;
			$arr['Email'] = $v->email_id;
			$arr['Contact No'] = $v->contact_no;
			$arr['Address'] = $v->address;
			$arr['Company'] = $v->company;
			$arr['Country'] = $v->country;
			$arr['Date'] = date('m-d-Y', strtotime($v->created_at));
			array_push($excelArr, $arr);
		}
		// dd($excelArr);  
		return Excel::download(new WebinarExport($excelArr), $filename . '.' . $type);

		return Excel::create($filename, function ($excel) use ($excelArr) {
			//echo "hi ";
			$excel->setTitle('Multotec');
			//echo "hi1 "; 
			$excel->setCreator('Multotec');
			//echo "hi2 "; 
			$excel->setCompany('Multotec');
			//echo "hi3 "; 
			$excel->setDescription('Multotec Webinar Requests');
			//echo "hi4 "; 
			$excel->sheet('All Webinar Requests', function ($sheet) use ($excelArr) {

				$sheet->fromArray($excelArr);
			});
			//echo "tt";//die;
		})->download('csv');
	}
}
