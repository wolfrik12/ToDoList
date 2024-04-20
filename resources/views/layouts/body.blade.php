@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="container-fluid d-flex justify-content-between">
    <form  class="form" method="POST" action="{{ route('list.store') }}">
        @csrf
        <div id="dynamic-fields">
            <div class="form-group">
                <label for="name">Задача</label>
                <input type="text" name="name" class="form-control" required>
                <label for="time">Время</label>
                <input type="text" name="time" class="form-control" required>

            </div>

        </div>
        <button type="submit" class="btn btn-primary">Добавить</button>

    </form>

    @if ($lists->isEmpty())
    <div class="container d-flex justify-content-center">
    <h3>Добавьте свою первую задачу</h3>
</div>
    @else
<div class="container">
 <table class="table">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Задача</th>
        <th scope="col">Время</th>
        <th scope="col">Статус</th>
        <th scope="col"></th>
      </tr>
    </thead>
    <tbody>
        @foreach($lists as $list)
      <tr>
        <td>{{ $list->task_counter }}</td>
        <td>{{$list->name}}</td>
        <td>{{ date('H:i', strtotime($list->time)) }}</td>
        <td>
            @if ($list->status === 'В процессе')
        <form action="{{ route('list.update', $list->id) }}" method="POST">
            @csrf
            @method('PUT')
            <select name="status" onchange="this.form.submit()">
                <option value="В процессе" selected>В процессе</option>
                <option value="Выполнено">Выполнено</option>
            </select>
        </form>
    @else
    <span class="status-color {{ $list->status === 'Выполнено' ? 'status-completed' : 'status-time-expired' }}">
        {{ $list->status }}
    </span>
    @endif
        </td>
        <td>
            <form action="{{ route('list.destroy', $list->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit">X</button>
            </form>
        </td>
      </tr>
@endforeach

    </tbody>
  </table>
  <div class="container d-flex justify-content-center">
    <div class="pagination">
        {{ $lists->links('pagination::bootstrap-4') }}
    </div>
</div>

</div>
@endif
