<?php

namespace App\Http\Controllers\Front;

use Exception;
use App\Models\Document;
use App\Models\Proposal;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class FrontPageControler extends Controller
{
    public function getDocuments(){
        $document= Document::with(['user','department','year'])->where('status' ,'>=',4)->latest()->get();
        return response()->json($document);

    }
    public function getLatestDocuments(){
        $latestdoc = Document::with(['user','department','year'])->where('status' ,'>=',4)->latest()->paginate(3);
        return response()->json($latestdoc);
    }
    public function showDocument($id){
        $document = Document::with(['user','department','year'])->where('id',$id)->get();
        return response()->json($document);
   } 

   public function getAnnouncements(){
    $announcement = Announcement::with(['user','year'])->where('status',1)->latest()->get();
    return response()->json($announcement);
   }

   public function uploadProposal(Request $request){
     $request->validate([
        'file_attached'=>'file|mimes:pdf,doc,docx'
     ]);
     
     try {
        $proposal = new Proposal();
        DB::beginTransaction();
        if ($request->hasFile('file_attached')) {
            $file = $request->file('file_attached');
            $file_name_ext = time() . '.' . $file->getClientOriginalExtension();
            $upload_path = '/document/attached_file/';
            $name = $upload_path . $file_name_ext;
            $file->move(public_path($upload_path), $file_name_ext);
            $proposal->file_attached = $name;
        }
        $proposal->user_id = Auth::user()->id;
        $proposal->announcement_id = $request->announcement_id;
        $proposal->save();
        DB::commit();
    } catch (Exception $e) {
        DB::rollBack();
    }
   }

   public function proposalStatus(){
    $proposal = Proposal::with(['user','announcement'])->where('user_id',Auth::user()->id)->get();
    return response()->json($proposal);
   }
}
