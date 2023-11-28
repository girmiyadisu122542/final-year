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
use Illuminate\Support\Facades\Hash;

class DepartmentHeadController extends Controller
{
    public function getProjectCoordinator()
    {
        $paginateLimit = request('per_page')? request('per_page') :5;
        $dept_id = Auth::user()->department_id;
        $role_id = 17;
        $projectCoordinator = User::with(['role', 'department'])->where('role_id', $role_id)->where('department_id', $dept_id)->latest()->paginate($paginateLimit);
        return response()->json($projectCoordinator);
    }
    public function getStudents()
    {
       $paginateLimit = request('per_page')? request('per_page') :5;
        $dept_id = Auth::user()->department_id;
        $role_id = 10;
        $student = User::with(['role', 'department'])->where('role_id', $role_id)->where('department_id', $dept_id)->latest()->paginate($paginateLimit);
        return response()->json($student);
    }
    public function addProjectCoordinator(Request $request)
    {
        $validatedData = $request->validate([
            'full_name' => 'required|string|min:3',
            'email' => 'required|email|unique:users,email',
        ]);


        try {
            DB::beginTransaction();
            $user = new User();
            $user->full_name = $request->full_name;
            $user->email = $request->email;
            $user->role_id = 17;
            $user->department_id = Auth::user()->department_id;
            $usercode = rand('000000', '999999');
            $user->code = $usercode;
            $user->password = Hash::make($usercode);
            $user->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }
    public function updateProjectCoordinator(Request $request, $id)
    {
        $user =  User::where('id', $id)->first();
        $validatedData = $request->validate([
            'full_name' => 'required|string|min:3',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);


        try {
            DB::beginTransaction();
            $user->full_name = $request->full_name;
            $user->email = $request->email;
            $user->role_id = 17;
            $user->department_id = Auth::user()->department_id;
            $usercode = rand('000000', '999999');
            $user->code = $usercode;
            $user->password = Hash::make($usercode);
            $user->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    public function getDocuments()
    {
        $paginateLimit = request('per_page')? request('per_page') :5;
        $user_id = Auth::user()->department_id;
        $document = Document::with(['user', 'department', 'year', 'advisor'])->where('department_id', $user_id)->where('status', '>=', 4)->latest()->paginate($paginateLimit);
        return response()->json($document);
    }
    public function addComment(Request $request)
    {
        $user_id = Auth::user()->id;
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
            $comment->user_id = $user_id;
            $comment->document_id = $request->document_id;
            $comment->role_id = $role_id;
            $comment->comment = $request->comment;
            $comment->status = 2;
            if ($comment->save()) {
                $document =  Document::where('id', $request->document_id)->first();
                $document->status = 5;
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
        ($document->status == 6) ? $document->status = 5 : $document->status = 6;
        if ($document->save()) {
            return response()->json([
                'message' => 'Document  status Changed!',
            ], 200);
            return response()->json([
                'message' => 'Something wrong happen please try again!',
            ], 500);
        }
    }
    public function getSingleDocumentComment($id)
    {
        $paginateLimit = request('per_page')? request('per_page') :5;
        $comment = Comment::with(['user', 'document', 'role'])->where('document_id', $id)->where('status', 2)->latest()->paginate($paginateLimit);
        return response()->json($comment);
    }
    public function updateComment(Request $request, $id)
    {
        $comment = Comment::find($id);
        $user_id = Auth::user()->id;
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
            $comment->user_id = $user_id;
            $comment->document_id = $request->document_id;
            $comment->role_id = $role_id;
            $comment->comment = $request->comment;
            $comment->status = 2;
            if ($comment->save()) {
                $document =  Document::where('id', $request->document_id)->first();
                $document->status = 5;
                $document->save();
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }
}
