<?php

namespace App\Http\Livewire;

use App\Models\Coupon;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\productRcm;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Illuminate\Support\Str;
use Cart;

class CartComponent extends Component
{
    public $haveCouponCode;
    public $couponCode;
    public $discount;
    public $subtotalAfterDiscount;
    public $taxAfterDiscount;
    public $totalAfterDiscount;
    public $fake = null;
    public $fakeDiscount;
    public $qty;
    public $idProductRcm0;
    public $idProductRcm1;
    public $idProductRcm2;
    public $idProductRcm3;
    public $idProductRcm4;
    public $idProductRcm5;
    public $idProductRcm1_view;
    public $countProductRcm1_view;
    public $idProductRcm2_view;
    public $countProductRcm2_view;
    public $idProductRcm3_view;
    public $countProductRcm3_view;
    public $idProductRcm4_view;
    public $countProductRcm4_view;
    public $idProductRcm5_view;
    public $countProductRcm5_view;
    public $idProductRcm6_view;
    public $countProductRcm6_view;
    public $delay;

    public function mount(){
        //For Quantity Product Cart
        if($this->qty <= 1){
            $this->qty = 1;
        }
        $this->delay = 0;

        //For Id Commendation Product Database
        if(Cart::instance('cart')->count() > 0){
            $items = array();
            foreach(Cart::instance('cart')->content() as $i => $array){
                $items[] = $array->id;
            }
            $this->idProductRcm0 = $items[0];
            if(Cart::instance('cart')->count() > 1) {
                $this->idProductRcm1 = $items[1];
                if (Cart::instance('cart')->count() > 2) {
                    $this->idProductRcm2 = $items[2];
                    if (Cart::instance('cart')->count() > 3) {
                        $this->idProductRcm3 = $items[3];
                        if (Cart::instance('cart')->count() > 4) {
                            $this->idProductRcm4 = $items[4];
                            if (Cart::instance('cart')->count() > 5) {
                                $this->idProductRcm5 = $items[5];
                            }
                        }
                    }
                }
            }
        }
    }

    public function refreshPage(){
        if ($this->delay == 0){
            header("Refresh: $this->delay;");
            $this->delay = 10000000000000000000000;
        }
    }

    public function increaseQuantity($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);

        $qty = $product->qty + 1;

        Cart::update($rowId, $qty);

        $this->emitTo('cart-count-component', 'refreshComponent');
    }

    public function decreaseQuantity($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);

        if($product->qty > 1){
            $qty = $product->qty - 1;
            Cart::instance('cart')->update($rowId, $qty);
        }elseif($product->qty == null){
            $this->qty = 1;
        }

        $this->emitTo('cart-count-component', 'refreshComponent');
    }

    public function destroy($rowId)
    {
        Cart::instance('cart')->remove($rowId);
        $this->emitTo('cart-count-component', 'refreshComponent');
        session()->flash('success_message', 'Item has been removed');
        return redirect(route('product.cart'));
    }

    public function destroyAll()
    {
        Cart::instance('cart')->destroy();
        $this->emitTo('cart-count-component', 'refreshComponent');
        session()->flash('success_message', 'All Items has been removed');
        return;
    }

    public function switchToSaveForLater($rowId)
    {
        $item = Cart::instance('cart')->get($rowId);
        Cart::instance('cart')->remove($rowId);
        Cart::instance('saveForLater')->add($item->id, $item->name, 1, $item->price)->associate('App\Models\Product');
        $this->emitTo('cart-count-component', 'refreshComponent');
        session()->flash('success_message', 'Item has been saved for later !!!');
    }

    public function moveToCart($rowId)
    {
        $item = Cart::instance('saveForLater')->get($rowId);
        Cart::instance('saveForLater')->remove($rowId);
        Cart::instance('cart')->add($item->id, $item->name, 1, $item->price)->associate('App\Models\Product');
        $this->emitTo('cart-count-component', 'refreshComponent');
        session()->flash('s_success_message', 'Item has been move to cart !!!');
    }

    public function deleteFromSaveForLater($rowId)
    {
        Cart::instance('saveForLater')->remove($rowId);
        session()->flash('s_success_message', 'Item has been removed from save for later !!!');
    }

    public function applyCouponCode()
    {
        $coupon = Coupon::where('code', $this->couponCode)->where('expiry_date','>=',Carbon::today())->where('cart_value', '<=', Cart::instance('cart')->subtotal())->first();

        if (!$coupon) {
            session()->flash('coupon_message', 'Coupon code is invalid !!!');
            return;
        }

        session()->put('coupon', [
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => $coupon->value,
            'cart_value' => $coupon->cart_value
        ]);

        $this->fake = [
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => $coupon->value,
            'cart_value' => $coupon->cart_value
        ];

        $this->fakeDiscount = $coupon->value;

        if (session()->get('coupon')['type'] == 'fixed') {
            $this->discount = session()->get('coupon')['value'];
        } else {
            $this->discount = (Cart::instance('cart')->subtotal() * session()->get('coupon')['value']) / 100;
        }
        session()->put('discount', $this->discount);
        $this->subtotalAfterDiscount = Cart::instance('cart')->subtotal() - $this->discount;
        session()->put('subtotalAfterDiscount', $this->subtotalAfterDiscount);
        $this->taxAfterDiscount = ($this->subtotalAfterDiscount * config('cart.tax')) / 100;
        session()->put('taxAfterDiscount', $this->taxAfterDiscount);
        $this->totalAfterDiscount = $this->subtotalAfterDiscount + $this->taxAfterDiscount;
        session()->put('totalAfterDiscount', $this->totalAfterDiscount);
    }


    public function removeCoupon()
    {
        session()->forget('coupon');
    }

    public function addNewRecommendation(){
        $product_rcms = new productRcm();
        $product_rcms->idProductRcm0 = $this->idProductRcm0;
        $product_rcms->idProductRcm1 = $this->idProductRcm1;
        $product_rcms->idProductRcm2 = $this->idProductRcm2;
        $product_rcms->idProductRcm3 = $this->idProductRcm3;
        $product_rcms->idProductRcm4 = $this->idProductRcm4;
        $product_rcms->idProductRcm5 = $this->idProductRcm5;
        $product_rcms->save();
    }

    public function getRecommendationProduct(){
        $n1 = productRcm::where('idProductRcm0', $this->idProductRcm0)->count();
        $arrIdProductRcm1 = array($n1);
        $resIdProductRcm1 = array($n1);

        if(Cart::instance('cart')->count() > 0){
            $dataProductRcm1 = productRcm::where('idProductRcm0', $this->idProductRcm0)->get();

            foreach($dataProductRcm1 as $i => $arrayF){
                $arrIdProductRcm1[] = $arrayF->idProductRcm1;
            }

            for ($i = 1; $i <= $n1; $i++) {
                $resIdProductRcm1[$i] = -1;
            }

            // Count the number of times the product is selected
            for ($i = 1; $i <= $n1; $i++) {
                $count = 0;
                for ($j = $i + 1; $j < $n1; $j++){
                    if($resIdProductRcm1[$j] != 0 && $arrIdProductRcm1[] = $arrIdProductRcm1[$j]){
                        $count++;
                        $resIdProductRcm1[$j] = 0;
                    }
                }

                if ($resIdProductRcm1[$i] != 0){
                    $resIdProductRcm1[$i] = $count;
                    $this->countProductRcm1_view = $count;
                }
            }

            //Get most appear value
            $values = array_count_values($arrIdProductRcm1);
            arsort($values);
            $popular = array_slice(array_keys($values), 0, 1, true);
            $this->idProductRcm1_view = $popular[0];
        }

        //Recommendation Product 2
        $n2 = productRcm::where('idProductRcm0', $this->idProductRcm0)->
                            where('idProductRcm1', $this->idProductRcm1_view)->count();
        $arrIdProductRcm2 = array($n2);
        $resIdProductRcm2 = array($n2);


        if(!is_null($this->idProductRcm1_view)){
            $dataProductRcm2 = productRcm::where('idProductRcm0', $this->idProductRcm0)->
                                            where('idProductRcm1', $this->idProductRcm1_view)->get();

            foreach($dataProductRcm2 as $i => $arrayF){
                $arrIdProductRcm2[] = $arrayF->idProductRcm2;
            }

            for ($i = 1; $i <= $n2; $i++) {
                $resIdProductRcm2[$i] = -1;
            }

            // Count the number of times the product is selected
            for ($i = 1; $i <= $n2; $i++) {
                $count = 0;
                for ($j = $i + 1; $j < $n2; $j++){
                    if($resIdProductRcm2[$j] != 0 && $arrIdProductRcm2[] = $arrIdProductRcm2[$j]){
                        $count++;
                        $resIdProductRcm2[$j] = 0;
                    }
                }

                if ($resIdProductRcm2[$i] != 0){
                    $resIdProductRcm2[$i] = $count;
                    $this->countProductRcm2_view = $count;
                }
            }

            //Get most appear value
            $values2 = array_count_values($arrIdProductRcm2);
            arsort($values2);
            $popular2 = array_slice(array_keys($values2), 0, 1, true);
            $this->idProductRcm2_view = $popular2[0];
        }

        //Recommendation Product 3
        $n3 = productRcm::where('idProductRcm0', $this->idProductRcm0)
                            ->where('idProductRcm1', $this->idProductRcm1_view)
                            ->where('idProductRcm2', $this->idProductRcm2_view)->count();
        $arrIdProductRcm3 = array($n3);
        $resIdProductRcm3 = array($n3);


        if(!is_null($this->idProductRcm2_view)){
            $dataProductRcm3 = productRcm::where('idProductRcm0', $this->idProductRcm0)
                                            ->where('idProductRcm1', $this->idProductRcm1_view)
                                            ->where('idProductRcm2', $this->idProductRcm2_view)->get();

            foreach($dataProductRcm3 as $i => $arrayF){
                $arrIdProductRcm3[] = $arrayF->idProductRcm3;
            }

            for ($i = 1; $i <= $n3; $i++) {
                $resIdProductRcm3[$i] = -1;
            }

            // Count the number of times the product is selected
            for ($i = 1; $i <= $n3; $i++) {
                $count = 0;
                for ($j = $i + 1; $j < $n3; $j++){
                    if($resIdProductRcm3[$j] != 0 && $arrIdProductRcm3[] = $arrIdProductRcm3[$j]){
                        $count++;
                        $resIdProductRcm3[$j] = 0;
                    }
                }

                if ($resIdProductRcm3[$i] != 0){
                    $resIdProductRcm3[$i] = $count;
                    $this->countProductRcm3_view = $count;
                }
            }

            //Get most appear value
            $values3 = array_count_values($arrIdProductRcm3);
            arsort($values3);
            $popular3 = array_slice(array_keys($values3), 0, 1, true);
            $this->idProductRcm3_view = $popular3[0];
        }

        //Recommendation Product 4
        $n4 = productRcm::where('idProductRcm0', $this->idProductRcm0)
            ->where('idProductRcm1', $this->idProductRcm1_view)
            ->where('idProductRcm2', $this->idProductRcm2_view)
            ->where('idProductRcm3', $this->idProductRcm3_view)->count();
        $arrIdProductRcm4 = array($n4);
        $resIdProductRcm4 = array($n4);


        if(!is_null($this->idProductRcm3_view)){
            $dataProductRcm4 = productRcm::where('idProductRcm0', $this->idProductRcm0)
                ->where('idProductRcm1', $this->idProductRcm1_view)
                ->where('idProductRcm2', $this->idProductRcm2_view)
                ->where('idProductRcm3', $this->idProductRcm3_view)->get();

            foreach($dataProductRcm4 as $i => $arrayF){
                $arrIdProductRcm4[] = $arrayF->idProductRcm4;
            }

            for ($i = 1; $i <= $n4; $i++) {
                $resIdProductRcm4[$i] = -1;
            }

            // Count the number of times the product is selected
            for ($i = 1; $i <= $n4; $i++) {
                $count = 0;
                for ($j = $i + 1; $j < $n4; $j++){
                    if($resIdProductRcm4[$j] != 0 && $arrIdProductRcm4[] = $arrIdProductRcm4[$j]){
                        $count++;
                        $resIdProductRcm4[$j] = 0;
                    }
                }

                if ($resIdProductRcm4[$i] != 0){
                    $resIdProductRcm4[$i] = $count;
                    $this->countProductRcm4_view = $count;
                }
            }

            //Get most appear value
            $values4 = array_count_values($arrIdProductRcm4);
            arsort($values4);
            $popular4 = array_slice(array_keys($values4), 0, 1, true);
            $this->idProductRcm4_view = $popular4[0];
        }

        //Recommendation Product 4
        $n5 = productRcm::where('idProductRcm0', $this->idProductRcm0)
            ->where('idProductRcm1', $this->idProductRcm1_view)
            ->where('idProductRcm2', $this->idProductRcm2_view)
            ->where('idProductRcm3', $this->idProductRcm3_view)
            ->where('idProductRcm4', $this->idProductRcm4_view)->count();
        $arrIdProductRcm5 = array($n5);
        $resIdProductRcm5 = array($n5);


        if(!is_null($this->idProductRcm3_view)){
            $dataProductRcm4 = productRcm::where('idProductRcm0', $this->idProductRcm0)
                ->where('idProductRcm1', $this->idProductRcm1_view)
                ->where('idProductRcm2', $this->idProductRcm2_view)
                ->where('idProductRcm3', $this->idProductRcm3_view)
                ->where('idProductRcm4', $this->idProductRcm4_view)->get();

            foreach($dataProductRcm4 as $i => $arrayF){
                $arrIdProductRcm5[] = $arrayF->idProductRcm5;
            }

            for ($i = 1; $i <= $n5; $i++) {
                $resIdProductRcm5[$i] = -1;
            }

            // Count the number of times the product is selected
            for ($i = 1; $i <= $n5; $i++) {
                $count = 0;
                for ($j = $i + 1; $j < $n5; $j++){
                    if($resIdProductRcm5[$j] != 0 && $arrIdProductRcm5[] = $arrIdProductRcm5[$j]){
                        $count++;
                        $resIdProductRcm5[$j] = 0;
                    }
                }

                if ($resIdProductRcm5[$i] != 0){
                    $resIdProductRcm5[$i] = $count;
                    $this->countProductRcm4_view = $count;
                }
            }

            //Get most appear value
            $values5 = array_count_values($arrIdProductRcm5);
            arsort($values5);
            $popular5 = array_slice(array_keys($values5), 0, 1, true);
            $this->idProductRcm5_view = $popular5[0];
        }
    }



    public function getValueOfRecommandationProduct(){
        //Recommendation
        $checkIdProductRcm = productRcm::where('idProductRcm0', $this->idProductRcm0)->first();
        $countProduct = Product::count();
        if(is_null($checkIdProductRcm)){
            //For Commendation Product View
            $this->idProductRcm1_view = rand(1, $countProduct);
            $this->idProductRcm2_view = rand(1, $countProduct);
            if($this->idProductRcm2_view == $this->idProductRcm1_view){
                $this->idProductRcm2_view++;
            }
            $this->idProductRcm3_view = rand(1, $countProduct);
            if($this->idProductRcm3_view == $this->idProductRcm1_view &&
                $this->idProductRcm3_view ==  $this->idProductRcm2_view){
                $this->idProductRcm3_view++;
            }
            $this->idProductRcm4_view = rand(1, $countProduct);
            if($this->idProductRcm4_view == $this->idProductRcm1_view &&
                $this->idProductRcm4_view ==  $this->idProductRcm2_view &&
                $this->idProductRcm4_view ==  $this->idProductRcm3_view){
                $this->idProductRcm4_view++;
            }
            $this->idProductRcm5_view = rand(1, $countProduct);
            if($this->idProductRcm5_view == $this->idProductRcm1_view &&
                $this->idProductRcm5_view ==  $this->idProductRcm2_view &&
                $this->idProductRcm5_view ==  $this->idProductRcm3_view &&
                $this->idProductRcm5_view ==  $this->idProductRcm4_view){
                $this->idProductRcm5_view++;
            }
            $this->idProductRcm6_view = rand(1, $countProduct);
            if($this->idProductRcm6_view == $this->idProductRcm1_view &&
                $this->idProductRcm6_view ==  $this->idProductRcm2_view &&
                $this->idProductRcm6_view ==  $this->idProductRcm3_view &&
                $this->idProductRcm6_view ==  $this->idProductRcm4_view &&
                $this->idProductRcm6_view ==  $this->idProductRcm5_view){
                $this->idProductRcm6_view++;
            }
        }else{
            $this->getRecommendationProduct();
        }
    }

    public function checkNullValueRecommendationProduct(){
        $countProductR = Product::count();
        if (is_null($this->idProductRcm1_view)){
            $this->idProductRcm1_view = rand(1, $countProductR);
        }
        if (is_null($this->idProductRcm2_view)){
            $this->idProductRcm2_view = rand(1, $countProductR);
            if($this->idProductRcm2_view == $this->idProductRcm1){
                $this->idProductRcm2_view++;
            }
        }
        if (is_null($this->idProductRcm3_view)){
            $this->idProductRcm3_view = rand(1, $countProductR);
            if($this->idProductRcm3_view == $this->idProductRcm1 &&
                $this->idProductRcm3_view == $this->idProductRcm2){
                $this->idProductRcm3_view++;
            }
        }
        if (is_null($this->idProductRcm4_view)){
            $this->idProductRcm4_view = rand(1, $countProductR);
            if($this->idProductRcm4_view == $this->idProductRcm1 &&
                $this->idProductRcm4_view == $this->idProductRcm2 &&
                $this->idProductRcm4_view == $this->idProductRcm3){
                $this->idProductRcm4_view++;
            }
        }
        if (is_null($this->idProductRcm5_view)){
            $this->idProductRcm5_view = rand(1, $countProductR);
            if($this->idProductRcm5_view == $this->idProductRcm1 &&
                $this->idProductRcm5_view == $this->idProductRcm2 &&
                $this->idProductRcm5_view == $this->idProductRcm3 &&
                $this->idProductRcm5_view == $this->idProductRcm4){
                $this->idProductRcm5_view++;
            }
        }
        if (is_null($this->idProductRcm6_view)){
            $this->idProductRcm6_view = rand(1, $countProductR);
            if($this->idProductRcm6_view == $this->idProductRcm1 &&
                $this->idProductRcm6_view == $this->idProductRcm2 &&
                $this->idProductRcm6_view == $this->idProductRcm3 &&
                $this->idProductRcm6_view == $this->idProductRcm4 &&
                $this->idProductRcm6_view == $this->idProductRcm5){
                $this->idProductRcm6_view++;
            }
        }
    }

    public function checkout(){
        if(Auth::check()){
            return redirect()->route('checkout');
            $this->addNewRecommendation();
        }else{
            return redirect()->route('login');
        }
    }

    public function setAmountForCheckout(){
        if(!Cart::instance('cart')->count() > 0){
            session()->forget('checkout');
            return;
        }
        if(session()->has('coupon')){
            session()->put('checkout', [
                'discount' => $this->discount,
                'subtotal' => $this->subtotalAfterDiscount,
                'tax' => $this->taxAfterDiscount,
                'total' => $this->totalAfterDiscount
            ]);
        }else{
            session()->put('checkout', [
                'discount' => 0,
                'subtotal' => Cart::instance('cart')->subtotal(),
                'tax' => Cart::instance('cart')->tax(),
                'total' => Cart::instance('cart')->total()
            ]);
        }
    }

    public function render()
    {
        $countProduct = Product::count();
        //Check Coupon
        if (session()->has('coupon')) {
            $this->fake = Session::get('coupon');
        }

        //Get value and check null value Recommendation Product
        $this->getValueOfRecommandationProduct();
        $this->checkNullValueRecommendationProduct();

        $productRcm_1 = Product::find($this->idProductRcm1_view);
        if(is_null($productRcm_1)){
            $productRcm_1 = Product::find(rand(1,$countProduct));
        }
        $productRcm_2 = Product::find($this->idProductRcm2_view);
        if(is_null($productRcm_2)){
            $productRcm_2 = Product::find(rand(1,$countProduct));
        }
        $productRcm_3 = Product::find($this->idProductRcm3_view);
        if(is_null($productRcm_3)){
            $productRcm_3 = Product::find(rand(1,$countProduct));
        }
        $productRcm_4 = Product::find($this->idProductRcm4_view);
        if(is_null($productRcm_4)){
            $productRcm_4 = Product::find(rand(1,$countProduct));
        }
        $productRcm_5 = Product::find($this->idProductRcm5_view);
        if(is_null($productRcm_5)){
            $productRcm_5 = Product::find(rand(1,$countProduct));
        }
        $productRcm_6 = Product::find($this->idProductRcm6_view);
        if(is_null($productRcm_6)){
            $productRcm_6 = Product::find(rand(1,$countProduct));
        }

        //Test Product
//        echo "<pre>";
//        echo  "Product 1 --------------------------------------------------------------";
//        print_r($productRcm_1);
//        echo  "Product 2 --------------------------------------------------------------";
//        print_r($productRcm_2);
//        echo  "Product 3 --------------------------------------------------------------";
//        print_r($productRcm_3);
//        echo  "Product 4 --------------------------------------------------------------";
//        print_r($productRcm_4);
//        echo  "Product 5 --------------------------------------------------------------";
//        print_r($productRcm_5);
//        echo  "Product 6 --------------------------------------------------------------";
//        print_r($productRcm_6);
//        echo "</pre>";

        //Get value Cart to Checkout
        $this->discount = session()->get('discount');
        $this->subtotalAfterDiscount = session()->get('subtotalAfterDiscount');
        $this->taxAfterDiscount = session()->get('taxAfterDiscount');
        $this->totalAfterDiscount = session()->get('totalAfterDiscount');

        //Same the name function
        $this->setAmountForCheckout();

        //Send mail to confirm
        if(Auth::check()){
            Cart::instance('cart')->store(Auth::user()->email);
        }

        //For most view product recommendation
        $mostViewProducts = Product::orderBy('view', 'DESC')->take(6)->get();
        $sale = Sale::find(1);
        if(Cart::instance('cart')->count() > 0) {
            $checkRcmProduct = productRcm::where('idProductRcm0', $this->idProductRcm0)->get();
        }

        return view('livewire.cart-component', [
            'mostViewProducts'=>$mostViewProducts,
            'sale'=>$sale,
            'productRcm_1'=>$productRcm_1,
            'productRcm_2'=>$productRcm_2,
            'productRcm_3'=>$productRcm_3,
            'productRcm_4'=>$productRcm_4,
            'productRcm_5'=>$productRcm_5,
            'productRcm_6'=>$productRcm_6,
            ])->layout("layouts.base");
    }
}
