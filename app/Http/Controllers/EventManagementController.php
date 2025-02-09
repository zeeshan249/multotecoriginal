<?php

namespace App\Http\Controllers;

use DB;
use Excel;
use Image;
use App\Exports\EventExport;
use Illuminate\Http\Request;
use App\Exports\WebinarExport;
use Illuminate\Support\Carbon;
use Session;
class EventManagementController extends Controller
{

    public function index(){
        $DataBag = array();
		$DataBag['parentMenu'] = 'eventManage';
		$DataBag['childMenu'] = 'allEventCt';
		$DataBag['eventsAll'] = DB::table('event_management')
        ->join('event_management_type','event_management_type.id','event_management.event_type_id')
        ->select('event_management.id','event_management.name','event_management.slug','event_management.event_start_date','event_management.event_end_date','event_management.event_location','region_id','country_id','event_management.event_url','event_management_type.name as event_type')->whereNotNull('event_type_id')
		->orderBy('event_management.id','desc')
		->get();
		return view('dashboard.event_management.index', $DataBag);
    }
    public function addEvent()
	{
		$DataBag = array();
		$DataBag['parentMenu'] = 'eventManage';
		$DataBag['childMenu'] = 'addEventCt';

		$DataBag['eventManagementType'] = DB::table('event_management_type')->where('status', '=', '1')->select('name', 'id')
			->orderBy('name', 'asc')->get();
		$DataBag['regions'] = DB::table('event_management_continents')->where('status', '=', '1')->select('continent_name', 'id')
			->orderBy('status', 'asc')->get();

		$DataBag['insert_id'] = md5(microtime(TRUE));
		return view('dashboard.event_management.add', $DataBag);
	}
    public function saveEvent(Request $request){
		
       // dd($request->all());
        $slug_url = trim($request->input('slug'));
		$table_id = trim($request->input('id'));
		$ck = DB::table('event_management')->where('slug', '=', $slug_url)->where('id', '!=', $table_id)->count();
		if ($ck > 0) {
			return back()->with('msg', 'This URL Already Exist, Try Another.')
				->with('msg_class', 'alert alert-danger');
		} else {


			$insert_id = trim($request->input('insert_id')); // Page Builder -- Insert Time

			
			$name = trim(ucfirst($request->input('name')));


	



			$eventType = trim(ucfirst($request->input('event_type_id')));
	
			$region_id = trim(ucfirst($request->input('region_id')));
			$country_id = trim(ucfirst($request->input('country_id')));
			
			$eventlink = trim(ucfirst($request->input('event_link')));
			$eventdescription = trim(ucfirst($request->input('description')));
		
			

			$start_date = trim(ucfirst($request->input('event_start_date')));
			$end_date = trim(ucfirst($request->input('event_end_date')));
			if (empty($end_date)) {
				$event_end_date = null; // Set to NULL if no date is provided
			  }
			  else{
				$event_end_date = $end_date;

			  }
			$event_start_date = date('Y-m-d', strtotime($start_date));

		

			if ($request->hasFile('image')) {

				$image = $request->file('image');
				$real_path = $image->getRealPath();
				$file_orgname = $image->getClientOriginalName();
				$file_size = $image->getSize();
				$file_ext = strtolower($image->getClientOriginalExtension());
				$file_newname = "event" . "_" . time() . "." . $file_ext;

				$destinationPath = public_path('/uploads/event_images');
				$original_path = $destinationPath . "/original";
				$thumb_path = $destinationPath . "/thumb";

				$img = Image::make($real_path);
				$img->resize(300, 200, function ($constraint) {
					// $constraint->aspectRatio();
				})->save($thumb_path . '/' . $file_newname);

				$image->move($original_path, $file_newname);
				// $Webinar->image = $file_newname;
			}

		//dd($event_start_date);
			$eventInsert = DB::table('event_management')->insertGetId([
                'name' => $name,
                'slug' => $slug_url,
                'event_type_id' => $eventType,
                'region_id' => $region_id,
                'country_id' => $country_id,
                'event_url' => $eventlink,
                'event_start_date' => $event_start_date,
                'event_end_date' => $event_end_date,
                'description' => $eventdescription??'',
                'image' => $file_newname??'',
            ]);
			if ($eventInsert>0) {

				// return back()->with('msg', 'Event Created Successfully.')
				// 	->with('msg_class', 'alert alert-success');
				return redirect()->route('alleventManagement')->with('msg', 'Event Updated Successfully.');
				
			}
         else{
			return back()->with('msg', 'Something Went Wrong')
				->with('msg_class', 'alert alert-danger');
		 }
			}
    }

    public function editEvent($event_id){
        $DataBag = array();

        $DataBag['parentMenu'] = 'eventManage';
		$DataBag['childMenu'] = 'addEventCt';

		$DataBag['eventManagementType'] = DB::table('event_management_type')->where('status', '=', '1')->select('name', 'id')
			->orderBy('name', 'asc')->get();

		$DataBag['content_id'] = $event_id;

		$DataBag['prodCat'] =DB::table('event_management')
        ->join('event_management_type','event_management_type.id','event_management.event_type_id')
        ->select('event_management.id','description','event_management.event_type_id','event_management.image','event_management.name','event_management.slug','event_management.event_start_date','event_management.event_end_date','event_management.event_location','event_management.event_url','event_management_type.name as event_type','region_id','country_id')->whereNotNull('event_type_id')->where('event_management.id', $event_id)->first();
		$DataBag['regions'] = DB::table('event_management_continents')->where('status', '=', '1')->select('continent_name', 'id')
		->orderBy('status', 'asc')->get();
        return view('dashboard.event_management.add', $DataBag);
    }
    public function updateEvent($event_id,Request $request){
        // dd($request->all());
        $slug_url = trim($request->input('slug'));
		$table_id = $event_id;
		$ck =DB::table('event_management')->where('slug', '=', $slug_url)->where('id', '!=', $table_id)->count();
		if ($ck > 0) {
			return back()->with('msg', 'This URL Already Exist, Try Another.')
				->with('msg_class', 'alert alert-danger');
		} else {

            $name = trim(ucfirst($request->input('name')));
			$eventType = trim(ucfirst($request->input('event_type_id')));
	
			$region_id = trim(ucfirst($request->input('region_id')));
			$country_id = trim(ucfirst($request->input('country_id')));
			$eventlink = trim(ucfirst($request->input('event_link')));
		


			$start_date = trim(ucfirst($request->input('event_start_date')));
			$end_date = trim(ucfirst($request->input('event_end_date')));

			$event_start_date = date('Y-m-d', strtotime($start_date));
			
			if (empty($end_date)) {
				$event_end_date = null; // Set to NULL if no date is provided
			  }
			  else{
				$event_end_date = $end_date;

			  }
			$eventdescription = trim(ucfirst($request->input('description')));


			
            if ($request->hasFile('image')) {

				$image = $request->file('image');
				$real_path = $image->getRealPath();
				$file_orgname = $image->getClientOriginalName();
				$file_size = $image->getSize();
				$file_ext = strtolower($image->getClientOriginalExtension());
				$file_newname = "event" . "_" . time() . "." . $file_ext;

				$destinationPath = public_path('/uploads/event_images');
				$original_path = $destinationPath . "/original";
				$thumb_path = $destinationPath . "/thumb";

				$img = Image::make($real_path);
				$img->resize(250, 160, function ($constraint) {
					$constraint->aspectRatio();
				})->save($thumb_path . '/' . $file_newname);

				$image->move($original_path, $file_newname);
				// $Webinar->image = $file_newname;
			}
            else{
                $getfile=DB::table('event_management')->select('image')->where('id',$event_id)->first();
                $file_newname=$getfile->image;
            }


			$eventUpdate =  DB::table('event_management')
            ->where('event_management.id',$event_id)
            ->update([
                'name' => $name,
                'slug' => $slug_url,
                'event_type_id' => $eventType,
				'region_id' => $region_id,
                'country_id' => $country_id,
                'event_url' => $eventlink,
                'event_start_date' => $event_start_date,
                'event_end_date' => $event_end_date,
                'image' => $file_newname,
				'description' => $eventdescription??'',
            ]);;

			if (isset($eventUpdate) && $eventUpdate == 1) {

				return redirect()->route('alleventManagement')->with('msg', 'Event Updated Successfully.')
					->with('msg_class', 'alert alert-success');
			}

			return back()->with('msg', 'Something Went Wrong')
				->with('msg_class', 'alert alert-danger');
		}
    }

    public function deleteEvent($event_id)
	{
		$ck = DB::table('event_management')
        ->where('event_management.id',$event_id)->exists();
		if (isset($ck) && !empty($ck)) {
			$eventDelete =  DB::table('event_management')
            ->where('event_management.id',$event_id)->delete();
		
			if (isset($eventDelete) && $eventDelete == 1) {

				return back()->with('msg', 'Event Deleted Successfully.')
					->with('msg_class', 'alert alert-success');
			}
		}

		return back('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger');
	}

    public function ajaxEvents(){
        $from_date = $request->input('from_date');
		$to_date = !empty($request->to_date)?$request->to_date:Carbon::now()->format('m-d-Y');
		$convert_from_date = Carbon::createFromFormat('m-d-Y', $from_date)->format('Y-m-d');
		$convert_to_date = Carbon::createFromFormat('m-d-Y', $to_date)->format('Y-m-d');
		

		Session::put('from_date', $convert_from_date);
		Session::put('to_date', $convert_to_date);

		$DataBag['allEventCats'] = DB::table('event_management')
        ->join('event_management_type','event_management_type.id','event_management.event_type_id')
        ->select('event_management.id','event_management.name','event_management.slug','event_management.event_start_date','event_management.event_end_date','event_management.event_location','event_management.event_url','event_management_type.name as event_type')->whereNotNull('event_type_id')
        ->whereBetween('start_date', [$convert_from_date, $convert_to_date])
        ->orWhereBetween('end_date', [$convert_from_date, $convert_to_date])
        ->get();

   

		$returnHTML = view('dashboard.event_management.eventFilter', $DataBag)->render();
		return response()->json(array('success' => true, 'html'=>$returnHTML));	
    }

    public function ajaxRefreshEvents(){

    }

    public function downloadEventUsers(Request $request)
	{


		//$Data = WebinarUser::where('status', '!=', '3')->orderBy('created_at', 'desc')->get();

		// dd($request->all());

		$from_date = $request->has('from_date')&& !empty($request->from_date)?$request->from_date:'01-01-2020';
		$to_date = $request->to_date;

		$from_date = $request->has('from_date') && !empty($from_date) ?Carbon::createFromFormat('m-d-Y', $from_date)->format('Y-m-d'):'';
		$to_date = $request->has('to_date') && !empty($to_date)?Carbon::createFromFormat('m-d-Y', $to_date)->format('Y-m-d'):Carbon::now()->format('Y-m-d');
		
		if($from_date){
		
            $Data =     DB::table('event_management')
        ->join('event_management_type','event_management_type.id','event_management.event_type_id')
        ->select('event_management.id','event_management.name','event_management.slug','event_management.event_start_date','event_management.event_end_date','event_management.event_location','event_management.event_url','event_management_type.name as event_type')->whereNotNull('event_type_id')
        ->whereBetween('event_start_date', [$from_date, $to_date])
        ->orderBy('event_management.name', 'desc')
        ->get();
			
			// ->groupBy('wb.name')
		
		}else{

            $Data =     DB::table('event_management')
            ->join('event_management_type','event_management_type.id','event_management.event_type_id')
            ->select('event_management.id','event_management.name','event_management.slug','event_management.event_start_date','event_management.event_end_date','event_management.event_location','event_management.event_url','event_management_type.name as event_type')->whereNotNull('event_type_id')
            ->whereBetween('event_start_date', [$from_date, $to_date])
            ->orWhereBetween('event_end_date', [$from_date, $to_date])
            ->orderBy('event_management.name', 'desc')
            ->get();
		}
		// dd($Data);
		$filename = 'Multotec_Events_' . date('m-d-Y');
		$type = 'csv';
		$excelArr = array();
		foreach ($Data as $v) {
			//dd($v);
			$arr = array();
			$arr['Event Name'] = $v->name;
			$arr['Event Type'] = $v->event_type;
			$arr['Start Date'] = $v->event_start_date;
			$arr['End Date'] = $v->event_end_date;
			$arr['Location'] = $v->event_location;
			$arr['Registration Link'] = $v->event_url;
			
		
			array_push($excelArr, $arr);
		}
		// dd($excelArr);  
		return Excel::download(new EventExport($excelArr), $filename . '.' . $type);

		// return Excel::create($filename, function ($excel) use ($excelArr) {
		// 	//echo "hi ";
		// 	$excel->setTitle('Multotec');
		// 	//echo "hi1 "; 
		// 	$excel->setCreator('Multotec');
		// 	//echo "hi2 "; 
		// 	$excel->setCompany('Multotec');
		// 	//echo "hi3 "; 
		// 	$excel->setDescription('Multotec Webinar Requests');
		// 	//echo "hi4 "; 
		// 	$excel->sheet('All Multotec Requests', function ($sheet) use ($excelArr) {

		// 		$sheet->fromArray($excelArr);
		// 	});
		// 	//echo "tt";//die;
		// })->download('csv');
	}
	public function allEventManagementType(Request $request){
		$DataBag = array();
		$DataBag['parentMenu'] = 'eventManaget';
		$DataBag['childMenu'] = 'allEventCtt';
		$DataBag['allProdCats'] = DB::table('event_management_type')
  
        ->select('*')->get();
		return view('dashboard.event_management_type.index', $DataBag);
	}

	public function addEventManagementType(Request $request){
		$DataBag= array();
		$DataBag['parentMenu'] = 'eventManaget';
		$DataBag['childMenu'] = 'addEventCtt';

		// $DataBag['allSource'] = WebinarCategory::where('status', '=', '1')->select('name', 'id')
		// 	->orderBy('name', 'asc')->get();

		// $DataBag['alltopics'] = WebinarTopic::where('status', '=', '1')->select('name', 'id')
		// 	->orderBy('name', 'asc')->get();

		
      

		$DataBag['insert_id'] = md5(microtime(TRUE));
		return view('dashboard.event_management_type.add', $DataBag);
	}

	
	public function addEventType(Request $request){
		$name = trim(ucfirst($request->input('name')));
		$eventInsert = DB::table('event_management_type')->insertGetId([
			'name' => $name,
		
		]);
		if ($eventInsert>0) {
			return redirect()->route('allEventManagementType')->with('msg', 'Event Type Added  Successfully.');
			
		}
	 else{
		return back()->with('msg', 'Something Went Wrong')
			->with('msg_class', 'alert alert-danger');
	 }
	}
  
	public function editEventType($id){
		$DataBag = array();

        $DataBag['parentMenu'] = 'eventManaget';
		$DataBag['childMenu'] = 'addEventCtt';

		$DataBag['eventManagementType'] = DB::table('event_management_type')->where('status', '=', '1')->select('name', 'id')
			->orderBy('name', 'asc')->get();

		$DataBag['content_id'] = $id;

		$DataBag['prodCat'] =DB::table('event_management_type')
        
        ->select('*')->where('event_management_type.id', $id)->first();
		return view('dashboard.event_management_type.add', $DataBag);
	}
	public function updateEventType(Request $request,$id){
		$name = trim(ucfirst($request->input('name')));
		$eventUpdate =  DB::table('event_management_type')
            ->where('event_management_type.id',$id)
            ->update([
                'name' => $name,
        
            ]);;

			if (isset($eventUpdate) && $eventUpdate == 1) {

				return redirect()->route('allEventManagementType')->with('msg', 'Event Type Updated Successfully.')
					->with('msg_class', 'alert alert-success');
			}
         else
		 {
						return back()->with('msg', 'Something Went Wrong')
				->with('msg_class', 'alert alert-danger');
		 }
	}
	public function acInacEventType(){
		$val = trim( $_GET['val'] );
		$id = trim( $_GET['id'] );
		DB::table( 'event_management_type' )->where('id', '=', $id)->update( ['status' => $val] );
		return back()->with('msg', 'Status Changed Successfully.')->with('msg_class', 'alert alert-success');
	}
	public function getCountries(Request $request){
	    $region_id = $request->input('region_id');

    $countries = DB::table('event_management_countries')->where('continent_id', $region_id)->get(['id', 'country_name']);

    return response()->json($countries);
	}
}
