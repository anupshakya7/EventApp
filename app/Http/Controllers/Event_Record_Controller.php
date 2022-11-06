<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event_Record;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class Event_Record_Controller extends Controller
{

    public function index(){
        $events = Event_Record::orderBy('start_date','asc')->get();
        return view('event',compact('events'));
    }

    public function fetchEvent(){
        $events = Event_Record::all();
        if($events){
            return response()->json([
                'status'=>200,
                'events'=>$events
            ]);
        }else{
            return response()->json([
                'status'=>404,
                'message'=>'Events Not Found'
            ]);
        }
    }

    public function store(Request $req){

        $validator = Validator::make($req->all(),[
            'event_title'=>'required|max:191',
            'event_description'=>'required',
            'event_start_date'=>'required',
            'event_end_date'=>'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'status'=>400,
                'errors'=>$validator->messages()
            ]);
        }else{
            $newEvents = new Event_Record;
            $newEvents->event_title = $req->input('event_title');
            $newEvents->event_description = $req->input('event_description');
            $newEvents->start_date = $req->input('event_start_date');
            $newEvents->end_date = $req->input('event_end_date');
            $newEvents->save();
            return response()->json([
                'status'=>200,
                'message'=>'Event Added Successfully'
            ]);
        }
    }

    public function edit($id){
        $events_edit_item = Event_Record::find($id);
        return response()->json([
            'status'=>200,
            'events'=>$events_edit_item
        ]);
    }

    public function update(Request $req){
        $events_update = Event_Record::find($req->event_id);
        $events_update->event_title = $req->input('event_title');
        $events_update->event_description = $req->input('event_description');
        $events_update->start_date = $req->input('event_start_date');
        $events_update->end_date = $req->input('event_end_date');
        $events_update->update();

        return redirect()->back()->with('status',"Event Updated Successfully");
    }
    // public function storeEvent(Request $req){
    //     $validator = Validator::make($req->all(),[
    //         'event_title'=>'required|unique|max:255',
    //         'event_description'=>'required',
    //         'event_start_date'=>'required',
    //         'event_end_date'=>'required'
    //     ]);
    //     if($validator->fails()){
    //         return response()->json([
    //             'status'=>400,
    //             'errors'=>$validator->messages()
    //         ]);
    //     }else{
    //         $new_event = new Event_Record;
    //         $new_event->event_title = $req->input('event_title');
    //         $new_event->event_description = $req->input('event_description');
    //         $new_event->start_date = $req->input('event_start_date');
    //         $new_event->end_date = $req->input('event_end_date');
    //         $new_event->save();
    //         return response()->json([
    //             'status'=>200,
    //             'message'=>'New Event Added Successfully'
    //         ]);
    //     }
    // }

    public function destroy($id){
        $events_delete = Event_Record::find($id);
        if($events_delete){
            $events_delete->delete();
            return response()->json([
                'status'=>200,
                'message'=>'Event Deleted Successfully'
            ]);
            return redirect()->back()->with('status','Event Deleted Successfully');
        } else{
            return response()->json([
                'status'=>404,
                'message'=>'Event Not Found'
            ]);
        }
    }

    public function finishEvents(){
        $today = Carbon::now();
        $finishevent = Event_Record::whereDate('start_date', '<', $today->format('Y-m-d'))
           ->whereDate('end_date', '<', $today->format('Y-m-d'))
           ->get();

        return response()->json([
            "status"=>200,
            "finish_events"=>$finishevent
        ]);
    }

    public function upcomingEvents(){
        $today = Carbon::now();
        $upcomingevent = Event_Record::whereDate('start_date', '>', $today->format('Y-m-d'))
           ->whereDate('end_date', '>', $today->format('Y-m-d'))
           ->get();

        return response()->json([
            "status"=>200,
            "upcoming_events"=>$upcomingevent
        ]);
    }


}
