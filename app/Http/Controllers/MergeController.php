<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\ContactMergeHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MergeController extends Controller
{
    public function merge(Request $request)
    {
        // ✅ Validation
        $request->validate([
            'master_id' => 'required|different:secondary_id|exists:contacts,id',
            'secondary_id' => 'required|exists:contacts,id',
        ]);

        try {

            $master = Contact::with('customFieldValues')->findOrFail($request->master_id);
            $secondary = Contact::with('customFieldValues')->findOrFail($request->secondary_id);

            DB::transaction(function () use ($master, $secondary) {

               
                $emails = $master->secondary_emails ?? [];

                if ($secondary->email && $secondary->email !== $master->email) {
                    if (!in_array($secondary->email, $emails)) {
                        $emails[] = $secondary->email;
                    }
                }

                $master->secondary_emails = $emails;

                
                $phones = $master->secondary_phones ?? [];

                if ($secondary->phone && $secondary->phone !== $master->phone) {
                    if (!in_array($secondary->phone, $phones)) {
                        $phones[] = $secondary->phone;
                    }
                }

                $master->secondary_phones = $phones;

                
                foreach ($secondary->customFieldValues ?? [] as $secValue) {

                    $masterValue = $master->customFieldValues()
                        ->where('custom_field_id', $secValue->custom_field_id)
                        ->first();

                    if (!$masterValue) {
                        // Move value to master
                        $secValue->contact_id = $master->id;
                        $secValue->save();

                    } elseif ($masterValue->value !== $secValue->value) {

                        $history = $masterValue->history ?? [];

                        if (!in_array($secValue->value, $history)) {
                            $history[] = $secValue->value;
                        }

                        $masterValue->history = $history;
                        $masterValue->save();
                    }
                }

                
                $secondary->status = 'Merged';
                $secondary->save();

                $master->save();

                
                ContactMergeHistory::create([
                    'master_contact_id' => $master->id,
                    'merged_contact_id' => $secondary->id,
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Contacts merged successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
