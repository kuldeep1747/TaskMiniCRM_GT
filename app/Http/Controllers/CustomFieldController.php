<?php

namespace App\Http\Controllers;

use App\Models\CustomField;
use Illuminate\Http\Request;

class CustomFieldController extends Controller
{
    public function store(Request $request)
    {
        CustomField::create($request->all());
        return response()->json(['success'=>true]);
    }

    public function destroy($id)
    {
        CustomField::findOrFail($id)->delete();
        return response()->json(['success'=>true]);
    }
}
