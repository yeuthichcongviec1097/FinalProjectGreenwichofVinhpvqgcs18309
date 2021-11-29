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
                                All Categories
                            </div>
                            <div class="col-md-6">
                                <a href="{{route('admin.addbrand')}}" class="btn btn-success pull-right">Add New
                                    Brand</a>
                            </div>
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
                                <th>Image</th>
                                <th>Brand Name</th>
                                <th>Slug</th>
                                <th class="col-md-1">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($brands as $brand)
                                <tr>
                                    <td>{{$brand->id}}</td>
                                    <td><img src="{{asset('assets/images/brands')}}/{{$brand->image}}"
                                             alt="{{$brand->name}}" width="60px"></td>
                                    <td>{{$brand->name}}</td>
                                    <td>{{$brand->slug}}</td>
                                    <td>
                                        <a href="{{route('admin.editbrand', ['brand_slug'=>$brand->slug])}}"><i
                                                class="fa fa-edit fa-2x"></i></a>
                                        <a href="#" onclick="confirm('Are you sure, You want to delete this Brand?') || event.stopImmediatePropagation()" wire:click.prevent="deleteCategory({{$brand->id}})" style="margin-left: 10px;"><i
                                                class="fa fa-times fa-2x text-danger"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <center>{{$brands->links('pagination::bootstrap-4')}}</center>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
