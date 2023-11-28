<?php

namespace App\Http\Controllers\Back;

use Exception;
use App\Models\User;
use App\Models\Comment;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdvisorController extends Controller
{
    public function getDocument()
    {
        $paginateLimit = request('per_page') ? request('per_page') : 5;
        $user_id = Auth::user()->id;
        $document = Document::with(['user', 'department', 'year'])->where('advisor_id', $user_id)->latest()->paginate($paginateLimit);
        return response()->json($document);
    }

    public function addComment(Request $request)
    {
        $advisor_id = Auth::user()->id;
        $role_id = Auth::user()->role_id;
        $request->validate([
            'comment' => 'required|string',
        ]);
        if ($request->attached_file != null) {
            $request->validate([
                'attached_file' => 'file|mimes:doc,docx,pdf'
            ]);
        }
        $comment = new Comment();

        try {
            DB::beginTransaction();
            if ($request->hasFile('attached_file')) {
                $file = $request->file('attached_file');
                $file_name_ext = time() . '.' . $file->getClientOriginalExtension();
                $upload_path = '/document/attached_file/';
                $name = $upload_path . $file_name_ext;
                $file->move(public_path($upload_path), $file_name_ext);
                $comment->attached_file = $name;
            }
            $comment->user_id = $advisor_id;
            $comment->document_id = $request->document_id;
            $comment->role_id = $role_id;
            $comment->comment = $request->comment;
            if ($comment->save()) {
                $document =  Document::where('id', $request->document_id)->first();
                $document->status = 1;
                $document->save();
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    public function approveDocument($id)
    {
        $document = Document::find($id);
        ($document->status == 1) ? $document->status = 2 : $document->status = 1;
        if ($document->save()) {
            return response()->json([
                'message' => 'Document status Changed!',
            ], 200);
        } else {
            return response()->json([
                'message' => 'something wrong happen please try again!',
            ], 500);
        }
    }
    public function getSingleDocumentComment($id)
    {
        $paginateLimit = request('per_page')? request('per_page') :5;
        $comment = Comment::with(['user', 'document', 'role'])->where('document_id', $id)->latest()->paginate($paginateLimit);
        return response()->json($comment);
    }

    public function showComment($id)
    {
        $comment = Comment::where('id', $id)->get();
        return response()->json($comment);
    }

    public function deleteComment($id)
    {
        $comment = Comment::find($id);
        if ($comment->delete()) {
            unlink(public_path($comment->attached_file));
            return response()->json([
                'message' => 'Comment deleted successfully',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Something wrong happen please try again!',
            ], 500);
        }
    }
    public function updateComment(Request $request, $id)
    {
        $comment = Comment::find($id);
        $advisor_id = Auth::user()->id;
        $role_id = Auth::user()->role_id;
        $request->validate([
            'comment' => 'required|string',
        ]);
        if ($request->attached_file != null) {
            $request->validate([
                'attached_file' => 'file|mimes:doc,docx,pdf'
            ]);
        }

        try {
            DB::beginTransaction();
            if ($request->hasFile('attached_file')) {
                $file = $request->file('attached_file');
                $file_name_ext = time() . '.' . $file->getClientOriginalExtension();
                $upload_path = '/document/attached_file/';
                $name = $upload_path . $file_name_ext;
                unlink(public_path($comment->attached_file));
                $file->move(public_path($upload_path), $file_name_ext);
                $comment->attached_file = $name;
            }
            $comment->user_id = $advisor_id;
            $comment->document_id = $request->document_id;
            $comment->role_id = $role_id;
            $comment->comment = $request->comment;
            if ($comment->save()) {
                $document =  Document::where('id', $request->document_id)->first();
                $document->status = 1;
                $document->save();
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }
}
