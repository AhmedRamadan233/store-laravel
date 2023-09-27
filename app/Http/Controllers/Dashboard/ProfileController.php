<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Intl\Countries;
use Symfony\Component\Intl\Languages;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $countries = Countries::getNames();
        $locales = Languages::getNames();
 

        $responseData = [
            'user' => $user,
            'countries' => $countries,
            'locales' => $locales,
        ];
    
        return response()->json(['data' => $responseData]);
    }
    

    public function update(Request  $request){
        try{
            $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'birthday' => 'required|date',
                'gender' => 'nullable|in:male,female',
                'country' => 'required|string|size:2',
            ]);
            $user = Auth::user();
    
    
            $user->profile->fill( $request->all() )->save();
            
            
            return response()->json(['data' => $user]);
        }catch(\Exception $e ){
            return response()->json(['error' => $e->getMessage()], 500);
        }
       // $profile = $user->profile;
            // if($profile->user_id){
            //     $user->profile->update($request->all());
            // }else{
            //     // $request->merge([
            //     //     'user_id' => $user->id,
            //     // ]);
            //     // Profile::create($request->all());
            //     $user->profile()->create($request->all());
            // }

    }
}
