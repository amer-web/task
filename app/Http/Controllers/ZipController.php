<?php

namespace App\Http\Controllers;

use App\Http\Requests\ZipRequest;
use App\Models\FileUpload;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ZipController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $files = FileUpload::where('user_id', auth()->user()->id);

            return Datatables::of($files)
                ->make(true);
        }
        return view('zip.show');
    }

    public function zipUpload()
    {
        return view('zip.upload_zip');
    }

    public function uploadAndExtract(ZipRequest $request)
    {
        $file = $request->file('zip');
        \DB::beginTransaction();
        $fileName = $file->getClientOriginalName();
        $zip = new \ZipArchive();
        $status = $zip->open($file->getRealPath());
        if ($status !== true) {
            throw new \Exception($status);
        } else {
            $storageDestinationPath = public_path('extract');
            if (!\File::exists($storageDestinationPath)) {
                \File::makeDirectory($storageDestinationPath, 0755, true);
            }
            $zip->extractTo($storageDestinationPath);
            $zip->close();
            $data = [
                'file_name' => $fileName,
                'file_path' => 'uploadFiles/' . $fileName,
                'size' => $file->getSize(),
                'type' => $file->getMimeType(),
                'user_id' => auth()->user()->id
            ];
            FileUpload::create($data);
            $file->move(public_path('uploadFiles'), $fileName);
            \DB::commit();
            return back()
                ->with('success', 'You have successfully upload and extracted file in path ' . public_path('extract'));
        }


    }

    public function delete(Request $request, $id)
    {
        $file = FileUpload::findOrFail($id);
        if (\File::exists(public_path($file->file_path))) {
            \File::delete(public_path($file->file_path));
        }
        $file->delete();
        return response()->json('success');
    }

    public function json()
    {
        $auth = \Auth::user()->select('id', 'name')->get()->first()->toArray();
        $file = FileUpload::select('id', 'file_name', 'type')->where('user_id', $auth['id'])->get();
        $data = array_merge($auth, ['files' => $file]);
        return response()->json($data);
    }
}
