<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\DoList;

class ListController extends Controller
{
    public function list()
    {
        $user = Auth::user();

        if ($user) {
            return redirect()->route('task');
        } else {
            return redirect()->route('login');
        }
    }


    public function task()
{
    // Обновляем статусы просроченных задач на "Время вышло"
    $expiredTasks = DoList::where('status', 'В процессе')
                          ->where('time', '<=', Carbon::now())
                          ->get();

    foreach ($expiredTasks as $task) {
        $task->status = 'Время вышло';
        $task->save();
    }

    // Получаем все задачи пользователя с учетом пагинации
    $lists = DoList::where('user_id', Auth()->user()->id)->paginate(5);

    return view('toDoList', ['lists' => $lists]);
}
      public function store(Request $request)
      {
          $validatedData = $request->validate([
              'name' => 'required|string|max:255',
              'time' => 'required|date_format:H:i',
          ]);
          $user_id = $request->user()->id;
          $task_counter = DoList::where('user_id', $user_id)->count() + 1;
          $task_id = $user_id . '_' . $task_counter;


          $task = new DoList();
          $task->user_id = $user_id;
          $task->task_id = $task_id;
          $task->name = $validatedData['name'];
          $task->time = $validatedData['time'];
          $task->status = 'В процессе';
          $task->task_counter = $task_counter;


          $task->save();


          return redirect()->back()->with('success', 'Вы успешно добавили задачу!');
      }

public function update(Request $request, $id)
{
    $validatedData = $request->validate([
        'status' => 'required|string|max:255',
    ]);

    $list = DoList::findOrFail($id);
    $list->status = $validatedData['status'];
    $list->save();

    return redirect()->back()->with('success', 'Статус обновлен!');
}
public function destroy($id)
{
    $list = DoList::findOrFail($id);
    $list->delete();

    return redirect()->back()->with('success', 'Задача успешно удалена!');
}
}
