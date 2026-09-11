@include('backend.task.include.__form', [
    'action' => route('admin.task.store'),
    'task' => null,
    'proofTypes' => $proofTypes,
    'statuses' => $statuses,
    'levels' => $levels,
    'payoutMethods' => $payoutMethods,
])
