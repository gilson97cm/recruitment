<?php

namespace App\Http\Controllers;

use App\Contact;
use App\Http\Requests\ContactRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $contact = Contact::all();
        return response()->json([
            'statusCode' => 200,
            'code' => 'ALL_CONTACTS',
            'contact' => $contact
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ContactRequest  $request
     * @return JsonResponse
     */
    public function store(ContactRequest $request)
    {
        try {
            DB::beginTransaction();
            $contact = new Contact([
                'country'=> $request->country,
                'city'=> $request->city,
                'state'=> $request->state,
                'address'=> $request->address,
                'last_name'=> $request->last_name,
                'email'=> $request->email,
                'mobile'=> $request->mobile,
                'salary'=> $request->salary,
                'active'=> $request->active,
            ]);
            $contact->save();
            //photo
            if ($request->file('photo')) {
                $name = "image-" . time() . '.' . $request->file('photo')->getClientOriginalExtension();
                $path_image = 'images/' . $request->file('photo')->storeAs('/', $name, 'images');
            }
            $contact->update(['photo' => $path_image]);
            //contract
            if ($request->file('contract')) {
                $name = "document-" . time() . '.' . $request->file('contract')->getClientOriginalExtension();
                $path_doc = 'documents/' . $request->file('contract')->storeAs('/', $name, 'documents');
            }
            $contact->update(['contract' => $path_doc]);
            DB::commit();
            return response()->json([
                'statusCode' => 200,
                'code' => 'SUCCESS_STORE_CONTACT',
                'message' => 'contact saved successfully',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'statusCode' => 500,
                'code' => 'ERROR_STORE_CONTACT',
                'message' => 'error saving contact',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function edit($id)
    {
        $contact = Contact::find($id);

        if (isset($contact)) {
            return response()->json([
                'statusCode' => 200,
                'code' => 'CONTACT_FOUND',
                'message' => 'contact found',
                'contact' => $contact
            ], 200);
        }
        return response()->json([
            'statusCode' => 404,
            'code' => 'CONTACT_NOT_FOUND',
            'message' => 'error finding contact',
            'contact' => $contact,
        ], 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ContactRequest  $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(ContactRequest $request, $id)
    {
        try {
            $contact = Contact::find($id);
            DB::beginTransaction();
            $contact->country = $request->country;
            $contact->city = $request->city;
            $contact->state = $request->state;
            $contact->address = $request->address;
            $contact->last_name = $request->last_name;
            $contact->email = $request->email;
            $contact->mobile = $request->mobile;
            $contact->salary = $request->salary;
            $contact->active = $request->active;
            $contact->save();

            $path_image =$contact->photo;
            $path_doc = $contact->contract;
            //photo
            if ($request->file('photo')) {
                $name = "image-" . time() . '.' . $request->file('photo')->getClientOriginalExtension();
                $path_image = 'images/' . $request->file('photo')->storeAs('/', $name, 'images');
            }
            $contact->update(['photo' => $path_image]);
            //contract
            if ($request->file('contract')) {
                $name = "document-" . time() . '.' . $request->file('contract')->getClientOriginalExtension();
                $path_doc = 'documents/' . $request->file('contract')->storeAs('/', $name, 'documents');
            }
            $contact->update(['contract' => $path_doc]);
            DB::commit();
            return response()->json([
                'statusCode' => 200,
                'code' => 'SUCCESS_UPDATE_CONTACT',
                'message' => 'contact updated successfully',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'statusCode' => 500,
                'code' => 'ERROR_UPDATE_CONTACT',
                'message' => 'error updating contact',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        try {
            $contact = Contact::find($id);
            if (isset($contact)) {
                $contact->delete();
                return response()->json([
                    'statusCode' => 200,
                    'code' => 'SUCCESS_DELETE_CONTACT',
                    'message' => 'contact deleted successfully',
                ], 200);
            }
            return response()->json([
                'statusCode' => 404,
                'code' => 'CONTACT_NOT_FOUND',
                'message' => 'error finding contact',
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => 500,
                'code' => 'ERROR_DELETE_CONTACT',
                'message' => 'error deleting contact',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }
}
