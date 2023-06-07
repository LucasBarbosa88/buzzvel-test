<?php

namespace App\Http\Requests\Api;

use App\Http\Controllers\Controller;
use Throwable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ApiRequest extends FormRequest
{
    public $validated = null;
    public $_params = [];

    public static function validate(Request $request, array $params = [])
    {
        $instance = new static;
        $instance->_params = $params;

        try
        {
            $data = (new Controller() )->validate(
                $request,
                $instance->rules(),
                $instance->messages(),
                $instance->customAttributes()
            );
        }

        catch (ValidationException $th)
        {
            $body = [
                'messages' => [],
            ];

            foreach($th->validator->getMessageBag()->getMessages() as $field => $messages)
            {
                $body['messages'] = array_merge($body['messages'], $messages);
                $body[$field] = $messages;
            }

            $response = response()->json($body , $th->status);
            throw new ValidationException($th->validator, $response);
        }

        try
        {
            $keys = array_keys($instance->rules());
            foreach( $keys as $input )
            {
                try{
                    if($request->hasFile($input))
                    {
                        $data[$input] = $request->file($input);
                    }
                }
                catch(Throwable $th){ }
            }

        }
        catch(Throwable $th){}

        return $data;
    }
}
