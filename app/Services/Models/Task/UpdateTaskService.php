<?php

namespace App\Services\Models\Task;

use App\Models\Task;
use App\Services\BaseService;
use Illuminate\Support\Facades\Storage;

class UpdateTaskService extends BaseService
{
  protected $data = [];

  public function __construct(array $data)
  {
    $this->data = $data;
  }

  public function run()
  {
    if($this->data['attachment_file']){
      $filenameWithExt = $this->data['attachment_file']->getClientOriginalName();
      $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
      $extension = $this->data['attachment_file']->getClientOriginalExtension();
      $fileNameToStore= $filename.'_'.time().'.'.$extension;
      $this->data['attachment_file'] = $this->data['attachment_file']->storeAs('tasks', $fileNameToStore);
    }
    $task = Task::find($this->data['id']);
    if($task->attachment_file && Storage::exists($task->attachment_file)) {
      Storage::delete($task->attachment_file);
    }
    if ($task->update($this->data)) {
      return $task->refresh();
    }
    try {
    } catch (\Throwable $th) {
      report($th);
    }
    return false;
  }
}
