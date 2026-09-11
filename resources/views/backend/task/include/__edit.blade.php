@include('backend.task.include.__form', [
    'action' => route('admin.task.update', $task->id),
    'task' => $task,
    'proofTypes' => $proofTypes,
    'statuses' => $statuses,
    'levels' => $levels,
    'payoutMethods' => $payoutMethods,
])
