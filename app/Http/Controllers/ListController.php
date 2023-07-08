<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\DoList;
class ListController extends Controller
{
    public function list(){
        $userId = Auth::id();


        if ($userId ) {
            $lists = DoList::where('user_id', $userId)->get();
            return redirect()->route('task');
        } else {

            return redirect()->route('login');
        }
      }
      public function task(){






        $expiredTasks = DoList::where('status', 'В процессе')
        ->where('time', '<', Carbon::now())
        ->get();

foreach ($expiredTasks as $task) {
        $task->status = 'Время вышло';
        $task->save();
    }

   
        $lists = DoList::where('user_id',Auth()->user()->id)->paginate(5);
    


        return view('toDoList',['lists'=>$lists]);
}


    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|regex:/^[А-Яа-яЁё\s]+$/u|max:255',
            'time' => 'required|date_format:H:i',
        ]);



            $status = 'В процессе';

        DoList::create([

            'name' => $validatedData['name'],
            'time' => $validatedData['time'],
            'user_id'=>$request->user()->id,
            'status'=>$status,
        ]);


        // Redirect or return a response
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
