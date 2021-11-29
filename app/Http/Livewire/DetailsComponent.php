<?php

namespace App\Http\Livewire;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\Sale;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Cart;


class DetailsComponent extends Component
{
    public $slug;
    public $product_id;
    public $qty;
    public $view;

    public function mount($slug)
    {
        $product = Product::where('slug', $slug)->first();
        $this->product_id = $product->id;
        $this->slug = $slug;
        $this->qty = 1;
        $this->view = $product->view;
    }

    public function updateView(){
        $product = Product::find($this->product_id);
        $viewPlus1 = ($product->view) + 1;
        $product->view = $viewPlus1;
        $product->save();
    }

    public function store($product_id, $product_name, $product_price)
    {
        Cart::instance('cart')->add($product_id, $product_name, 1, $product_price)->associate('App\Models\Product');
        session()->flash('success_message', 'Item added in Cart');
        $this->emitTo('cart-count-component', 'refreshComponent');
        return;
    }

    public function destroy($product_id)
    {
        foreach (Cart::instance('cart')->content() as $citem){
            if($citem->id == $product_id){
                Cart::instance('cart')->remove($citem->rowId);
                $this->emitTo('cart-count-component', 'refreshComponent');
                return;
            }
        }
    }

    public function addToWishlist($product_id, $product_name, $product_price)
    {
        Cart::instance('wishlist')->add($product_id, $product_name, 1, $product_price)->associate('App\Models\Product');
        $this->emitTo('wishlist-count-component', 'refreshComponent');
    }

    public function removeFromWishlist($product_id){
        foreach (Cart::instance('wishlist')->content() as $witem){
            if($witem->id == $product_id){
                Cart::instance('wishlist')->remove($witem->rowId);
                $this->emitTo('wishlist-count-component', 'refreshComponent');
                return;
            }
        }
    }

    public function increaseQuantity(){
        $this->qty++;
    }

    public function decreseQuantity(){
        if($this->qty > 1){
            $this->qty--;
        }
    }

    public function render()
    {
        $product = Product::where('slug', $this->slug)->first();
        $popular_products = Product::inRandomOrder()->limit(4)->get();
        $related_products = Product::where('category_id', $product->category_id)->inRandomOrder()->limit(4)->get();
        $sale = Sale::find(1);
        $orderItemByProduct = OrderItem::where('product_id', $product->id)->where('rstatus', 1)->get();
        $this->updateView();

        if(Auth::check()){
            Cart::instance('cart')->restore(Auth::user()->email);
            Cart::instance('wishlist')->store(Auth::user()->email);
        }

        return view('livewire.details-component', [
            'product' => $product,
            'popular_products' => $popular_products,
            'related_products' => $related_products,
            'sale'=>$sale,
            'orderItemByProduct' => $orderItemByProduct
        ])->layout('layouts.base');
    }
}
