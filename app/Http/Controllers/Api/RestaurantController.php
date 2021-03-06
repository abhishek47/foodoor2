<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\Order;
use App\Events\OrderStatusChanged;
use App\Http\Controllers\Controller;

class RestaurantController extends Controller
{

    public function confirmOrder(Order $order)
    {
        $user = request()->user();



        if(!$user)
        {
            return response(['status' => 'failed', 'message' => 'User doesn\'t exist!']);
        }

        if(!$user->is_restaurant)
        {
            return response(['status' => 'failed', 'message' => 'User should be a restaurant!']);
        }

        // dd($user->restaurant->id);
        if($order->restaurant_id != $user->restaurant->id)
        {
            return response(['status' => 'failed', 'message' => 'The order belongs to other restaurant!']);
        }

        $order->status = 1;
        $order->save();

        event(new OrderStatusChanged($order));

        // send user message;

        return response(['status' => 'success', 'message' => 'Order Confirmed!']);
    }

     public function cancelOrder(Order $order)
    {
        $user = request()->user();


        if(!$user)
        {
            return response(['status' => 'failed', 'message' => 'User doesn\'t exist!']);
        }

        if(!$user->is_restaurant)
        {
            return response(['status' => 'failed', 'message' => 'User should be a restaurant!']);
        }

        // dd($user->restaurant->id);
        if($order->restaurant_id != $user->restaurant->id)
        {
            return response(['status' => 'failed', 'message' => 'The order belongs to other restaurant!']);
        }

        $order->status = -1;
        $order->cancel_reason = request('cancel_reason');
        $order->save();

        event(new OrderStatusChanged($order));

        // send user message;

        return response(['status' => 'success', 'message' => 'Order Cancelled!']);
    }




    public function orderReady(Order $order)
    {
        $user = request()->user();

        if(!$user)
        {
            return response(['status' => 'failed', 'message' => 'User doesn\'t exist!']);
        }

        if(!$user->is_restaurant)
        {
            return response(['status' => 'failed', 'message' => 'User should be a restaurant!']);
        }

        if($order->restaurant_id != $user->restaurant->id)
        {
            return response(['status' => 'failed', 'message' => 'The order belongs to other restaurant!']);
        }

        $order->status = 2;
        $order->save();

        event(new OrderStatusChanged($order));

        // send user message;

        return response(['status' => 'success', 'message' => 'Order Ready!']);

    }

    public function confirmOrders()
    {

        $user = request()->user();

        if(!$user)
        {
            return response(['status' => 'failed', 'message' => 'User doesn\'t exist!']);
        }

        if(!$user->is_restaurant)
        {
            return response(['status' => 'failed', 'message' => 'User should be a restaurant!']);
        }

        $orders = Order::where('status', '=', 1)->where('restaurant_id', $user->restaurant->id)->with('restaurant')->latest()->with('user')->get();

        return response(['status' => 'success', 'message' => 'All Confirmed Orders!', 'orders' => $orders], 200);

    }

    public function readyOrders()
    {
        $user = request()->user();

        if(!$user)
        {
            return response(['status' => 'failed', 'message' => 'User doesn\'t exist!']);
        }

        if(!$user->is_restaurant)
        {
            return response(['status' => 'failed', 'message' => 'User should be a restaurant!']);
        }



        $orders = Order::where('status', '=', 2)->where('restaurant_id', $user->restaurant->id)->with('restaurant')->latest()->with('user')->get();

        return response(['status' => 'success', 'message' => 'All Ready Orders!', 'orders' => $orders], 200);

    }

    public function newOrders()
    {
        $user = request()->user();

        if(!$user)
        {
            return response(['status' => 'failed', 'message' => 'User doesn\'t exist!']);
        }

        if(!$user->is_restaurant)
        {
            return response(['status' => 'failed', 'message' => 'User should be a restaurant!']);
        }



        $orders = Order::where('status', '=', 0)->where('restaurant_id', $user->restaurant->id)->whereDate('created_at', '=', date('Y-m-d'))->with('restaurant')->latest()->with('user')->get();

        return response(['status' => 'success', 'message' => 'All New Orders to be confirmed!', 'orders' => $orders], 200);
    }

    public function orders()
    {
        $user = request()->user();

        if(!$user)
        {
            return response(['status' => 'failed', 'message' => 'User doesn\'t exist!']);
        }

        if(!$user->is_restaurant)
        {
            return response(['status' => 'failed', 'message' => 'User should be a restaurant!']);
        }



        $orders = Order::where('status', '<', 4)->where('status', '>', 0)->where('restaurant_id', $user->restaurant->id)->latest()->with('restaurant')->with('user')->get();

        return response(['status' => 'success', 'message' => 'All Running Orders!', 'orders' => $orders], 200);

    }

    public function history()
    {
        $user = request()->user();

        if(!$user)
        {
            return response(['status' => 'failed', 'message' => 'User doesn\'t exist!']);
        }

        if(!$user->is_restaurant)
        {
            return response(['status' => 'failed', 'message' => 'User should be a restaurant!']);
        }



        $orders = Order::where('status', 4)->orWhere('status', -1)->where('restaurant_id', $user->restaurant->id)->with('restaurant')->latest()->with('user')->get();

        return response(['status' => 'success', 'message' => 'Orders history!', 'orders' => $orders], 200);

    }


     public function getOrder(Order $order)
    {
        $user = request()->user();

        if(!$user)
        {
            return response(['status' => 'failed', 'message' => 'User doesn\'t exist!']);
        }

        if(!$user->is_restaurant)
        {
            return response(['status' => 'failed', 'message' => 'User should be a restaurant!']);
        }

        if($order->restaurant_id != $user->restaurant->id)
        {
            return response(['status' => 'failed', 'message' => 'The order belongs to other restaurant!']);
        }



        $order->load('items');

        foreach ($order->items as $key => $item) {
            $item->custom_toppings = getCustomsString(json_decode($item->pivot->customs));
        }

        return response(['status' => 'success', 'message' => 'Order Details Sent!', 'order' => $order], 200);

    }

    public function getItems(Restaurant $restaurant)
    {
        return response(['items' => $restaurant->items, 'cuisines' => $restaurant->cuisines, 200]);
    }
}
