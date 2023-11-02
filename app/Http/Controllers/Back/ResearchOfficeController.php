<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Document;
use App\Models\Proposal;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ResearchOfficeController extends Controller
{
    public function getResearchers()
    {
        $researcher = User::where('role_id', 12)->get();
        return response()->json($researcher);
    }
     public function getDocuments(){
        $document = Document::with(['user', 'year'])->where('department_id',null)->get();
        return response()->json($document);
     }
    public function getAnnouncements()
    {
        $user_id = Auth::user()->id;
        $announce = Announcement::with(['user', 'year'])->where('user_id', $user_id)->get();
        return response()->json($announce);
    }

    public function addAnnouncement(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'year_id' => 'required',
            'announcement_start_date' => 'required',
            'announcement_end_date' => 'required'
        ]);

        try {
            DB::beginTransaction();
            $announce = new Announcement();
            $announce->title = $request->title;
            $announce->content = $request->content;
            $announce->year_id = $request->year_id;
            $announce->announcement_start_date = $request->announcement_start_date;
            $announce->announcement_end_date = $request->announcement_end_date;
            $announce->user_id = Auth::user()->id;
            $announce->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    public function deleteAnnouncement($id)
    {
        $announce = Announcement::find($id);
        if ($announce->delete()) {
            return response()->json([
                'message' => 'Announcement deleted successfully',
            ], 200);
        } else {
            return response()->json([
                'message' => 'something wrong happen please try again!',
            ], 500);
        }
    }

    public function showAnnouncment($id)
    {
        $announce = Announcement::where('id', $id)->get();
        return response()->json($announce);
    }
    public function updateAnnouncment(Request $request, $id)
    {
        $announce =  Announcement::where('id', $id)->first();
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'year_id' => 'required',
            'announcement_start_date' => 'required',
            'announcement_end_date' => 'required'
        ]);

        try {
            DB::beginTransaction();
            $announce->title = $request->title;
            $announce->content = $request->content;
            $announce->year_id = $request->year_id;
            $announce->announcement_start_date = $request->announcement_start_date;
            $announce->announcement_end_date = $request->announcement_end_date;
            $announce->user_id = Auth::user()->id;
            $announce->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }
    public function changeStatus($id){
        $announce=Announcement::find($id);
    
        ($announce->status==0) ? $announce->status=1:$announce->status=0;
        if($announce->save()){
            return response()->json([
                'message'=>'Announcement status Changed!',
            ],200);
         }else{
            return response()->json([
                'message'=>'something wrong happen please try again!',
            ],500); 
         }    
            
       }
        public function approveDocument($id)
    {
        $document = Document::find($id);
        if($document->status == 0){
            $document->status =1;
        }else{

            ($document->status == 1) ? $document->status = 4 : $document->status = 1;
        }
        if ($document->save()) {
            return response()->json([
                'message' => 'Document  status Changed!',
            ], 200);
            return response()->json([
                'message' => 'Something wrong happen please try again!',
            ], 500);
        }
    }

    public function getProposals(){
        $proposal = Proposal::with(['user','announcement'])->get();
        return response()->json($proposal);
    }

    public function approveProposals($id){
        $proposal = Proposal::find($id);
        if($proposal->status == 0){
            $proposal->status =1;
        }else{
            ($proposal->status == 1) ? $proposal->status = 2 : $proposal->status = 1;
        }
        if ($proposal->save()) {
            return response()->json([
                'message' => 'proposal  status Changed!',
            ], 200);
            return response()->json([
                'message' => 'Something wrong happen please try again!',
            ], 500);
        }
    }

    public function downloadProposal($id){
        $proposal = Proposal::where('id', $id)->first();
        $file_path = $proposal->file_attached;
        return response()->download(public_path($file_path));
    }
}
