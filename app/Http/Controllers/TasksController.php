<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;

class TasksController extends Controller
{
    // getでTask/にアクセスされた場合の「一覧表示処理」
    public function index()
    {
         // タスク一覧を取得
            $tasks = Task::all();
        // ここで認証状態によって分岐する コントローラーにかく
        if (\Auth::check()) {
            // 認証済みの場合はここ
             $user = \Auth::user();
             $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);
             $data = [
                'user' => $user,
                'tasks' => $tasks,
            ];
          
            // ログインユーザーのタスクのみ全権取得する必要がある
            $tasks = \Auth::user()->tasks;
           
             // タスク一覧ビューでそれを表示
            return view('tasks.index', [
                'tasks' => $tasks,
            ]);
        } else {
            // 認証済みでない場合はここ
            return view('welcome');
        }
    }

       // getでtasks/createにアクセスされた場合の「新規登録画面表示処理」
    public function create()
    {
        $task = new Task;

        // タスク作成ビューを表示
        
            return view('tasks.create', [
            'task' => $task,
        ]);
    }

    // postでmessages/にアクセスされた場合の「新規登録処理」
    public function store(Request $request)
    {
        // バリーデーション
        $request->validate([
            'status' => 'required|max:10',   // 追加
            'content' => 'required|max:255',
        ]);
    
        // タスクを作成
       
        $tasks = new Task;
        $tasks->status = $request->status;
        $tasks->content = $request->content;
        $tasks->user_id = \Auth::user()->id;

        $tasks->save();
        

        

        // トップページへリダイレクトさせる
        return redirect('/');

    }

    // getでmessages/idにアクセスされた場合の「取得表示処理」
    public function show($id)
    {
        // idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);
        
        if ($task->user_id === \Auth::user()->id) {
            // メッセージ詳細ビューでそれを表示
            return view('tasks.show', [
                'task' => $task,
            ]);
        } else {
            // ログインユーザーではないユーザーのタスクなので、詳細ページは表示しない
               return redirect('/');
        }
    }

   // getでmessages/id/editにアクセスされた場合の「更新画面表示処理」
    public function edit($id)
    {
        // idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);

        // メッセージ編集ビューでそれを表示
        if ($task->user_id === \Auth::user()->id) {
            return view('tasks.edit', [
            'task' => $task,
        ]);
        } else {
            // ログインユーザーではないユーザーのタスクなので、詳細ページは表示しない
               return redirect('/');
     }
    }

    // putまたはpatchでmessages/idにアクセスされた場合の「更新処理」
    public function update(Request $request, $id)
    {
        // バリデーション
        $request->validate([
            'status' => 'required|max:10',   // 追加
            'content' => 'required|max:255',
        ]);
        // idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);
        
        // メッセージを更新
       if (\Auth::check()){
        $task->status = $request->status;    // 追加
        $task->content = $request->content;
        $task->save();
       }
        // トップページへリダイレクトさせる
        return redirect('/');
    }

   // deleteでmessages/idにアクセスされた場合の「削除処理」
    public function destroy($id)
    {
        // idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);
        // メッセージを削除
        if (\Auth::id() === $task->user_id) {
            $task->delete();
        }

        // トップページへリダイレクトさせる
       return redirect('/');
    }
}