<?php

namespace App\Http\Controller;

use App\ContohBootcamp\Services\TaskService;
use App\Helpers\MongoModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TaskController extends Controller {
	private TaskService $taskService;
	public function __construct() {
		$this->taskService = new TaskService();
	}

	public function showTasks()
	{
		$tasks = $this->taskService->getTasks();
		return response()->json($tasks);
	}

	public function showTask(Request $request)
	{
		$taskId = $request->post('task_id');
		$task = $this->taskService->getById($taskId);
		return response()->json($task);
	}

	public function createTask(Request $request)
	{
		$request->validate([
			'title'=>'required|string|min:3',
			'description'=>'required|string'
		]);

		$data = [
			'title'=>$request->post('title'),
			'description'=>$request->post('description')
		];

		$dataSaved = [
			'title'=>$data['title'],
			'description'=>$data['description'],
			'assigned'=>null,
			'subtasks'=> [],
			'created_at'=>time()
		];

		$id = $this->taskService->addTask($dataSaved);
		$task = $this->taskService->getById($id);

		return response()->json($task);
	}


	public function updateTask(Request $request)
	{
		$request->validate([
			'task_id'=>'required|string',
			'title'=>'string',
			'description'=>'string',
			'assigned'=>'string',
			'subtasks'=>'array',
		]);

		$taskId = $request->post('task_id');
		$formData = $request->only('title', 'description', 'assigned', 'subtasks');
		$task = $this->taskService->getById($taskId);

		$this->taskService->updateTask($task, $formData);

		$task = $this->taskService->getById($taskId);

		return response()->json($task);
	}


	// TODO: deleteTask()
	public function deleteTask(Request $request)
	{
		$request->validate([
            'task_id' => 'required',
        ]);

        $taskId = $request->task_id;
        $result = $this->taskService->deleteTask($taskId);

        return response()->json([
            'message' => $result['message']
        ], $result['status']);
	}

	// TODO: assignTask()
	public function assignTask(Request $request)
	{
		$request->validate([
            'task_id' => 'required',
            'assigned' => 'required',
        ]);

        $taskId = $request->input('task_id');
        $assigned = $request->input('assigned');

        $result = $this->taskService->assignTask($taskId, $assigned);

        return response()->json([
            'message' => $result['message'],
			'task' => $result['task'],
			'status' => $result['status'],
		]);
	}

	// TODO: unassignTask()
	public function unassignTask(Request $request)
	{
		$request->validate([
            'task_id' => 'required',
        ]);

        $taskId = $request->input('task_id');

        $result = $this->taskService->unassignTask($taskId);

        return response()->json([
            'message' => $result['message'],
			'task' => $result['task'],
			'status' => $result['status'],
		]);
	}

	// TODO: createSubtask()
	public function createSubtask(Request $request)
	{
		$request->validate([
            'task_id' => 'required',
            'title' => 'required|string',
            'description' => 'required|string',
        ]);

        $taskId = $request->input('task_id');
        $title = $request->input('title');
        $description = $request->input('description');

        $result = $this->taskService->createSubtask($taskId, $title, $description);

		return response()->json([
            'message' => $result['message'],
			'task' => $result['task'],
			'status' => $result['status'],
		]);
    }
	

	// TODO deleteSubTask()
	public function deleteSubtask(Request $request)
	{
		$request->validate([
            'task_id' => 'required',
            'subtask_id' => 'required',
        ]);

        $taskId = $request->input('task_id');
        $subtaskId = $request->input('subtask_id');

        $result = $this->taskService->deleteSubtask($taskId, $subtaskId);

        return response()->json([
            'message' => $result['message'],
			'task' => $result['task'],
			'status' => $result['status'],
		]);
	}

}