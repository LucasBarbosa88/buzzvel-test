<?php

namespace App\Http\Requests;

class UpdateTaskRequest extends UserBaseRequest
{
    public function rules()
    {
        return [
            /**
             * @param Model/Task
             */
            "title" => [
                "required",
                "string"
            ],
            "description" => [
                "required",
                "string"
            ],
            "attachment_file" => [
                "nullable",
                "mimes:png,jpg,jpeg,doc,pdf",
                "max:10000"
            ],
            "complete" => [
                "required"
            ]
        ];
    }
}
