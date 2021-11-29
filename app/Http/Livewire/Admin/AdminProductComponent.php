<?php

namespace App\Http\Livewire\Admin;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class AdminProductComponent extends Component
{
    use WithPagination;

    public function deleteProduct($id){
        $product = Product::find($id);
        $product->delete();
        Session()->flash('message', 'Product has been deleted successfully !!!');
        $this->emit('alert', ['type' => 'success', 'message' => 'Product has been deleted successfully !!!']);
    }

    public function render()
    {
        $products = Product::paginate(10);

        return view('livewire.admin.admin-product-component', ['products'=>$products])->layout('layouts.base');
    }
}
