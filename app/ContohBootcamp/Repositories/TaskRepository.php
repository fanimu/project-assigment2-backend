<?php
namespace App\ContohBootcamp\Repositories;

use App\Helpers\MongoModel;

class TaskRepository
{
	private MongoModel $tasks;
	public function __construct()
	{
		$this->tasks = new MongoModel('tasks');
	}

	/**
	 * Untuk mengambil semua tasks
	 */
	public function getAll()
	{
		$tasks = $this->tasks->get([]);
		return $tasks;
	}

	/**
	 * Untuk mendapatkan task bedasarkan id
	 *  */
	public function getById(string $id)
	{
		$task = $this->tasks->find(['_id'=>$id]);
		return $task;
	}

	/**
	 * Untuk membuat task
	 */
	public function create(array $data)
	{
		$dataSaved = [
			'title'=>$data['title'],
			'description'=>$data['description'],
			'assigned'=>null,
			'subtasks'=> [],
			'created_at'=>time()
		];

		$id = $this->tasks->save($dataSaved);
		return $id;
	}

	/**
	 * Untuk menyimpan task baik untuk membuat baru atau menyimpan dengan struktur json secara bebas
	 *  */
	public function save(array $editedData)
	{
		$id = $this->tasks->save($editedData);
		return $id;
	}

	public function delete(string $id)
	{
		$this->tasks->deleteQuery(['_id'=>$id]);
		return $id;
	}

	public function updateAssign($taskId, $assigned){
		$existingTask = $this->getById($taskId);

        if (!$existingTask) {
            return null;
        }

        $existingTask['assigned'] = $assigned;
        $this->tasks->save($existingTask);

        return $existingTask;
	}

	public function updateUnssign($taskId){
		$existingTask = $this->getById($taskId);

        if (!$existingTask) {
            return null;
        }

        $existingTask['assigned'] = null;
        $this->tasks->save($existingTask);

        return $existingTask;
	}

	public function createSubtask($taskId, $subtask){
		$existingTask = $this->getById($taskId);

        if (!$existingTask) {
            return null;
        }

        $subtasks = isset($existingTask['subtasks']) ? $existingTask['subtasks'] : [];
        $subtask['_id'] = (string) new \MongoDB\BSON\ObjectId();
        $subtasks[] = $subtask;

        $existingTask['subtasks'] = $subtasks;
        $this->tasks->save($existingTask);

        return $existingTask;
	}


	public function deleteSubtask($taskId, $subtaskId){
		$existingTask = $this->getById($taskId);

        if (!$existingTask) {
            return null;
        }

        $subtasks = isset($existingTask['subtasks']) ? $existingTask['subtasks'] : [];

        // Pencarian dan penghapusan subtask
        $subtasks = array_filter($subtasks, function($subtask) use ($subtaskId) {
            return $subtask['_id'] != $subtaskId;
        });

        $existingTask['subtasks'] = array_values($subtasks);
        $this->tasks->save($existingTask);

        return $existingTask;
	}
}