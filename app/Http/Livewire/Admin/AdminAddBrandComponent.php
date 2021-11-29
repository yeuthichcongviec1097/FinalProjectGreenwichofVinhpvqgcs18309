<?php

namespace App\Http\Livewire\Admin;

use App\Models\Brand;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class AdminAddBrandComponent extends Component
{
    use WithFileUploads;
    public $name;
    public $slug;
    public $image;

    public function generateslug(){
        $this->slug = Str::slug($this->name);
    }

    public function updated($fields){
        $this->validateOnly($fields, [
            'name' => 'required',
            'slug'=> 'required|unique:brands'
        ]);
    }

    public function storeBrand(){
        $this->validate([
            'name' => 'required',
            'slug'=> 'required|unique:brands'

        ]);

        $brand = new Brand();
        $brand->name = $this->name;
        $brand->slug = $this->slug;
        if($this->image){
            $imageName = Carbon::now()->timestamp. '.' . $this->image->extension();
            $this->image->storeAs('brands', $imageName);
            $brand->image = $imageName;
        }

        $brand->save();
        session()->flash('message', 'Brand has been created successfully !!!');
        $this->emit('alert', ['type' => 'success', 'message' => 'Brand has been created successfully !!!']);
    }

    public function render()
    {
        return view('livewire.admin.admin-add-brand-component')->layout('layouts.base');
    }
}
