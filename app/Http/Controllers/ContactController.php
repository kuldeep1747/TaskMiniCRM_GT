<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\CustomField;
use App\Models\ContactCustomFieldValue;
use Illuminate\Http\Request;
use DB;

class ContactController extends Controller
{
    public function index()
    {
        $customFields = CustomField::all();
        return view('contacts.index', compact('customFields'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name'=>'required',
            'email'=>'required|unique:contacts',
            'gender'=>'required'
        ]);

        $contact = Contact::create($request->except('custom_fields'));

        if($request->custom_fields){
            foreach($request->custom_fields as $fieldId => $value){
                ContactCustomFieldValue::create([
                    'contact_id'=>$contact->id,
                    'custom_field_id'=>$fieldId,
                    'value'=>$value
                ]);
            }
        }

        return response()->json(['success'=>true]);
    }

    public function list(Request $request)
    {
        $query = Contact::where('status','Active');

        if($request->name){
            $query->where('full_name','like','%'.$request->name.'%');
        }

        if($request->email){
            $query->where('email','like','%'.$request->email.'%');
        }

        if($request->gender){
            $query->where('gender',$request->gender);
        }

        return response()->json($query->get());
    }

    
    public function update(Request $request, $id)
{
    $request->validate([
        'full_name'=>'required',
        'email'=>'required|unique:contacts,email,'.$id,
        'gender'=>'required'
    ]);

    $contact = Contact::findOrFail($id);

    // Handle file uploads
    if($request->hasFile('profile_image')){
        $file = $request->file('profile_image');
        $path = $file->store('profile_images','public');
        $request->merge(['profile_image' => $path]);
    }

    if($request->hasFile('document')){
        $file = $request->file('document');
        $path = $file->store('documents','public');
        $request->merge(['document' => $path]);
    }

    $contact->update($request->except('custom_fields'));

    // Update custom fields
    if($request->custom_fields){
        foreach($request->custom_fields as $fieldId => $value){
            $cf = $contact->customFieldValues()->where('custom_field_id',$fieldId)->first();
            if($cf){
                $cf->value = $value;
                $cf->save();
            } else {
                $contact->customFieldValues()->create([
                    'custom_field_id' => $fieldId,
                    'value' => $value
                ]);
            }
        }
    }

    return response()->json(['success'=>true]);
}


    public function destroy($id)
    {
        Contact::findOrFail($id)->delete();
        return response()->json(['success'=>true]);
    }
}
