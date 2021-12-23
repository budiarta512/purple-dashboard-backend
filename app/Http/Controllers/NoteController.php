<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception\InvalidOrderException;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        try {
            return response()->json([
                'status' => 'success',
                'message' => 'berhasil mendapatkan data note',
                'data' => Note::where('user_id', auth()->user()->id)->get()
            ], 200);
        } catch(Exception $error) {
            return response()->json([
                'status' => 'error',
                'message' => $error,
            ], 500);
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        try{
            $rule = [
                'title' => 'required|max:30',
                'body' => 'required',
                'category' => 'required',
                'user_id' => 'required'
            ];
            $validator = Validator::make($request->all(), $rule);
            if($validator->fails()) {
                return response()->json([
                    'status' => 'fail',
                    'message' => $validator->errors()
                ], 400);
            }
            Note::create([
                'user_id' => $request->user_id,
                'title' => $request->title,
                'body' => $request->body,
                'category' => $request->category,
                'user_id' => $request->user_id
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'berhasil menginput data note',
            ], 200);
        } catch(Exception $error) {
            return response()->json([
                'status' => 'success',
                'message' => $error
            ], 500);
        } catch(InvalidOrderException $error) {
            return response()->json([
                'status' => 'success',
                'message' => $error
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        try{
            return response()->json([
                'status' => 'success',
                'message' => 'berhasil mendapatkan data note',
                'data' => Note::where('id', $id)->first()
            ], 200);
        } catch(Exception $error) {
            return response()->json([
                'status' => 'success',
                'message' => $error
            ], 500);
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        try{
            $rule = [
                'title' => 'required|max:30',
                'body' => 'required',
                'category' => 'required'
            ];
            $validator = Validator::make($request->all(), $rule);
            if($validator->fails()) {
                return response()->json([
                    'status' => 'fail',
                    'message' => $validator->errors()
                ], 400);
            }
            $note = Note::where('id', $id)->first();
            $note->user_id = $request->user_id;
            $note->title = $request->title;
            $note->body = $request->body;
            $note->category = $request->category;
            $note->save();

            return response()->json([
                'status' => 'success',
                'message' => 'berhasil mengupdate note'
            ], 200);
        } catch(Exception $error) {
            return response()->json([
                'status' => 'fail',
                'message' => $error
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try{
            Note::where('id', $id)->first()->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'berhasil menghapus note'
            ],200);
        } catch(Exception $error) {
            return response()->json([
                'status' => 'fail',
                'message' => $error
            ], 500);
        }
    }
}
