<?php

namespace App\Http\Controllers;

use Validator;
use Carbon\Carbon;
use App\Models\Child;
use App\Models\Parents;
use Illuminate\Http\Request;

class ChildController extends Controller
{
    public function save(Request $request){

        $request->merge([
            'gender' => $request->gender === 'true' ? true : false,
        ]);

        $validator = Validator::make($request->all(),[
            'name' => 'bail|required|string|max:64',
            'dob' => 'bail|required|date',
            'place_of_birth' => 'bail|required|string|max:64',
            'photo' => 'bail|required|file|mimes:jpg,jpeg,png,bmp,tiff',
            'gender' => 'bail|required|boolean',
        ],[
            'mimes' => 'Please upload images only',
            'gender' => 'Please check male or female',
        ]);

        if($validator->fails()){
            return $this->jsonResult(400, $validator->errors());
        }

        $child = Child::create([
            'name' => $request->name,
            'DOB' => $request->dob,
            'place_of_birth' => $request->place_of_birth,
            'gender' => ($request->gender === true ? 'male' : 'female'),
        ]);

        $photo = $request->file('photo');
        $photo->move(public_path() . '/children_pics', $child->id);

        return $this->jsonResult(200, 'Success');
    }

    public function edit(Request $request){
        $request->merge([
            'gender' => $request->gender === 'true' ? true : false,
        ]);

        $validator = Validator::make($request->all(),[
            'id' => 'bail|required|exists:children,id',
            'name' => 'bail|nullable|string|max:64',
            'dob' => 'bail|nullable|date',
            'place_of_birth' => 'bail|nullable|string|max:64',
            'photo' => 'bail|nullable|file|mimes:jpg,jpeg,png,bmp,tiff',
            'gender' => 'bail|required|boolean',
        ],[
            'mimes' => 'Please upload images only',
            'gender' => 'Please check male or female',
        ]);

        if($validator->fails()){
            return $this->jsonResult(400, $validator->errors());
        }

        $child = Child::find( $request->id);

        $child->name = $request->name === null ? $child->name : $request->name;
        $child->DOB = $request->DOB === null ? $child->DOB : $request->dob;
        $child->place_of_birth = $request->place_of_birth === null ? $child->place_of_birth : $request->place_of_birth;
        $child->gender = ($request->gender === true ? 'male' : 'female');

        $photo = $request->file('photo');

        if($photo !== null){
            $photo->move(public_path() . '/children_pics', $child->id);
        }

        $child->update();

        return $this->jsonResult(200, 'Success');
    }

    public function adopt(Request $request){
        $validator = Validator::make($request->all(),[
            'id' => 'bail|required|exists:Children,id',
        ]);

        if($validator->fails()){
            return $this->jsonResult(400, $validator->errors());
        }

        $parent = Parents::where('user_id', auth()->user()->id)->first();
        $child = Child::find($request->id);

        $reject_adoption_request = [
            0 => false,
            1 => true,
            2 => false,
            3 => true,
        ];

        if($reject_adoption_request[(int) $child->adoption_status]){
            return $this->jsonResult(400, $validator->errors()->add('id', 'Child is already adopted, or has a pending approval'));
        }

        $child->adopted_by = $parent->id;
        $child->adoption_status = 1;
        $child->adopted_on = now();
        $child->save();

        $parent->adopted = $child->id;
        $parent->save();

        return $this->jsonResult(200, 'Success');
    }

    function getAdoptionReport($filter = null){
        switch($filter){
            case 'thisday':
                return $this->jsonResult(200, Child::whereDate('adopted_on', Carbon::today())
                ->where('adoption_status', 3)
                ->with(['parent', 'parent.user'])
                ->orderByDesc('adopted_on')
                ->get());

            case 'thismonth':
                return $this->jsonResult(200, Child::whereMonth('adopted_on', now()->month)
                    ->whereYear('adopted_on', now()->year)
                    ->where('adoption_status', 3)
                    ->with(['parent', 'parent.user'])
                    ->orderByDesc('adopted_on')
                    ->get());

            default:
                return $this->jsonResult(200, Child::with(['parent', 'parent.user'])
                ->where('adoption_status', 3)
                ->orderByDesc('adopted_on')
                ->get());
        }
    }

    public function adoptionChoice(Request $request){
        $validator = Validator::make($request->all(),[
            'id' => 'bail|required|exists:Children,id',
            'choice' => 'bail|required|in:accept,reject',
        ]);

        if($validator->fails()){
            return $this->jsonResult(400, $validator->errors());
        }

        $child = Child::find($request->id);

        if(strtolower($request->choice) === 'accept'){
            $child->adoption_status = 3;
        } else {
            $child->adoption_status = 2;
        }

        $child->update();

        return $this->jsonResult(200, "Success");
    }
}
