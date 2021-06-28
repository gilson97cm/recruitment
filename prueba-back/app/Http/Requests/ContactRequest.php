<?php

namespace App\Http\Requests;

use App\Contact;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class ContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $emailRule = Rule::unique((new Contact())->getTable());
        if (request()->isMethod('put')) {
            $emailRule->ignore($this->route('contact'));
            return [
                'country' => 'required',
                'city' => 'required',
                'state' => 'required',
                'address' => 'required',
                'last_name' => 'required',
                'email' => ['required','email',$emailRule],
//                'photo' => 'required|image',
                'mobile' => 'required',
//                'contract' => 'required',
                'salary' => 'required',
                'active' => 'required',
            ];
        }
        if (request()->isMethod('post')) {
            return [
                'country' => 'required',
                'city' => 'required',
                'state' => 'required',
                'address' => 'required',
                'last_name' => 'required',
                'email' => 'required|email|unique:contacts',
                'photo' => 'required|image',
                'mobile' => 'required',
                'contract' => 'required',
                'salary' => 'required|numeric',
                'active' => 'required',
            ];
        }
    }

    public function messages()
    {
        $data = [
            'country.required' => 'Campo obligatorio.',
            'city.required' => 'Campo obligatorio.',
            'state.required' => 'Campo obligatorio.',
            'address.required' => 'Campo obligatorio.',
            'last_name.required' => 'Campo obligatorio.',
            'email.required' => 'Campo obligatorio.',
            'email.email' => 'Campo incorrecto.',
            'email.unique' => 'Correo en uso.',
            'photo.required' => 'Campo obligatorio.',
            'photo.image' => 'La foto debe ser una imagen.',
            'mobile.required' => 'Campo obligatorio.',
            'contract.required' => 'Campo obligatorio.',
            'salary.required' => 'Campo obligatorio.',
            'salary.numeric' => 'Campo incorrecto.',
            'active.required' => 'Campo obligatorio.',
        ];
        return $data;
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $response = new JsonResponse([
            'statusCode' => 422,
            'meta' => [
                'message' => 'Los datos proporcionados no son válidos',
                'errors' => $validator->errors()
            ]], 422);

        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}
