<main id="main" class="main-site">

    <div class="container">

        <div class="wrap-breadcrumb">
            <ul>
                <li class="item-link"><a href="{{route('home')}}" class="link">home</a></li>
                <li class="item-link"><span>Cart</span></li>
            </ul>
        </div>
        <div class=" main-content-area">
            @if(Cart::instance('cart')->count() > 0)
                <div class="wrap-iten-in-cart">
                    @if(Session::has('success_message'))
                        <div class="alert alert-success">
                            <strong>Success</strong> {{Session::get('success_message')}}
                        </div>
                    @endif
                    @if(Cart::instance('cart')->count() > 0)
                        {{--                        <h3 class="box-title">Products Name</h3>--}}

                        <ul class="products-cart">
                            <li class="pr-cart-item">
                                <div class="product-image">
                                    <b>Product Image</b>
                                </div>
                                <div class="product-name">
                                    <b>Product Name</b>
                                </div>
                                <div class="price-field product-price">
                                    <p class="price">Product Price</p>
                                </div>
                                <div class="quantity">
                                    <center><b>Product quantity</b></center>
                                </div>
                                <div class="price-field sub-total"><p class="price">Total</p></div>
                                <div class="delete">
                                    <b>Action</b>
                                </div>
                            </li>
                        </ul>

                        <ul class="products-cart">
                            @foreach(Cart::instance('cart')->content() as $item)
                                <li class="pr-cart-item">
                                    <div class="product-image">
                                        <figure><img src="{{asset('assets/images/products')}}/{{$item->model->image}}"
                                                     alt="{{$item->model->name}}">
                                        </figure>
                                    </div>
                                    <div class="product-name">
                                        <a class="link-to-product"
                                           href="{{route('product.details', ['slug'=>$item->model->slug])}}">{{$item->model->name}}</a>
                                    </div>
                                    <div class="price-field product-price"><p class="price">
                                            ${{$item->model->regular_price}}</p></div>
                                    <div class="quantity">
                                        <div class="quantity-input">
                                            <input type="text" name="product-quatity" value="{{$item->qty}}"
                                                   data-max="120"
                                                   pattern="[0-9]*">
                                            <a class="btn btn-increase" href="#"
                                               wire:click.prevent="increaseQuantity('{{$item->rowId}}')"></a>
                                            <a class="btn btn-reduce" href="#"
                                               wire:click.prevent="decreaseQuantity('{{$item->rowId}}')"></a>
                                        </div>
                                        <p class="text-center"><a href="#"
                                                                  wire:click.prevent="switchToSaveForLater('{{$item->rowId}}')">Save
                                                For Later</a></p>
                                    </div>
                                    <div class="price-field sub-total"><p class="price">{{$item->subtotal}}</p></div>
                                    <div class="delete">
                                        <a href="#" wire:click.prevent="destroy('{{$item->rowId}}')"
                                           class="btn btn-delete"
                                           title="">
                                            <span>Delete from your cart</span>
                                            <i class="fa fa-times-circle" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>No item in shopping cart</p>
                    @endif
                </div>

                <div class="summary">
                    <div class="order-summary">
                        <h4 class="title-box">Order Summary</h4>
                        <p class="summary-info"><span class="title">Subtotal</span><b
                                class="index">${{Cart::instance('cart')->subtotal()}}</b></p>
                        {{--                    @php--}}
                        {{--                        $temp = session()--}}
                        {{--                    @endphp--}}
                        @if(Session::has('coupon'))
                            <p class="summary-info"><span class="title">Discount ({{Session::get('coupon')['code']}}) <a
                                        href="#" wire:click.prevent="removeCoupon"><i
                                            class="fa fa-times text-danger"></i></a></span><b
                                    class="index">-${{number_format($discount, 2)}}</b></p>
                            <p class="summary-info"><span class="title">Subtotal with Discount</span><b
                                    class="index">${{number_format($subtotalAfterDiscount, 2)}}</b></p>
                            <p class="summary-info"><span class="title">Tax ({{config('cart.tag')}}2%)<a target="_blank"
                                                                                                         href="https://www.expatica.com/uk/finance/taxes/a-complete-guide-to-the-uk-tax-system-758254/#:~:text=Tax%20is%20charged%20on%20total,on%20incomes%20over%20%C2%A3125%2C000./"><i
                                            class="fa fa-file" aria-hidden="true"></i></a></span><b
                                    class="index">${{number_format($taxAfterDiscount, 2)}}</b></p>
                            <p class="summary-info"><span class="title">Total</span><b
                                    class="index">${{number_format($totalAfterDiscount, 2)}}</b></p>
                        @else
                            <p class="summary-info"><span class="title">Tax (2%)<a target="_blank"
                                                                                   href="https://www.expatica.com/uk/finance/taxes/a-complete-guide-to-the-uk-tax-system-758254/#:~:text=Tax%20is%20charged%20on%20total,on%20incomes%20over%20%C2%A3125%2C000./"><i
                                            class="fa fa-file" aria-hidden="true"></i></a></span><b
                                    class="index">${{Cart::instance('cart')->tax()}}</b></p>
                            <p class="summary-info"><span class="title">Shipping</span><b class="index">Free
                                    Shipping</b>
                            </p>
                            <p class="summary-info total-info "><span class="title">Total</span><b
                                    class="index">${{Cart::instance('cart')->total()}}</b></p>
                        @endif
                    <!--test state fake-->
                        {{--                    @if(!is_null($fake))--}}
                        {{--                        <h1>Được rồi</h1>--}}
                        {{--                    @else--}}
                        {{--                        <h1>Chưa được</h1>--}}
                        {{--                    @endif--}}
                    </div>
                    <div class="checkout-info">
                        @if(!Session::has('coupon'))
                            <label class="checkbox-field">
                                <input class="frm-input " name="have-code" id="have-code" value="1" type="checkbox"
                                       wire:model="haveCouponCode"><span>I have coupon code</span>
                            </label>
                            @if($haveCouponCode == 1)
                                <div class="summary-item">
                                    <form wire:submit.prevent="applyCouponCode">
                                        <h4 class="title-box">Coupon Code</h4>
                                        @if(Session::has('coupon_message'))
                                            <div class="alert alert-danger"
                                                 role="alert">{{Session::get('coupon_message')}}</div>
                                        @endif
                                        <p class="row-in-form">
                                            <label class="coupon-code">Enter your Coupon Code</label>
                                            <input type="text" name="coupon-code" wire:model="couponCode">
                                        </p>
                                        <button type="submit" class="btn btn-small">Apply</button>
                                    </form>
                                </div>
                            @endif
                        @endif

                        <a class="btn btn-checkout" href="#" wire:click.prevent="checkout">Check out</a>
                        <a class="link-to-shop" href="{{route('shop')}}">Continue Shopping<i
                                class="fa fa-arrow-circle-right"
                                aria-hidden="true"></i></a>
                    </div>
                    <div class="update-clear">
                        <a class="btn btn-clear" href="#" wire:click.prevent="destroyAll">Clear Shopping Cart</a>
                        <a class="btn btn-update" href="#">Update Shopping Cart</a>
                    </div>
                </div>
            @else
                <div class="text-center" style="padding: 30px 0;">
                    <h1>Your cart is empty</h1>
                    <h4>Add items to it now</h4>
                    <a href="{{route('shop')}}" class="btn btn-success">Shop</a>
                </div>
            @endif

            <div class="wrap-iten-in-cart">
                <h3 class="title-box"
                    style="border-bottom: 1px solid; padding-bottom: 15px;">{{Cart::instance('saveForLater')->count()}}
                    item(s) Save For Later</h3>
                @if(Session::has('s_success_message'))
                    <div class="alert alert-success">
                        <strong>Success</strong> {{Session::get('s_success_message')}}
                    </div>
                @endif
                @if(Cart::instance('saveForLater')->count() > 0)
                    <h3 class="box-title">Products Name</h3>
                    <ul class="products-cart">
                        @foreach(Cart::instance('saveForLater')->content() as $item)
                            <li class="pr-cart-item">
                                <div class="product-image">
                                    <figure><img src="{{asset('assets/images/products')}}/{{$item->model->image}}"
                                                 alt="{{$item->model->name}}">
                                    </figure>
                                </div>
                                <div class="product-name">
                                    <a class="link-to-product"
                                       href="{{route('product.details', ['slug'=>$item->model->slug])}}">{{$item->model->name}}</a>
                                </div>
                                <div class="price-field produtc-price"><p class="price">
                                        ${{$item->model->regular_price}}</p></div>
                                <div class="quantity">

                                    <p class="text-center"><a href="#"
                                                              wire:click.prevent="moveToCart('{{$item->rowId}}')">Move
                                            To Cart</a></p>
                                </div>
                                <div class="delete">
                                    <a href="#" wire:click.prevent="deleteFromSaveForLater('{{$item->rowId}}')"
                                       class="btn btn-delete" title="">
                                        <span>Delete from your save for later</span>
                                        <i class="fa fa-times-circle" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p>No item saved</p>
                @endif
            </div>

                <!-- Recommendation Product Table-->
                <div class="wrap-show-advance-info-box style-1 box-in-site" wire:ignore>
                    <h3 class="title-box">Recommendation Products</h3>
                    <div class="wrap-products">
                        <div class="products slide-carousel owl-carousel style-nav-1 equal-container" data-items="5"
                             data-loop="false" data-nav="true" data-dots="false"
                             data-responsive='{"0":{"items":"1"},"480":{"items":"2"},"768":{"items":"3"},"992":{"items":"3"},"1200":{"items":"5"}}'>

                            <!-- Recommendation Product 1-->
                            <div class="product product-style-2 equal-elem ">
                                <div class="product-thumnail">
                                    <a href="#" title="T-Shirt Raw Hem Organic Boro Constrast Denim">
                                        <figure><img src="{{asset('assets/images/products')}}/{{$productRcm_1->image}}"
                                                     width="214"
                                                     height="214" alt="{{$productRcm_1->name}}">
                                        </figure>
                                    </a>
                                    <div class="group-flash">
                                        <span class="flash-item new-label"
                                              style="width: 95px !important;">Maybe you need</span>
                                    </div>
                                    <div class="wrap-btn">
                                        <a href="#" class="function-link">quick view</a>
                                    </div>
                                </div>
                                <div class="product-info">
                                    <a href="#" class="product-name"><span>{{$productRcm_1->name}}</span></a>
                                    @if($sale->status == 1 && $sale->sale_date > \Carbon\Carbon::now() && $productRcm_1->sale_price > 0)
                                        <div class="wrap-price">
                                            <ins><p class="product-price">${{$productRcm_1->sale_price}}</p></ins>
                                            <del><p class="product-price">${{$productRcm_1->regular_price}}</p></del>
                                        </div>
                                    @else
                                        <div class="wrap-price">
                                            <span class="product-price">${{$productRcm_1->regular_price}}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Recommendation Product 2-->
                            <div class="product product-style-2 equal-elem ">
                                <div class="product-thumnail">
                                    <a href="#" title="T-Shirt Raw Hem Organic Boro Constrast Denim">
                                        <figure><img src="{{asset('assets/images/products')}}/{{$productRcm_2->image}}"
                                                     width="214"
                                                     height="214" alt="{{$productRcm_2->name}}">
                                        </figure>
                                    </a>
                                    <div class="group-flash">
                                        <span class="flash-item new-label"
                                              style="width: 100px !important;">Maybe you need</span>
                                    </div>
                                    <div class="wrap-btn">
                                        <a href="#" class="function-link">quick view</a>
                                    </div>
                                </div>
                                <div class="product-info">
                                    <a href="#" class="product-name"><span>{{$productRcm_2->name}}</span></a>
                                    @if($sale->status == 1 && $sale->sale_date > \Carbon\Carbon::now() && $productRcm_2->sale_price > 0)
                                        <div class="wrap-price">
                                            <ins><p class="product-price">${{$productRcm_2->sale_price}}</p></ins>
                                            <del><p class="product-price">${{$productRcm_2->regular_price}}</p></del>
                                        </div>
                                    @else
                                        <div class="wrap-price">
                                            <span class="product-price">${{$productRcm_2->regular_price}}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Recommendation Product 3-->
                            <div class="product product-style-2 equal-elem ">
                                <div class="product-thumnail">
                                    <a href="#" title="T-Shirt Raw Hem Organic Boro Constrast Denim">
                                        <figure><img src="{{asset('assets/images/products')}}/{{$productRcm_3->image}}"
                                                     width="214"
                                                     height="214" alt="{{$productRcm_3->name}}">
                                        </figure>
                                    </a>
                                    <div class="group-flash">
                                        <span class="flash-item new-label"
                                              style="width: 95px !important;">Maybe you need</span>
                                    </div>
                                    <div class="wrap-btn">
                                        <a href="#" class="function-link">quick view</a>
                                    </div>
                                </div>
                                <div class="product-info">
                                    <a href="#" class="product-name"><span>{{$productRcm_3->name}}</span></a>
                                    @if($sale->status == 1 && $sale->sale_date > \Carbon\Carbon::now() && $productRcm_3->sale_price > 0)
                                        <div class="wrap-price">
                                            <ins><p class="product-price">${{$productRcm_3->sale_price}}</p></ins>
                                            <del><p class="product-price">${{$productRcm_3->regular_price}}</p></del>
                                        </div>
                                    @else
                                        <div class="wrap-price">
                                            <span class="product-price">${{$productRcm_3->regular_price}}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Recommendation Product 4-->
                            <div class="product product-style-2 equal-elem ">
                                <div class="product-thumnail">
                                    <a href="#" title="T-Shirt Raw Hem Organic Boro Constrast Denim">
                                        <figure><img src="{{asset('assets/images/products')}}/{{$productRcm_4->image}}"
                                                     width="214"
                                                     height="214" alt="{{$productRcm_4->name}}">
                                        </figure>
                                    </a>
                                    <div class="group-flash">
                                        <span class="flash-item new-label"
                                              style="width: 95px !important;">Maybe you need</span>
                                    </div>
                                    <div class="wrap-btn">
                                        <a href="#" class="function-link">quick view</a>
                                    </div>
                                </div>
                                <div class="product-info">
                                    <a href="#" class="product-name"><span>{{$productRcm_4->name}}</span></a>
                                    @if($sale->status == 1 && $sale->sale_date > \Carbon\Carbon::now() && $productRcm_4->sale_price > 0)
                                        <div class="wrap-price">
                                            <ins><p class="product-price">${{$productRcm_4->sale_price}}</p></ins>
                                            <del><p class="product-price">${{$productRcm_4->regular_price}}</p></del>
                                        </div>
                                    @else
                                        <div class="wrap-price">
                                            <span class="product-price">${{$productRcm_4->regular_price}}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Recommendation Product 5-->
                            <div class="product product-style-2 equal-elem ">
                                <div class="product-thumnail">
                                    <a href="#" title="T-Shirt Raw Hem Organic Boro Constrast Denim">
                                        <figure><img src="{{asset('assets/images/products')}}/{{$productRcm_5->image}}"
                                                     width="214"
                                                     height="214" alt="{{$productRcm_5->name}}">
                                        </figure>
                                    </a>
                                    <div class="group-flash">
                                        <span class="flash-item new-label"
                                              style="width: 95px !important;">Maybe you need</span>
                                    </div>
                                    <div class="wrap-btn">
                                        <a href="#" class="function-link">quick view</a>
                                    </div>
                                </div>
                                <div class="product-info">
                                    <a href="#" class="product-name"><span>{{$productRcm_5->name}}</span></a>
                                    @if($sale->status == 1 && $sale->sale_date > \Carbon\Carbon::now() && $productRcm_5->sale_price > 0)
                                        <div class="wrap-price">
                                            <ins><p class="product-price">${{$productRcm_5->sale_price}}</p></ins>
                                            <del><p class="product-price">${{$productRcm_5->regular_price}}</p></del>
                                        </div>
                                    @else
                                        <div class="wrap-price">
                                            <span class="product-price">${{$productRcm_5->regular_price}}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Recommendation Product 6-->
                            <div class="product product-style-2 equal-elem ">
                                <div class="product-thumnail">
                                    <a href="#" title="T-Shirt Raw Hem Organic Boro Constrast Denim">
                                        <figure><img src="{{asset('assets/images/products')}}/{{$productRcm_6->image}}"
                                                     width="214"
                                                     height="214" alt="{{$productRcm_6->name}}">
                                        </figure>
                                    </a>
                                    <div class="group-flash">
                                        <span class="flash-item new-label"
                                              style="width: 95px !important;">Maybe you need</span>
                                    </div>
                                    <div class="wrap-btn">
                                        <a href="#" class="function-link">quick view</a>
                                    </div>
                                </div>
                                <div class="product-info">
                                    <a href="#" class="product-name"><span>{{$productRcm_6->name}}</span></a>
                                    @if($sale->status == 1 && $sale->sale_date > \Carbon\Carbon::now() && $productRcm_6->sale_price > 0)
                                        <div class="wrap-price">
                                            <ins><p class="product-price">${{$productRcm_6->sale_price}}</p></ins>
                                            <del><p class="product-price">${{$productRcm_6->regular_price}}</p></del>
                                        </div>
                                    @else
                                        <div class="wrap-price">
                                            <span class="product-price">${{$productRcm_6->regular_price}}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div><!--End wrap-products-->
                </div>

<!--                Most View Product-->
            <div class="wrap-show-advance-info-box style-1 box-in-site" wire:ignore>
                <h3 class="title-box">Most Viewed Products</h3>
                <div class="wrap-products">
                    <div class="products slide-carousel owl-carousel style-nav-1 equal-container" data-items="5"
                         data-loop="false" data-nav="true" data-dots="false"
                         data-responsive='{"0":{"items":"1"},"480":{"items":"2"},"768":{"items":"3"},"992":{"items":"3"},"1200":{"items":"5"}}'>

                        @foreach($mostViewProducts as $mv_Product)
                            <div class="product product-style-2 equal-elem ">
                                <div class="product-thumnail">
                                    <a href="#" title="T-Shirt Raw Hem Organic Boro Constrast Denim">
                                        <figure><img src="{{asset('assets/images/products')}}/{{$mv_Product->image}}"
                                                     width="214"
                                                     height="214" alt="{{$mv_Product->name}}">
                                        </figure>
                                    </a>
                                    <div class="group-flash">
                                        <span class="flash-item new-label"
                                              style="width: 47px !important;">Most view</span>
                                    </div>
                                    <div class="wrap-btn">
                                        <a href="#" class="function-link">quick view</a>
                                    </div>
                                </div>
                                <div class="product-info">
                                    <a href="#" class="product-name"><span>{{$mv_Product->name}}</span></a>
                                    @if($mostViewProducts->count() > 0 && $sale->status == 1 && $sale->sale_date > \Carbon\Carbon::now() && $mv_Product->sale_price > 0)
                                        <div class="wrap-price">
                                            <ins><p class="product-price">${{$mv_Product->sale_price}}</p></ins>
                                            <del><p class="product-price">${{$mv_Product->regular_price}}</p></del>
                                        </div>
                                    @else
                                        <div class="wrap-price">
                                            <span class="product-price">${{$mv_Product->regular_price}}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach


                    </div>
                </div><!--End wrap-products-->
            </div>

        </div><!--end main content area-->
    </div><!--end container-->

</main>
