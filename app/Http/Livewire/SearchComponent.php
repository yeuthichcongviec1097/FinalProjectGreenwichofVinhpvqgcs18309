<?php

namespace App\Http\Livewire;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Sale;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Category;
use Cart;


class SearchComponent extends Component
{
    public $sorting;
    public $pagesize;

    public $search;
    public $product_cat;
    public $product_cat_id;

    public $min_price;
    public $max_price;

    public function mount (){
        $this->sorting = "default";
        $this->pagesize = 12;
        $this->fill(request()->only('search', 'product_cat', 'product_cat_id'));

        $this->min_price = 1;
        $this->max_price = 2200;
    }

    public function store($product_id, $product_name, $product_price)
    {
        Cart::instance('cart')->add($product_id, $product_name, 1, $product_price)->associate('App\Models\Product');
        session()->flash('success_message', 'Item added in Cart');
        $this->emitTo('cart-count-component', 'refreshComponent');
        $this->emit('alert', ['type' => 'success', 'message' => 'Product move to cart successfully !!!']);
        return;
    }

    public function destroy($product_id)
    {
        foreach (Cart::instance('cart')->content() as $citem){
            if($citem->id == $product_id){
                Cart::instance('cart')->remove($citem->rowId);
                $this->emitTo('cart-count-component', 'refreshComponent');
                $this->emit('alert', ['type' => 'success', 'message' => 'Remove product from cart successfully !!!']);
                return;
            }
        }
    }

    public function addToWishlist($product_id, $product_name, $product_price)
    {
        Cart::instance('wishlist')->add($product_id, $product_name, 1, $product_price)->associate('App\Models\Product');
        $this->emitTo('wishlist-count-component', 'refreshComponent');
        $this->emit('alert', ['type' => 'success', 'message' => 'Product move to wishlist successfully !!!']);
    }

    public function removeFromWishlist($product_id){
        foreach (Cart::instance('wishlist')->content() as $witem){
            if($witem->id == $product_id){
                Cart::instance('wishlist')->remove($witem->rowId);
                $this->emitTo('wishlist-count-component', 'refreshComponent');
                $this->emit('alert', ['type' => 'success', 'message' => 'Remove product from wishlist successfully !!!']);
                return;
            }
        }
    }

    use WithPagination;
    public function render()
    {
        if (empty($this->min_price && $this->max_price)) {
            $this->min_price = 1;
            $this->max_price = 2200;
        }
        if($this->sorting == 'date'){
            $products = Product::whereBetween('regular_price', [$this->min_price, $this->max_price])->where('name', 'like', '%'.$this->search.'%')->where('category_id', 'like', '%'.$this->product_cat_id.'%')->orderBy('created_at', 'DESC')->paginate($this->pagesize);
        }else if($this->sorting == "price"){
            $products = Product::whereBetween('regular_price', [$this->min_price, $this->max_price])->where('name', 'like', '%'.$this->search.'%')->where('category_id', 'like', '%'.$this->product_cat_id.'%')->orderBy('regular_price', 'ASC')->paginate($this->pagesize);
        }else if($this->sorting == "price-desc"){
            $products = Product::whereBetween('regular_price', [$this->min_price, $this->max_price])->where('name', 'like', '%'.$this->search.'%')->where('category_id', 'like', '%'.$this->product_cat_id.'%')->orderBy('regular_price', 'DESC')->paginate($this->pagesize);
        }else{
            $products = Product::whereBetween('regular_price', [$this->min_price, $this->max_price])->where('name', 'like', '%'.$this->search.'%')->where('category_id', 'like', '%'.$this->product_cat_id.'%')->paginate($this->pagesize);
        }

        $category = Category::orderBy('name', 'ASC')->get();
        $brands = Brand::orderBy('name', 'ASC')->get();
        $sale = Sale::find(1);
        $l_products = Product::orderBy('created_at', 'DESC')->get()->take(4);

        return view('livewire.search-component', ['products'=>$products, 'categories'=>$category, 'brands'=>$brands, 'l_products'=>$l_products, 'sale'=>$sale])->layout("layouts.base");
    }
}
