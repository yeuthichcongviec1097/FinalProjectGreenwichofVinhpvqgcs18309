<?php

namespace App\Http\Livewire\Admin;

use App\Models\Brand;
use Livewire\Component;
use Livewire\WithPagination;

class AdminBrandComponent extends Component
{
    use WithPagination;

    public function deleteCategory($id){
        $brand = Brand::find($id);
        $brand->delete();
        session()->flash('message', 'Brand has been deleted successfully !!!');
        $this->emit('alert', ['type' => 'success', 'message' => 'Brand has been deleted successfully !!!']);
    }

    public function render()
    {
        $brands = Brand::paginate(5);

        return view('livewire.admin.admin-brand-component', ['brands'=>$brands])->layout('layouts.base');
    }
}
