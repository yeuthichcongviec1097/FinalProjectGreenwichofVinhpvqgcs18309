<?php

namespace App\Http\Livewire\Admin;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class AdminEditProductComponent extends Component
{
    use WithFileUploads;
    public $name;
    public $slug;
    public $short_description;
    public $description;
    public $regular_price;
    public $sale_price;
    public $SKU;
    public $stock_status;
    public $featured;
    public $quantity;
    public $image;
    public $category_id;
    public $brand_id;
    public $newimage;
    public $product_id;

    public $images;
    public $newimages;
    public $scategory_id;

    public function mount($product_slug){
        $product = Product::where('slug', $product_slug)->first();
        $this->name = $product->name;
        $this->slug = $product->slug;
        $this->short_description = $product->short_description;
        $this->description = $product->description;
        $this->regular_price = $product->regular_price;
        $this->sale_price = $product->sale_price;
        $this->SKU = $product->SKU;
        $this->stock_status = $product->stock_status;
        $this->featured = $product->featured;
        $this->quantity = $product->quantity;
        $this->image = $product->image;
        $this->images = explode(",", $product->images);
        $this->category_id = $product->category_id;
        $this->brand_id = $product->brand_id;
        $this->scategory_id = $product->subcategory_id;
        $this->product_id = $product->id;
    }

    public function generateSlug(){
        $this->slug = Str::slug($this->name, '-');
    }

    public function updated($fields){
        if($this->newimage){
            $this->validateOnly($fields, [
               'newimage' => 'required|mimes:jpeg,png,jpg',
            ]);
        }else{
            $this->validateOnly($fields, [
                'name' => 'required',
                'slug' => 'required',
                'short_description' => 'required',
                'description' => 'required',
                'regular_price' => 'required|numeric',
                'SKU' => 'required',
                'stock_status' => 'required',
                'quantity' => 'required|numeric',
                'category_id' => 'required',
                'brand_id' => 'required',
            ]);
        }
    }

    public function updateProduct(){
        if($this->newimage){
            $this->validate([
                'newimage' => 'required|mimes:jpeg,png,jpg',
            ]);
        }else{
            $this->validate([
                'name' => 'required',
                'slug' => 'required',
                'short_description' => 'required',
                'description' => 'required',
                'regular_price' => 'required|numeric',
                'SKU' => 'required',
                'stock_status' => 'required',
                'quantity' => 'required|numeric',
                'category_id' => 'required',
                'brand_id' => 'required',
            ]);
        }

        $product = Product::find($this->product_id);
        $product->name = $this->name;
        $product->slug = $this->slug;
        $product->short_description = $this->short_description;
        $product->description = $this->description;
        $product->regular_price = $this->regular_price;
        $product->sale_price = $this->sale_price;
        $product->SKU = $this->SKU;
        $product->stock_status = $this->stock_status;
        $product->featured = $this->featured;
        $product->quantity = $this->quantity;
        if($this->newimage){
            $imageName = Carbon::now()->timestamp. '.' . $this->newimage->extension();
            $this->newimage->storeAs('products', $imageName);
            $product->image = $imageName;
        }

        $imagesName = '';
        if($this->newimages){
            foreach ($this->newimages as $key=>$image){
                $imgName = Carbon::now()->timestamp . $key . '.' . $image->extension();
                $image->storeAs('products', $imgName);
                $imagesName = $imagesName . ',' . $imgName;
            }
        }
        $product->images = $imagesName;

        $product->category_id = $this->category_id;
        if($this->scategory_id){
            $product->subcategory_id = $this->scategory_id;
        }
        $product->brand_id = $this->brand_id;
        $product->save();

        session()->flash('message', 'Product has been updated successfully !!!');
        $this->emit('alert', ['type' => 'success', 'message' => 'Product has been updated successfully !!!']);
    }

    public function changeSubcategory(){
        $this->category_id = 0;
    }

    public function render()
    {
        $categories = Category::all();
        $scategories = Subcategory::where('category_id', $this->category_id)->orderBy('name', 'ASC')->get();
        $brands = Brand::orderBy('name', 'ASC')->get();

        return view('livewire.admin.admin-edit-product-component', ['categories'=>$categories, 'brands'=>$brands, 'scategories'=>$scategories])->layout('layouts.base');
    }
}
