@extends('layouts.admin')

@section('content')

    @if(Session::has('deleted_user'))

            <p class="bg-danger">{{session('deleted_user')}}</p>

    @endif

    @if(Session::has('created_user'))

        <p class="bg-success">{{session('created_user')}}</p>

    @endif

    <h1>Users</h1>

    <table class="table">
       <thead>
          <tr>
              <th>ID</th>
              <th>Photo</th>
              <th>Name</th>
              <th>Email</th>
              <th>Role</th>
              <th>Status</th>
              <th>Checkbox W</th>
              <th>Created</th>
              <th>Updated</th>
              <th>Edit</th>
              <th>Delete</th>
          </tr>
        </thead>

        <tbody>

        @if($users)

            @foreach($users as $user)

          <tr>
              <td>{{$user->id}}</td>
              <td><img height="50" src="{{$user->photo ? $user->photo->file : 'http://placehold.it/400x400'}}" alt=""></td>
              <td><a href="{{route('admin.users.edit', $user->id)}}">{{$user->name}}</a></td>
              <td>{{$user->email}}</td>
              <td>{{$user->role->name}}</td>
              <td>{{$user->is_active == 1 ? 'Active' : 'Not Active'}}</td>
              {!! $user->is_active == 1 ? '<td><input type="checkbox" checked></td>' : '<td><input type="checkbox"></td>' !!}
              <td>{{$user->created_at->diffforHumans()}}</td>
              <td>{{$user->updated_at->diffforHumans()}}</td>
              <td><a href="{{route('admin.users.edit', $user->id)}}"><button id="btn-add" class="btn btn-primary btn-xs" ng-click="toggle('add', 0)">Edit</button></a></td>
              {{--<td><a href="{{route('admin.users.destroy', $user->id, 'AdminUsersController@destroy')}}"><button id="btn-add" class="btn btn-primary btn-xs" ng-click="toggle('add', 0)">Delete</button></a></td>--}}
          </tr>

          @endforeach

            @endif

       </tbody>
     </table>

@stop