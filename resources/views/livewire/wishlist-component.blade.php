<main id="main" class="main-site left-sidebar">

    <div class="container">

        <div class="wrap-breadcrumb">
            <ul>
                <li class="item-link"><a href="#" class="link">home</a></li>
                <li class="item-link"><span>Wishlist</span></li>
            </ul>
        </div>

        <style>
            .product-wish {
                position: absolute;
                bottom: 13%;
                left: 0;
                z-index: 99;
                right: 30px;
                text-align: right;
                padding-top: 0;
            }

            .product-wish .fa {
                color: #cbcbcb;
                font-size: 32px;
            }

            .product-wish .fa:hover {
                color: #ff7007;
            }

            .fill-heart {
                color: #ff7007 !important;
            }

            .regprice {
                font-weight: 300;
                font-size: 13px !important;
                color: #aaaaaa !important;
                text-decoration: line-through;
                padding-left: 10px;
            }
        </style>

        <div class="row">
            @if(Cart::instance('wishlist')->content()->count() > 0)

                <ul class="product-list grid-products equal-container">
                    @foreach (Cart::instance('wishlist')->content() as $item)

                        <li class="col-lg-3 col-md-6 col-sm-6 col-xs-6 ">
                            <div class="product product-style-3 equal-elem ">
                                <div class="product-thumnail">
                                    <a href="{{route('product.details', ['slug'=>$item->model->slug])}}"
                                       title="{{$item->model->name}}">
                                        <figure><img src="{{asset('assets/images/products')}}/{{$item->model->image}}"
                                                     alt="{{$item->model->name}}"></figure>
                                    </a>
                                </div>
                                <div class="product-info">
                                    <a href="{{route('product.details', ['slug'=>$item->model->slug])}}" class="product-name"><span style="display:inline-block;
                                                                                                                                white-space: nowrap;
                                                                                                                                overflow: hidden;
                                                                                                                                text-overflow: ellipsis;
                                                                                                                                max-width: 34ch;" title="{{$item->model->name}}">{{$item->model->name}}</span></a>
                                    @if($item->model->sale_price > 0 && $sale->status == 1 && $sale->sale_date > \Carbon\Carbon::now())
                                        <div class="wrap-price"><ins><p class="product-price">${{$item->model->sale_price}}</p></ins><del><p class="product-price">${{$item->model->regular_price}}</p></del></div>
                                    @else
                                        <div class="wrap-price"><span
                                                class="product-price">${{$item->model->regular_price}}</span>
                                        </div>
                                    @endif
                                    @if($item->model->sale_price > 0 && $sale->status == 1 && $sale->sale_date > \Carbon\Carbon::now())
                                        <a href="#" class="btn add-to-cart"
                                           wire:click.prevent="moveProductFromWishListToCart('{{$item->rowId}}')">Move
                                            To Cart</a>
                                    @else
                                        <a href="#" class="btn add-to-cart"
                                           wire:click.prevent="moveProductFromWishListToCart('{{$item->rowId}}')">Move
                                            to Cart</a>
                                    @endif
                                    <div class="product-wish">
                                        <a href="#" wire:click.prevent="removeFromWishlist({{$item->model->id}})"><i
                                                class="fa fa-heart fill-heart"></i></a>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <h4>No Itme in wishlist</h4>
            @endif
        </div>

    </div>
</main>
