<?php

namespace App\Http\Livewire\Admin;

use App\Models\Brand;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class AdminEditBrandComponent extends Component
{
    use WithFileUploads;
    public $brand_slug;
    public $brand_id;
    public $name;
    public $slug;
    public $image;
    public $newimage;

    public function mount($brand_slug){
        $this->brand_slug = $brand_slug;
        $brand = Brand::where('slug', $brand_slug)->first();
        $this->brand_id = $brand->id;
        $this->name = $brand->name;
        $this->slug = $brand->slug;
        $this->image = $brand->image;
    }

    public function generateslug(){
        $this->slug = Str::slug($this->name);
    }

    public function updated($fields){
        if($this->newimage){
            $this->validateOnly($fields, [
                'newimage' => 'required|mimes:jpeg,png,jpg',
            ]);
        }else{
            $this->validateOnly($fields, [
                'name' => 'required',
                'slug'=> 'required|unique:brands'
            ]);
        }
    }

    public function updateBrand(){
        if($this->newimage){
            $this->validate([
                'newimage' => 'required|mimes:jpeg,png,jpg',
            ]);
        }else{
            $this->validate([
                'name' => 'required',
                'slug'=> 'required|unique:brands'
            ]);
        }
        $brand = Brand::find($this->brand_id);
        $brand->name = $this->name;
        $brand->slug = $this->slug;
        if($this->newimage){
            $imageName = Carbon::now()->timestamp. '.' . $this->newimage->extension();
            $this->newimage->storeAs('brands', $imageName);
            $brand->image = $imageName;
        }

        $brand->save();
        session()->flash('message', 'Brand has been updated successfully !!!');
        $this->emit('alert', ['type' => 'success', 'message' => 'Brand has been updated successfully !!!']);
    }

    public function render()
    {
        return view('livewire.admin.admin-edit-brand-component')->layout('layouts.base');
    }
}
