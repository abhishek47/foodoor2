@extends('layouts.restaurants')

@section('title',  $restaurant->name . ' | Home delivery | Order Food Online in Ranchi')

@section('meta-desc',  'Order Food Online from ' . $restaurant->name . ' ' .  $restaurant->area  .'\'s menu for Home Delivery in Ranchi. Fastest delivery | No minimum order | GPS tracking.')

@section('meta-keywords',  $restaurant->name . ' , menus, order food online ' . $restaurant->name .' ,' . $restaurant->area)


@section('content')


            <section class="inner-page-hero restaurant-info"  style="background: #171a29;">
               <div class="profile">
                  <div class="container">
                     <div class="row">
                        <div class="col-xs-12 col-sm-12  col-md-3 col-lg-4 profile-img hidden-sm-down">
                           <div class="image-wrap">
                              <figure><img src="{{ isset($restaurant->logo) ? url($restaurant->logo) : 'http://via.placeholder.com/350x250' }}" style="height: 160px;width: 100%;" alt="Profile Image"></figure>
                           </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 profile-desc">
                           <div class="pull-left right-text white-txt">
                              <h6><a href="#">{{ $restaurant->name }}</a></h6>
                              @if($restaurant->is_open)
                              <a class="btn btn-small btn-green">Open</a>
                              @else
                               <a class="btn btn-small" style="background: red;">Closed</a>
                              @endif
                              <p> @foreach($restaurant->cuisines as $cuisine)
                                             {{ $cuisine->name }},
                                           @endforeach</p>
                              <ul class="nav nav-inline">
                                 <li class="nav-item"> <a class="nav-link active" href="#"><i class="fa fa-check"></i> Min &#8377 {{ $restaurant->min_price }}</a> </li>
                                 <li class="nav-item"> <a class="nav-link" href="#"><i class="fa fa-motorcycle"></i> {{ $restaurant->delivery_time }}</a> </li>
                                 <li class="nav-item ratings">
                                    <a class="nav-link" href="#"> <span>
                                   {!! getStars($restaurant->rating) !!}
                                    </span> </a>
                                 </li>
                              </ul>
                           </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-3">
                           @if($restaurant->promo_text != '' || $restaurant->promo_text != null)
                            @if(check_in_range($restaurant->valid_from, $restaurant->valid_through, date('Y-m-d')))
                              <div class="alert alert-success" role="alert" style="margin-top: 40px;">
                                 <b>OFFER</b> <br>
                                 {{ $restaurant->promo_text }}
                              </div>
                             @endif
                           @endif
                        </div>
                     </div>
                  </div>
               </div>
            </section>



            <div class="breadcrumb  hidden-sm-down" style="background: #fff;" >
               <div class="container">
                  <ul>
                     <li><a href="/" class="active">Home</a></li>
                     <li><a href="/restaurants/explore?lat={{request('lat')}}&lng={{request('lng')}}">Restaurants</a></li>
                     <li>{{ $restaurant->name }}</li>
                  </ul>
               </div>
            </div>



            <div class="container m-t-30 resp-container" style="min-height: 1200px;margin-bottom: 80px;">

              <a  style="display: block;
    margin-bottom: 25px;" href="/restaurants/explore?lat={{request('lat')}}&lng={{request('lng')}}"> <i class="fa fa-arrow-left"></i> All Restaurants</a>

               @if(!$restaurant->is_open)
                 <div class="alert alert-warning" role="alert" >
                     This restaurant is currently closed. Please try other  <a class="alert-link" href="/restaurants/explore?lat={{request('lat')}}&lng={{request('lng')}}">restaurants</a>
                  </div>
               @endif

               <div class="row">
                  <div class="col-xs-12 col-sm-4 col-md-4 col-lg-3 hidden-sm-down">
                     <div id="fixedSidebar" class="sidebar clearfix m-b-20 " >
                        <div class="main-block">
                           <div class="sidebar-title white-txt">
                              <h6>Choose Cuisine</h6>
                              <i class="fa fa-cutlery pull-right"></i>
                           </div>
                           <ul>

                              <li class="{{ request('filter') == 'all' ? 'active' : '' }}">
                                 <a href="/restaurants/{{ $restaurant->id }}?lat={{request('lat')}}&lng={{request('lng')}}&filter=all" class="scroll">All Items</a>
                              </li>

                              <li class="{{ request('filter') == 'featured' ? 'active' : '' }}">
                                 <a href="#restaurant-special" class="scroll">Restaurant Special</a>
                              </li>

                              @if(!$restaurant->is_veg)
                                 <li class="{{ request('filter') == 'veg' ? 'active' : '' }}">
                                    <a href="/restaurants/{{ $restaurant->id }}?lat={{request('lat')}}&lng={{request('lng')}}&filter=veg" class="scroll">Veg Items</a>
                                 </li>

                                 <li class="{{ request('filter') == 'nonveg' ? 'active' : '' }}">
                                    <a href="/restaurants/{{ $restaurant->id }}?lat={{request('lat')}}&lng={{request('lng')}}&filter=nonveg" class="scroll">Non-Veg Items</a>
                                 </li>
                              @endif

                              @foreach($cuisineMenu as $cuisine)
                                @if($cuisine->parent_id == 0 || $cuisine->parent_id == null)
                                 <li class="{{ request('cuisine') ==  $cuisine->id ? 'active' : '' }}">

                                    @if($cuisine->subs()->count())
                                    <a data-toggle="collapse" aria-expanded="false" href="#cuisinemenu-{{ $cuisine->id }}" class="scroll">{{ $cuisine->name }}  <i style="margin-top: 5px;" class="fa fa-angle-right pull-right"></i>
                                    <i style="margin-top: 5px;" class="fa fa-angle-down pull-right"></i></a>


                                     @else

                                      <a href="#cuisine-{{$cuisine->id}}" class="scroll">{{ $cuisine->name }} </a>
                                      @endif


                                 </li>
                                 <li>
                                    <div class="collapse" id="cuisinemenu-{{ $cuisine->id }}">
                                      <ul style="background-color: #fff;">
                                          @foreach($cuisine->subs as $subCuisine)
                                            @if($subCuisine->items()->where('restaurant_id', $restaurant->id)->count())
                                              <li style="text-align: right;font-size: 15px;" class="{{ request('cuisine') ==  $cuisine->id ? 'active' : '' }}">
                                               <a style="padding: 4px;" href="#cuisine-{{$subCuisine->id}}" class="scroll">{{ $subCuisine->name }}</a>
                                              </li>
                                            @endif
                                          @endforeach
                                      </ul>
                                     </div>
                                 </li>

                                  @endif

                              @endforeach

                           </ul>
                           <div class="clearfix"></div>
                        </div>

                     </div>

                  </div>
                  <div class="col-xs-12 col-sm-8 col-md-8 col-lg-6">

                      @if(request()->has('filter') && request('filter') == 'featured')
                          <div id="restaurant-special"></div>
                         <?php $itemChunks = $restaurant->items()->where('featured', 1)->get()->chunk(2); ?>
                        @foreach($itemChunks as $items)
                           <div class="row">
                                  @foreach($items as $index => $item)
                                    <div class="col-xs-6">
                                      @include('partials._specialItem')
                                    </div>
                                  @endforeach
                            </div>
                        @endforeach


                      @else

                        @if(request('cuisine') == null && (request('filter') == 'all' || request('filter') == ''))
                         <div id="restaurant-special"></div>
                        <?php $itemChunks = $restaurant->items()->where('featured', 1)->get()->chunk(2); ?>
                        @foreach($itemChunks as $items)
                          <div class="row">
                                  @foreach($items as $index => $item)
                                    <div class="col-xs-6">
                                      @include('partials._specialItem')
                                    </div>
                                  @endforeach
                            </div>
                          @endforeach
                         @endif


                        @foreach($cuisines as $cuisine)
                           @if(request('cuisine') == null || $cuisine->id == request('cuisine'))

                             @if(request()->has('filter') && request('filter') == 'veg')
                                <?php $items = $restaurant->items()->where('cuisine_id', $cuisine->id)->where('is_veg', 1)->get(); ?>
                             @elseif(request()->has('filter') && request('filter') == 'nonveg')
                                <?php $items = $restaurant->items()->where('cuisine_id', $cuisine->id)->where('is_veg', 0)->get(); ?>
                             @else
                                 <?php $items = $restaurant->items()->where('cuisine_id', $cuisine->id)->get(); ?>
                           @endif
                           <div id="cuisine-{{$cuisine->id}}"></div>
                           <div  class="menu-widget " style="background: #fff;margin-bottom: 8px;">
                              <div class="widget-heading">
                                 <h3 class="widget-title text-dark">
                                    {{ $cuisine->name }} <a class="btn btn-link pull-right" data-toggle="collapse" href="#cuisine-{{ $cuisine->id }}" aria-expanded="true">
                                    <i class="fa fa-angle-right pull-right"></i>
                                    <i class="fa fa-angle-down pull-right"></i>
                                    </a>
                                 </h3>
                                 <div class="clearfix"></div>
                              </div>
                              <div class="collapse in" id="cuisine-{{ $cuisine->id }}">


                              @foreach($items as $index => $item)
                                 <div class="food-item {{ ($index+1) % 2 == 0 ? 'white' : '' }}">
                                    <div class="row">
                                       <div class="col-xs-12 col-sm-12 col-lg-8">
                                        <div class="rest-logo pull-left">

                                             <a class="restaurant-logo pull-left" href="#">
                                                @if($item->is_veg)
                                                 <img src="/images/veg.png" style="width: 15px;height: 15px;margin-top: 3px;" >
                                                @else
                                                <img src="/images/nonveg.png" style="width: 15px;height: 15px;margin-top: 3px;" >
                                                @endif
                                             </a>
                                          </div>

                                          <div class="rest-descr" style="padding-left: 23px;">
                                             <h6 style="{{ 'margin-bottom: 8px;' }}"><a href="#">{{ $item->name }}</a></h6>

                                              <p class="hidden-sm-down" style="cursor: pointer;" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="{{ $item->description }}">{{ strlen($item->description) > 40 ? substr($item->description, 0, 40) . '...' : $item->description }}</p>

                                          </div>
                                          <!-- end:Description -->
                                       </div>
                                       <!-- end:col -->
                                       <div class="col-xs-12 col-sm-12 col-lg-4 pull-right item-cart-info"> <span class="price pull-left">&#8377; {{ $item->price }}</span>

                                        @if($item->is_available)
                                       <?php $added = Cart::instance('restaurant-'.$restaurant->id)->search(function ($cartItem, $rowId) use ($item)  {
                                          return $cartItem->id === $item->id ;
                                          }); ?>

                                          @if(count($added) && (count($item->additions) == 0 && ($item->sizes == null || count(json_decode($item->sizes)) == 0)))
                                            <div data-trigger="spinner" id="spinner2-{{$added->first()->rowId}}" style="display: inline;text-align: center;float: right;margin-right: 6px;" >
                                                 <a style="color: #f30; font-size: 18px;font-weight: bold;" href="javascript:;" data-spin="down">-</a>
                                                 <input type="text" style="width: 40px;text-align: center;" min="1" value="{{ $added->first()->qty }}" data-rule="quantity">
                                                 <a href="" style="color: #f30; font-size: 18px;font-weight: bold;" href="javascript:;" data-spin="up">+</a>
                                             </div>
                                          @else
                                             @if(count($item->additions) || ($item->sizes != null && count(json_decode($item->sizes))))
                                                <a href="#toppings-{{$item->id}}" data-toggle="modal" class="btn btn-small btn btn-secondary pull-right">+</a>
                                             @else
                                                <a href="/cart/add/{{$item->id}}" class="btn btn-small btn btn-secondary pull-right">+</a>
                                             @endif
                                           @endif

                                           @else

                                              <a href="javascript:;" class="btn btn-small btn btn-danger pull-right">Not Available</a>
                                           @endif
                                       </div>
                                    </div>
                                      @if(count($item->additions) || ($item->sizes != null && count(json_decode($item->sizes))))
                                    <div class="modal fade" id="toppings-{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                      <div class="modal-dialog modal-dialog-centered" role="document">
                                        <form method="post" action="/cart/add/{{$item->id}}/custom">
                                          @csrf
                                           <div class="modal-content">
                                             <div class="modal-header">
                                               <h5 class="modal-title" id="exampleModalCenterTitle" style="font-weight: bold;">{{ $item->name }}</h5>
                                               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                 <span aria-hidden="true">&times;</span>
                                               </button>
                                             </div>
                                             <div class="modal-body">
                                                <h5 style="font-weight: bold;margin-bottom: 18px;">Choose Size/Category</h5>
                                                @if($item->sizes != null)
                                                @foreach(json_decode($item->sizes) as $key => $size)
                                                   <label class="custom-control custom-radio  m-b-20">
                                                          <input id="size" value="{{ $key }}" required="true" name="size" type="radio" class="custom-control-input"> <span class="custom-control-indicator"></span> <span class="custom-control-description">{{ $size->name }}(&#8377;{{ $size->price }})</span>
                                                          </label>
                                                @endforeach
                                                @endif
                                               @foreach($item->additions as $addition)
                                                   <h5 style="font-weight: bold;margin-bottom: 18px;">{{ $addition->name }}</h5>
                                                   @foreach(json_decode($addition->options) as $key => $option)
                                                      @if($addition->select_type == 0)
                                                         <label class="custom-control custom-radio  m-b-20">
                                                          <input id="{{str_slug($option->name)}}" value="{{ $key }}" name="{{str_slug($addition->name)}}" type="radio" class="custom-control-input"> <span class="custom-control-indicator"></span> <span class="custom-control-description">{{ $option->name }}(&#8377;{{ $option->price }})</span>
                                                          <br>
                                                          <span>{{ isset($option->description) ? $option->description : '' }}</span>
                                                          </label>

                                                      @else
                                                          <label class="custom-control custom-checkbox  m-b-20">
                                                          <input id="{{str_slug($option->name)}}" value="{{ $key }}" name="{{str_slug($addition->name)}}[]" type="checkbox" class="custom-control-input"> <span style="border-radius: 0;" class="custom-control-indicator"></span> <span class="custom-control-description">{{ $option->name }}(&#8377;{{ $option->price }})</span>
                                                          <br>
                                                          <span>{{ isset($option->description) ? $option->description : '' }}</span>
                                                          </label>
                                                      @endif
                                                    @endforeach
                                               @endforeach
                                             </div>
                                             <div class="modal-footer">
                                               <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                               <button type="submit" class="btn theme-btn">Add to Cart</button>
                                             </div>
                                          </form>
                                        </div>
                                      </div>
                                    </div>
                                    @endif
                                    <!-- end:row -->
                                 </div>
                          @endforeach

                           </div>
                           </div>
                          @endif
                     @endforeach

                     @endif


                  </div>
                  <!-- end:Bar -->
                  <div class="col-xs-12 col-md-12 col-lg-3 hidden-sm-down">
                     <div id="fixedCart" class="sidebar-wrap">
                        <form id="cart-form" method="GET" action="/checkout">


                           <input type="hidden" name="restaurant_id" value="{{$restaurant->id}}">
                            <input type="hidden" name="lat" value="{{request('lat')}}">
                             <input type="hidden" name="lng" value="{{request('lng')}}">
                           <div class="widget widget-cart" style="background: #fff;">
                              <div class="widget-heading">
                                 <h3 style="font-weight: bold;font-size: 24px;" class="widget-title text-dark">
                                    Cart <br>   <small style="color: #8a8a8a;font-size: 15px;">{{ Cart::instance('restaurant-'.$restaurant->id)->count() }} items</small>
                                 </h3>

                                 <div class="clearfix"></div>
                              </div>
                              <div class="scroll" style="max-height: 274px;
    overflow: scroll;">
                              @foreach(Cart::instance('restaurant-'.$restaurant->id)->content() as $item)
                              <?php $customs = $item->options->has('customs') ? json_decode($item->options->customs) : null; ?>
                              <div class="order-row bg-white">
                                 <div class="widget-body" style="padding: 10px;
    padding-bottom: 0;">
                                    <div class="title-row"><span style="font-size: 14px;display: block;">
                                             @if($item->model->is_veg)
                                                 <img src="/images/veg.png" style="width: 12px;height: 12px;margin-top: -2px;" >
                                                @else
                                                <img src="/images/nonveg.png" style="width: 12px;height: 12px;margin-top: -2px;" >
                                                @endif {{ $item->name }} {!! getCustomsString($customs) !!}</span>
                                    <div style="margin-bottom: 7px;margin-top: 10px;">

                                    <div data-trigger="spinner" id="spinner-{{$item->rowId}}" style="display: inline;text-align: center;margin-right: 6px;" >
                                      <a style="color: #f30; font-size: 18px;font-weight: bold;
   " href="javascript:;" data-spin="down">-</a>
                                      <input type="text" style="width: 40px;text-align: center;" min="1" value="{{ $item->qty }}" data-rule="quantity">
                                      <a href="" style="color: #f30; font-size: 18px;font-weight: bold;" href="javascript:;" data-spin="up">+</a>
                                    </div>

                                    <p style="font-size:12px;font-weight: normal;margin-top: 2px;margin-left: 3px;display: inline;" class="">&#8377; {{ $item->price * $item->qty }}
                                             <a class="one-click-links"  href="/cart/remove/{{$item->rowId}}/{{$restaurant->id}}"><i class="fa fa-trash"></i></a></p>


                                    {{-- <a href="/cart/remove/{{$item->rowId}}/{{$restaurant->id}}">
                                     <i class="fa fa-trash pull-right"></i></a> --}}




                                    </div>
                                     </div>


                                 </div>


                              </div>
                              @endforeach
                              </div>
                              <div class="order-row">
                                 <div class="widget-body">
                                  <div class="form-group row no-gutter">


                                       <input class="form-control" name="suggestions" style="background: #fcfcfc;color: #000;" type="text" placeholder="Any suggestions?">

                                 </div>


                                 </div>


                              </div>


                              <div class="widget-body">
                                 <div class="price-wrap text-xs-center">
                                    <p>SUBTOTAL</p>
                                    <h3 class="value"><strong>&#8377; {{ Cart::instance('restaurant-'.$restaurant->id)->subtotal() }}</strong></h3>
                                    <p  style="color: #8a8a8a;font-size: 14px;">Extra charges may apply</p>
                                    <button style="width: 100%;" type="submit" class="btn theme-btn btn-lg" {{  $restaurant->is_open && Cart::instance('restaurant-'.$restaurant->id)->count() && (floatval(\Cart::instance('restaurant-'.$restaurant->id)->subtotal(2, '.', ''))) > 99 ? '' : 'disabled'}}>Checkout</button>
                                    @if(floatval(\Cart::instance('restaurant-'.$restaurant->id)->subtotal(2, '.', '')) < 99)
                                     <p style="color: #000;font-size: 14px;margin-top: 5px;font-weight: bold;">Minimum order amount should be <br> Rs. 99</p>
                                    @endif
                                 </div>
                              </div>
                           </div>
                        </form>
                     </div>
                  </div>
                  <!-- end:Right Sidebar -->
               </div>
               <!-- end:row -->
            </div>




           <div class="filter-bottom filter-bottom-menu hidden-sm-up">
               <button id="showFilter" class="btn btn-link" style="color: rgb(233, 78, 27);" ><i class="fa fa-cutlery"></i> Menu</button>
           </div>

           <div class="restaurant-filters"  style="display: none;max-height: 500px;height: 500px;">
              <div class="main-block" style="max-height: 500px;height: 500px;">
                           <div class="sidebar-title white-txt">
                              <h6>Choose Cuisine</h6>
                              <a class="closeFilters" style="color: #fff;" href="#"><i class="fa fa-close pull-right"></i></a>
                           </div>
                           <ul style="max-height: 500px;height: 500px;overflow-y: scroll;padding-bottom: 100px;">

                              <li class="{{ request('filter') == 'all' ? 'active' : '' }}">
                                 <a href="/restaurants/{{ $restaurant->id }}?lat={{request('lat')}}&lng={{request('lng')}}&filter=all" class="scroll">All Items</a>
                              </li>

                              <li class="{{ request('filter') == 'featured' ? 'active' : '' }}">
                                 <a href="#restaurant-special" class="scroll closeFilters">Restaurant Special</a>
                              </li>

                              @if(!$restaurant->is_veg)
                                 <li class="{{ request('filter') == 'veg' ? 'active' : '' }}">
                                    <a href="/restaurants/{{ $restaurant->id }}?lat={{request('lat')}}&lng={{request('lng')}}&filter=veg" class="scroll">Veg Items</a>
                                 </li>

                                 <li class="{{ request('filter') == 'nonveg' ? 'active' : '' }}">
                                    <a href="/restaurants/{{ $restaurant->id }}?lat={{request('lat')}}&lng={{request('lng')}}&filter=nonveg" class="scroll">Non-Veg Items</a>
                                 </li>
                              @endif

                              @foreach($cuisineMenu as $cuisine)
                                @if($cuisine->parent_id == 0 || $cuisine->parent_id == null)
                                 <li class="{{ request('cuisine') ==  $cuisine->id ? 'active' : '' }}">

                                    @if($cuisine->subs()->count())
                                    <a data-toggle="collapse" aria-expanded="true" href="#cuisinemenu-{{ $cuisine->id }}" class="scroll">{{ $cuisine->name }}  <i style="margin-top: 5px;" class="fa fa-angle-right pull-right"></i>
                                    <i style="margin-top: 5px;" class="fa fa-angle-down pull-right"></i></a>


                                     @else

                                      <a href="#cuisine-{{$cuisine->id}}" class="scroll closeFilters">{{ $cuisine->name }} </a>
                                      @endif


                                 </li>
                                 <li>
                                    <div class="collapse in" id="cuisinemenu-{{ $cuisine->id }}">
                                      <ul style="background-color: #fff;">
                                          @foreach($cuisine->subs as $subCuisine)
                                            @if($subCuisine->items()->where('restaurant_id', $restaurant->id)->count())
                                              <li style="text-align: right;font-size: 15px;" class="{{ request('cuisine') ==  $cuisine->id ? 'active' : '' }}">
                                               <a style="padding: 4px;" href="#cuisine-{{$subCuisine->id}}" class="scroll closeFilters">{{ $subCuisine->name }}</a>
                                              </li>
                                            @endif
                                          @endforeach
                                      </ul>
                                     </div>
                                 </li>

                                  @endif

                              @endforeach

                           </ul>
                           <div class="clearfix"></div>
                        </div>
           </div>


           @if($restaurant->is_open && Cart::instance('restaurant-'.$restaurant->id)->count() && (floatval(\Cart::instance('restaurant-'.$restaurant->id)->subtotal(2, '.', ''))) > 99)
            <div class="filter-bottom filter-bottom-cart  hidden-sm-up">
               <button id="showCart" class="btn btn-link" style="color: rgb(233, 78, 27);" ><i class="fa fa-shopping-bag"></i> View Cart ( {{ Cart::instance('restaurant-'.$restaurant->id)->count() > 1 ? Cart::instance('restaurant-'.$restaurant->id)->count() . ' Items' : Cart::instance('restaurant-'.$restaurant->id)->count() . ' Item' }}  )

               <span>&#8377; {{ floatval(\Cart::instance('restaurant-'.$restaurant->id)->subtotal(2, '.', '')) }}</span>
               </button>
           </div>
           @endif

             @if(!$restaurant->is_open)
                <div class="filter-bottom filter-bottom-cart  hidden-sm-up" style="background: red !important;">
                   <button class="btn btn-link" style="color: rgb(233, 78, 27);" >Restaurant Closed</button>
               </div>
             @elseif($restaurant->is_open && (floatval(\Cart::instance('restaurant-'.$restaurant->id)->subtotal(2, '.', ''))) < 99)

              <div class="filter-bottom filter-bottom-cart  hidden-sm-up" style="background: red !important;">
                   <button class="btn btn-link" style="color: rgb(233, 78, 27);" >Minimum Order Should be Rs. 99</button>
               </div>

           @endif





@endsection


@section('scripts')


   <script src="/js/jquery.spinner.js"></script>

    @foreach(Cart::instance('restaurant-'.$restaurant->id)->content() as $item)

      <script>
      $("#spinner-{{$item->rowId}}")
        .spinner('delay', 200) //delay in ms
        .spinner('changed', function(e, newVal, oldVal) {
          // trigger lazed, depend on delay option.
        })
        .spinner('changing', function(e, newVal, oldVal) {
         if(newVal > oldVal)
         {

          window.location = '/cart/increment/{{$item->rowId}}/{{$restaurant->id}}/newVal:' + newVal
         } else {
             window.location = '/cart/decrement/{{$item->rowId}}/{{$restaurant->id}}/newVal:' + newVal
         }
        });
      </script>

       <script>
      $("#spinner2-{{$item->rowId}}")
        .spinner('delay', 200) //delay in ms
        .spinner('changed', function(e, newVal, oldVal) {
          // trigger lazed, depend on delay option.
        })
        .spinner('changing', function(e, newVal, oldVal) {
         if(newVal > oldVal)
         {

         window.location = '/cart/increment/{{$item->rowId}}/{{$restaurant->id}}/newVal:' + newVal
         } else {
              window.location = '/cart/decrement/{{$item->rowId}}/{{$restaurant->id}}/newVal:' + newVal
         }
        });


      </script>

        <script>
      $("#spinner3-{{$item->rowId}}")
        .spinner('delay', 200) //delay in ms
        .spinner('changed', function(e, newVal, oldVal) {
          // trigger lazed, depend on delay option.
        })
        .spinner('changing', function(e, newVal, oldVal) {
         if(newVal > oldVal)
         {

         window.location = '/cart/increment/{{$item->rowId}}/{{$restaurant->id}}/newVal:' + newVal
         } else {
              window.location = '/cart/decrement/{{$item->rowId}}/{{$restaurant->id}}/newVal:' + newVal
         }
        });


      </script>




    @endforeach



    <script type="text/javascript">
      window.onscroll = function() {myFunction()};

      function myFunction() {
          if (document.body.scrollTop > 468 || document.documentElement.scrollTop > 468) {
              $('#fixedSidebar').css('position', 'fixed');
               $('#fixedSidebar').css('width', '287px');
                 $('#fixedSidebar').css('top', '30px');
                  $('#fixedCart').css('position', 'fixed');
               $('#fixedCart').css('width', '287px');
                 $('#fixedCart').css('top', '30px');
          } else {
             $('#fixedSidebar').css('position', 'absolute');
               $('#fixedSidebar').css('width', '287px');
                 $('#fixedSidebar').css('top', '0px');
               $('#fixedCart').css('position', 'absolute');
               $('#fixedCart').css('width', '287px');
                 $('#fixedCart').css('top', '0px');
          }
      }



        $('#showFilter').on('click', function() {
        $('.restaurant-filters').show();
        });

       $('.closeFilters').on('click', function(e) {

              $('.restaurant-filters').hide();
              e.preventDefault();

       });

       $('#showCart').on('click', function() {
          // $('.resp-cart').show();
          $('#cart-form').submit();
        });

       $('#closeCart').on('click', function() {
        $('.resp-cart').hide();
        });






    </script>


@endsection