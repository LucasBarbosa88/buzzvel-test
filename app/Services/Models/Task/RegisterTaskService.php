<?php

namespace App\Services\Models\Task;

use App\Models\Task;
use App\Services\BaseService;

class RegisterTaskService extends BaseService
{
    protected $data = [];

    public function __construct(array $data) {
        $this->data = $data;
    }

    public function run() {
        if($this->data['attachment_file']){
            $filenameWithExt = $this->data['attachment_file']->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $this->data['attachment_file']->getClientOriginalExtension();
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            $this->data['attachment_file'] = $this->data['attachment_file']->storeAs('tasks', $fileNameToStore);
        }
        $task = new Task($this->data);
        if($task->save()) {
            return $task->refresh();
        }
        try {} catch (\Throwable $th) {
            report($th);
        }
        return false;
    }

    public function uploadFiles() {

    }
}