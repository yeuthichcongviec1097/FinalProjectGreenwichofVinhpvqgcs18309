<div>
    <div class="container" style="padding: 30px 0;">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-6">
                                Add new Category
                            </div>
                            <div class="col-md-6">
                                <a href="{{route('admin.categories')}}" class="btn btn-success pull-right">All
                                    Category</a>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        @if(Session::has('message'))
                            <div class="alert alert-success" role="alert">{{Session::get('message')}}</div>
                        @endif
                        <form class="form-horizontal" wire:submit.prevent="storeCategory">
                            <div class="form-group">
                                <label class="col-md-4 control-label">Category Name</label>
                                <label class="col-md-4">
                                    <input type="text" placeholder="Category Name"
                                           class="form-control input-md" wire:model="name" wire:keyup="generateslug">
                                    @error('name') <span class="text-danger">{{$message}}</span> @enderror
                                </label>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">Category Slug</label>
                                <label class="col-md-4">
                                    <input type="text" placeholder="Category Name"
                                           class="form-control input-md" readonly wire:model="slug">
                                    @error('slug') <span class="text-danger">{{$message}}</span> @enderror
                                </label>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">Parent Category</label>
                                <label class="col-md-4">
                                    <select class="form-control input-md" wire:model="category_id">
                                        <option value="">None</option>
                                        @foreach($categories as $category)
                                            <option value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                    <p style="font-size: 10.5px; color: red">(If you select the parent category, you will create a sub-category for it.)</p>
                                </label>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label"></label>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
