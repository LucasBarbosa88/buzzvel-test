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
            $path = $this->data['attachment_file']->store('tasks');
            $this->data['attachment_file'] = $path;
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