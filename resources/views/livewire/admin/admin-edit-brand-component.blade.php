<div>
    <div class="container" style="padding: 30px 0;">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-6">
                                Edit Brand
                            </div>
                            <div class="col-md-6">
                                <a href="{{route('admin.brands')}}" class="btn btn-success pull-right">All
                                    Brand</a>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        @if(Session::has('message'))
                            <div class="alert alert-success" role="alert">{{Session::get('message')}}</div>
                        @endif
                        <form class="form-horizontal" wire:submit.prevent="updateBrand">
                            <div class="form-group">
                                <label class="col-md-4 control-label">Brand Name</label>
                                <label class="col-md-4">
                                    <input type="text" placeholder="Brand Name"
                                           class="form-control input-md" wire:model="name" wire:keyup="generateslug">
                                    @error('name') <p class="text-danger">{{$message}}</p>  @enderror
                                </label>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">Brand Slug</label>
                                <label class="col-md-4">
                                    <input type="text" placeholder="Brand Name"
                                           class="form-control input-md" readonly wire:model="slug">
                                    @error('slug') <p class="text-danger">{{$message}}</p>  @enderror
                                </label>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">Brand Image</label>
                                <div class="col-md-4">
                                    <input type="file" class="input-file" name="" id="" placeholder="Brand Image" accept=".gif, .jpeg, .jpg, .png"
                                           wire:model="newimage">
                                    @if($newimage)
                                        <img src="{{$newimage->temporaryUrl()}}" alt="" width="120">
                                    @else
                                        <img src="{{asset('assets/images/brands')}}/{{$image}}" alt="{{$name}}" width="120">
                                    @endif
                                    @error('newimage') <p class="text-danger">{{$message}}</p> @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label"></label>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
