<div>
    <div class="container" style="padding: 30px 0;">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-6">
                                Edit User
                            </div>
                            <div class="col-md-6">
                                <a href="{{route('admin.users')}}" class="btn btn-success pull-right">All
                                    Users</a>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        @if(Session::has('message'))
                            <div class="alert alert-success" role="alert">{{Session::get('message')}}</div>
                        @endif
                        <form class="form-horizontal" wire:submit.prevent="updateUser">
                            <div class="form-group">
                                <label class="col-md-4 control-label">User Email</label>
                                <label class="col-md-4">
                                    <input type="text" placeholder="Email"
                                           class="form-control input-md" wire:model="email" readonly>
                                    @error('email') <p class="text-danger">{{$message}}</p>  @enderror
                                </label>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">User Name</label>
                                <label class="col-md-4">
                                    <input type="text" placeholder="Fullname"
                                           class="form-control input-md" wire:model="name">
                                    @error('name') <p class="text-danger">{{$message}}</p>  @enderror
                                </label>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">Category Slug</label>
                                <label class="col-md-4">
                                    <input type="text" placeholder="Category Name"
                                           class="form-control input-md" readonly wire:model="slug">
                                    @error('slug') <p class="text-danger">{{$message}}</p>  @enderror
                                </label>
                            </div>

                            <div class="form-group">
                                <label class="col-md-offset-4 control-label checkbox-field">
                                    <input class="frm-input " name="have-code" id="have-code" value="1" type="checkbox"
                                           wire:model="confirmChangePassword"><span> Change Password</span>
                                </label>
                            </div>
                            @if($confirmChangePassword == 1)
                                <div class="form-group">
                                    <label class="col-md-4 control-label">User Password</label>
                                    <label class="col-md-4">
                                        <input type="password" placeholder="User Password"
                                               class="form-control input-md" wire:model="password">
                                        @error('password') <p class="text-danger">{{$message}}</p>  @enderror
                                    </label>
                                </div>
                            @endif

                            <div class="form-group">
                                <label class="col-md-4 control-label">Type User</label>
                                <div class="col-md-4">
                                    <select class="form-control" wire:model="utype">
                                        <option value="ADM">Admin</option>
                                        <option value="USR">User</option>
                                        @error('utype') <p class="text-danger">{{$message}}</p> @enderror
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label"></label>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary">Update User</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
