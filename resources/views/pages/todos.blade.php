<?php

use function Livewire\Volt\state;
use App\Models\Todo;

state(description: '', todos: fn () => Todo::all());
state(search: '');

$addTodo = function () {

    if ($this->description !== '') {
        Todo::create([
            'description' => $this->description,
            'status' => '0'
        ]);
        $this->description = '';
        $this->todos = Todo::all();
    }
}; 

$deleteTodo = function($id){
    Todo::where('id', $id)->delete();
    $this->todos = Todo::all();
};


$statusUpdate = function($id, $status){
    $newStatus = '1';
    if ($status == '1') {
        $newStatus = '0';
    }
    Todo::where('id', $id)->update([
        'status' => $newStatus
    ]);
    $this->todos = Todo::all();
};



$searchTodo = function(){
    $this->todos = Todo::where('description', 'like', '%' . $this->search . '%')->get();
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @vite('resources/css/app.css')
</head>
<body class="p-2">
    @volt
        <div class="max-w-2xl mx-auto">
            <h1 class="mb-1 text-2xl font-medium">Add Todo </h1>
            <form wire:submit="addTodo" class="grid grid-cols-12 gap-2 mb-5">
                <input type="text" wire:model="description" class="col-span-9 p-2 border border-gray-200 focus:border-gray-400 transition duration-300 rounded focus:outline-none" placeholder="Todo..">
                <button type="submit" class="col-span-3 bg-gray-200 hover:bg-gray-300 rounded-md text-gray-700 p-2 transition duration-300">Add</button>
            </form>


            <h1 class="mb-2 text-2xl font-medium">Todos</h1>


            <label class="flex items-center border border-gray-300 mb-2 py-2 px-3 rounded">
                <input type="text" class="w-full focus:outline-none" wire:change="searchTodo" wire:model="search" placeholder="Todolarda Ara...">
                <span><i class="far fa-search text-gray-400"></i> </span>
            </label>

            <ul class="flex flex-col gap-2">
                @foreach ($todos as $todo)
                    <li class="border border-gray-200 rounded-md p-3 relative overflow-hidden">
                        <label class="flex items-center gap-2">
                            <input id="default-checkbox" wire:change="statusUpdate({{ $todo->id }}, {{ $todo->status }})" 
                               type="checkbox"
                               @checked($todo->status === '1')
                               class="transition duration-300 text-blue-600 bg-gray-100 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600">
                            <span class="{{ ($todo->status === '1') ? 'line-through text-rose-500' : ' ' }}">{{ $todo->description }}</span>
                            <button class="absolute top-0 right-0 m-1 hover:bg-rose-100 text-rose-500 transition duration-300 p-2 w-10 h-10 rounded-md focus:bg-rose:100" 
                                    wire:click="deleteTodo({{ $todo->id }})">
                                <i class="far fa-trash"></i>
                            </button>
                        </label>
                    </li>
                @endforeach
            </ul>
        </div>
    @endvolt
</body>
</html>