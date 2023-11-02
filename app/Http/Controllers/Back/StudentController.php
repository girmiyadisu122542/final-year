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
use Intervention\Image\Facades\Image;

class StudentController extends Controller
{
  public function getDocument()
  {
    $user_id = Auth::user()->id;
    $document = Document::with(['user', 'department', 'year'])->where('user_id', $user_id)->get();
    return response()->json($document);
  }
  public function getAdvisor()
  {
    $dept_id = Auth::user()->department_id;
    $role_id = 13;
    $advisor = User::with(['role', 'department'])->where('role_id', $role_id)->where('department_id', $dept_id)->where('status', 1)->get();
    return response()->json($advisor);
  }
  public function uploadDocument(Request $request)
  {
    $request->validate([
      'document' => 'file|mimes:pdf,docx',
      'author' => 'required',
      'cover_page' => 'file|mimes:png,jpg,jpeg',
      'advisor' => 'required',
      'abstract' => 'required',
      'title' => 'required',
      'page_number' => 'required|numeric',
      'accadamic_year' => 'required',
    ]);

    $document = new Document();

    if ($request->hasFile('document')) {
      $file = $request->file('document');
      $file_name_ext = time() . '.' . $file->getClientOriginalExtension();
      $upload_path = '/document/Upload/';
      $name = $upload_path . $file_name_ext;
      $file->move(public_path($upload_path), $file_name_ext);
      $document->path = $name;
      $document->format = $file->getClientOriginalExtension();
    }
    if ($request->hasFile('cover_page')) {
      $file = $request->file('cover_page');
      $file_name_ext = time() . '.' . $file->getClientOriginalExtension();
      $upload_path = '/document/cover_page/';
      $name = $upload_path . $file_name_ext;
      $file->move(public_path($upload_path), $file_name_ext);
      $document->cover_page = $name;
    }
    $document->user_id = Auth::user()->id;
    $document->department_id = Auth::user()->department_id;
    $document->size = $request->size;
    $document->title = $request->title;
    $document->abstract = $request->abstract;
    $document->author = $request->author;
    $document->advisor_id = $request->advisor;
    $document->year_id = $request->accadamic_year;
    $document->page_number = $request->page_number;
    $document->save();
  }
  public function showDocument($id)
  {
    $document = Document::where('id', $id)->get();
    return response()->json($document);
  }

  public function updateDocument(Request $request, $id)
  {
    $document = Document::where('id',$id)->first();
    $request->validate([
      'author' => 'required',
      'advisor' => 'required',
      'abstract' => 'required',
      'title' => 'required|unique:documents,'.$id,
      'page_number' => 'required|numeric',
      'accadamic_year' => 'required',
    ]);

    try {
      DB::beginTransaction();
      if ($request->hasFile('document')) {
        $file = $request->file('document');
        $file_name_ext = time() . '.' . $file->getClientOriginalExtension();
        $upload_path = '/document/Upload/';
        $name = $upload_path . $file_name_ext;
        unlink(public_path($document->path));
        $file->move(public_path($upload_path), $file_name_ext);
        $document->path = $name;
        $document->format = $file->getClientOriginalExtension();
      }
      if ($request->hasFile('cover_page')) {
        $file = $request->file('cover_page');
        $file_name_ext = time() . '.' . $file->getClientOriginalExtension();
        // $img = Image::make($request->cover_page)->resize(752,390);
        $upload_path = '/document/cover_page/';
        $name = $upload_path . $file_name_ext;
        unlink(public_path($document->cover_page));
        $file->move(public_path($upload_path), $file_name_ext);
        $document->cover_page = $name;
      }
      $document->user_id = Auth::user()->id;
      $document->department_id = Auth::user()->department_id;
      $document->size = $request->size;
      $document->title = $request->title;
      $document->abstract = $request->abstract;
      $document->author = $request->author;
      $document->advisor_id = $request->advisor;
      $document->year_id = $request->accadamic_year;
      $document->page_number = $request->page_number;
      $document->save();
      DB::commit();
    } catch (Exception $e) {
      DB::rollBack();
    }
  }

  public function deleteDocument($id)
  {
    $document = Document::find($id);
    if ($document->delete()) {
      unlink(public_path($document->path));
      unlink(public_path($document->cover_page));
      return response()->json([
        'message' => 'Document deleted successfully',
      ], 200);
    } else {
      return response()->json([
        'message' => 'something wrong happen please try again!',
      ], 500);
    }
  }

  public function getSingleDocumentComment($id)
  {
    $comment = Comment::with(['user', 'document', 'role'])->where('document_id', $id)->get();
    return response()->json($comment);
  }
}
