<div>
    <style>
        nav svg {
            height: 20px;
        }

        nav .hidden {
            display: block !important;
        }
    </style>
    <div class="container" style="padding: 30px 0;">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-6">
                                All Users
                            </div>
{{--                            <div class="col-md-6">--}}
{{--                                <a href="{{route('admin.addcategory')}}" class="btn btn-success pull-right">Add New--}}
{{--                                    Category</a>--}}
{{--                            </div>--}}
                        </div>
                    </div>
                    <div class="panel-body">
                        @if(Session::has('message'))
                            <div class="alert alert-success" role="alert">{{Session::get('message')}}</div>
                        @endif
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>User Name</th>
                                <th>Email</th>
                                <th>Type</th>
                                <th>Account creation date</th>
                                <th class="col-md-1"><center>Action</center></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{$user->id}}</td>
                                    <td>{{$user->name}}</td>
                                    <td>{{$user->email}}</td>
                                    <td>{{$user->utype}}</td>
                                    <td>{{$user->created_at}}</td>
                                    <td>
                                        <a href="{{route('admin.edituser', ['id'=>$user->id])}}"><i
                                                class="fa fa-edit fa-2x"></i></a>
                                        <a href="#" onclick="confirm('Are you sure, You want to delete this user?') || event.stopImmediatePropagation()" wire:click.prevent="deleteCategory({{$user->id}})" style="margin-left: 10px;"><i
                                                class="fa fa-times fa-2x text-danger"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <center>{{$users->links('pagination::bootstrap-4')}}</center>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
