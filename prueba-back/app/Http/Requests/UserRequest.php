<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
        $emailRule = Rule::unique((new User())->getTable());
        if (request()->isMethod('put')) {
            $emailRule->ignore($this->route('user'));
            return [
                'email' => ['required','email',$emailRule],
            ];
        }
        if (request()->isMethod('post')) {
            return [
                'email' => 'required|email|unique:contacts',
                'password' => 'required',
            ];
        }
    }

    public function messages()
    {
        $data = [
            'email.required' => 'Campo obligatorio.',
            'email.email' => 'Campo incorrecto.',
            'email.unique' => 'Correo en uso.',
            'password.required' => 'Campo obligatorio.',

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
