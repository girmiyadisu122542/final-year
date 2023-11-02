<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Department;
use App\Models\Document;
use App\Models\User;
use Illuminate\Http\Request;

class AllBackendController extends Controller
{
    //
    public function downloadDocument($id)
    {
        $document = Document::where('id', $id)->first();
        $file_path = $document->path;
        return response()->download(public_path($file_path));
    }
    public function downloadComment($id)
    {
        $comment = Comment::where('id', $id)->first();
        $file_path = $comment->attached_file;
        return response()->download(public_path($file_path));
    }
    public function allUser()
    {
        $user = User::get()->count();
        return response()->json($user);
    }
    public function allActiveUser()
    {
        $user = User::where('status', 1)->get()->count();
        return response()->json($user);
    }
    public function allDeactiveUser()
    {
        $user = User::where('status', 0)->get()->count();
        return response()->json($user);
    }
    public function allDocument()
    {
        $document = Document::get()->count();
        return response()->json($document);
    }

    public function allPrograms(){
        $programs = Department::get()->count();
        return response()->json($programs); 
    }
  
}
