<?php

namespace App\Http\Controllers;

use App\Pengguna;
use App\position;
use Validator;
use Crypt;
use Illuminate\Http\Request;
use DB;

class PenggunaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pengguna = pengguna::all();
        $position = position::all();


        return view ('pages.home')->with('pengguna',$pengguna)->with('position',$position);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->ajax()){
            $rules = array (
                'fullname.*' => 'required|max:30',
                'username.*' => 'required|max:30',
                'password.*' => ['required','regex:/[a-z]/','regex:/[A-Z]/','regex:/[0-9]/'],
                'email.*' => 'required|email',
                'phonenumber.*' => 'required|starts_with:+62|regex:/[0-9]/',
                'position.*' => 'required'
            );
            $error = Validator::make($request->all(), $rules);
            if($error->fails()){
                return response()->json([
                    'error' => $error->errors()->all()
                ]);
            }

            $full_name = $request->fullname;
            $username = $request->username;
            $password = $request->password;
            $email = $request->email;
            $phonenumber = $request->phonenumber;
            $position = $request->position;
            for($count = 0; $count < count($full_name); $count++){
                $fPassword = Crypt::encryptString($password[$count]);

                $data = array(
                    'full_name' => $full_name[$count],
                    'username' => $username[$count],
                    'password' => $fPassword,
                    'email' => $email[$count],
                    'phonenumber' => $phonenumber[$count],
                    'position' => $position[$count]
                );
                $insert_data[] = $data;
            }
            Pengguna::insert($insert_data);
            return response()->json(['success'=>'Data Berhasil di Tambahkan']);
        }
        // $validator = Validator::make($request->all(), [
        //     'fullname[]'=>'required|max:30',
        //     'username[]'=>'required|max:30',
        //     'password[]'=>'required|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/',
        //     'email[]'=>'required|email',
        //     'phonenumber[]'=>'required|starts_with:+62',
        //     'position[]'=>'required'

        // ]);

        // if ($validator->fails()) {

        //     if($request->ajax())
        //     {
        //         return response()->json(array(
        //             'success' => false,
        //             'message' => 'There are incorect values in the form!',
        //             'errors' => $validator->getMessageBag()->toArray()
        //         ), 422);
        //     }

        //     $this->throwValidationException(

        //         $request, $validator

        //     );

        // }

        // $request->validate([
        //     'fullname[]'=>'required|max:30',
        //     'username[]'=>'required|max:30',
        //     'password[]'=>'required|regex:/[a-z]/,regex:/[A-Z]/,regex:/[0-9]/',
        //     'email[]'=>'required|email',
        //     'phonenumber[]'=>'required|starts_with:+62',
        //     'position[]'=>'required'

        //     ]);
            
            // $full_name = $request->fullname;
            // $username = $request->username;
            // $password = $request->password;
            // $email = $request->email;
            // $phonenumber = $request->phonenumber;
            // $position = $request->position;

            // for($count = 0; $count < count($full_name); $count++){
            //     $data = array (
            //         'fullname' => $full_name[$count],
            //         'username' => $username[$count],
            //         'password' => $password[$count],
            //         'email' => $email[$count],
            //         'phonenumber' => $phonenumber[$count],
            //         'position' => $position[$count]
            //     );

            //     $insert_data[] = $data;
            // }

            // return $request;
            // return re



            // Pengguna::create([
            //     'full_name'=> $request["fullname"],
            //     'username'=>$request["username"],
            //     'password'=>md5($request["password"]),
            //     'email'=>$request["email"],
            //     'phonenumber'=>$request['phonenumber'],
            //     'position'=>$request['position']
            // ]);
            

            // return redirect('/')->with('status','Data berhasil disimpan');
    }



    /**
     * Display the specified resource.
     *
     * @param  \App\Pengguna  $pengguna
     * @return \Illuminate\Http\Response
     */
    // public function show(Pengguna $pengguna)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Pengguna  $pengguna
     * @return \Illuminate\Http\Response
     */
    public function edit(Pengguna $pengguna)
    {
        $position = position::all();
        return view ('pages.edit')->with('pengguna',$pengguna)->with('position',$position);;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Pengguna  $pengguna
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pengguna $pengguna)
    {
        $request->validate([
            'fullname'=>'required|max:30',
            'username'=>'required|max:30',
            'password'=>'required|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/',
            'email'=>'required|email',
            'phonenumber'=>'required|starts_with:+62',
            'position'=>'required'

            ]);
        Pengguna::where('id',$pengguna->id)
                    ->update([
                        'full_name'=> $request["fullname"],
                        'username'=>$request["username"],
                        'password'=>md5($request["password"]),
                        'email'=>$request["email"],
                        'phonenumber'=>$request['phonenumber'],
                        'position'=>$request['position']
                    ]);
        return redirect ('/')->with('status','Data Berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Pengguna  $pengguna
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pengguna $pengguna)
    {
        Pengguna::destroy($pengguna->id);
        return redirect ('/')->with('status','Data berhasil dihapus');
    }
}
