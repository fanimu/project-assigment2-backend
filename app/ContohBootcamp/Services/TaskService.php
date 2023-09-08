<?php

namespace App\ContohBootcamp\Services;

use App\ContohBootcamp\Repositories\TaskRepository;

class TaskService {
	private TaskRepository $taskRepository;

	public function __construct() {
		$this->taskRepository = new TaskRepository();
	}

	/**
	 * NOTE: untuk mengambil semua tasks di collection task
	 */
	public function getTasks()
	{
		$tasks = $this->taskRepository->getAll();
		return $tasks;
	}

	public function getById(string $taskId)
	{
		$task = $this->taskRepository->getById($taskId);
		return $task;
	}


	/**
	 * NOTE: menambahkan task
	 */
	public function addTask(array $data)
	{
		$taskId = $this->taskRepository->create($data);
		return $taskId;
	}

	/**
	 * NOTE: UNTUK mengambil data task
	 */
	

	/**
	 * NOTE: untuk update task
	 */
	public function updateTask(array $editTask, array $formData)
	{
		if(isset($formData['title']))
		{
			$editTask['title'] = $formData['title'];
		}

		if(isset($formData['description']))
		{
			$editTask['description'] = $formData['description'];
		}

		$id = $this->taskRepository->save( $editTask);
		return $id;
	}

	public function deleteTask($taskId)
	{
		$existingTask = $this->taskRepository->getById($taskId);

        if (!$existingTask) {
            return [
                'message' => "Task tidak ada",
                'status' => 401,
            ];
        }

        $this->taskRepository->delete($taskId);

        return [
            'message' => "Berhasil menghapus task",
            'status' => 200,
        ];
	}

	public function assignTask($taskId, $assigned){
		$existingTask = $this->taskRepository->getById($taskId);

		if (!$existingTask){
			$message = 'Task tidak ada';
			$status = 401;
		}else{
			$message = 'Berhasil mengassign task';
			$status = 200;
		}

		$updateTask = $this->taskRepository->updateAssign($taskId, $assigned);

		return [
			'message' => $message,
			'task' => $updateTask,
			'status' => $status,
		];
	}

	public function unassignTask($taskId){
		$existingTask = $this->taskRepository->getById($taskId);

		if (!$existingTask){
			$message = 'Task tidak ada';
			$status = 401;
		}else{
			$message = 'Berhasil mengassign task';
			$status = 200;
		}

		$updateTask = $this->taskRepository->updateUnssign($taskId);

		return [
			'message' => $message,
			'task' => $updateTask,
			'status' => $status,
		];
	}

	public function createSubtask($taskId, $title, $description)
    {
        $existingTask = $this->taskRepository->getById($taskId);

        if (!$existingTask) {
			$message = 'Task tidak ada';
			$status = 401;
        }else{
			$message = 'Subtask berhasil dibuat';
			$status = 200;
		}

        $subtask = [
            'title' => $title,
            'description' => $description,
        ];

        $updatedTask = $this->taskRepository->createSubtask($taskId, $subtask);

        return [
            'message' => $message,
            'task' => $updatedTask,
            'status' => $status,
        ];
    }


	public function deleteSubtask($taskId, $subtaskId)
    {
        $existingTask = $this->taskRepository->getById($taskId);

        if (!$existingTask) {
			$message = 'Task tidak ada';
			$status = 401;
        }else{
			$message = 'Subtask berhasil dihapus';
			$status = 200;
		}

        $updatedTask = $this->taskRepository->deleteSubtask($taskId, $subtaskId);

        return [
            'message' => $message,
            'task' => $updatedTask,
            'status' => $status,
        ];
    }
}