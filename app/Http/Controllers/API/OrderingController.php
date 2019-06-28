<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;

//----Integrating Models---
use App\User;
use App\Role;
use App\Brand;
use App\Order;
use App\Product;
use App\Product_category;
use App\Product_type;
use App\Product_class;

class OrderingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $done = DB::table('products')
            ->join('users', 'products.user_id', '=', 'users.id')
            ->join('locations', 'products.location_id', '=', 'locations.id')
            ->orderBy('id', 'DESC')
            ->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $product = Product::findOrFail($id)->first();
        $show = DB::table('products')
                ->join('users', 'products.user_id', '=', 'users.id')
                ->where('products.id', '=', $product->id)
                ->join('locations', 'products.location_id', '=', 'locations.id')
                ->get();
        return response()->json($show);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $token = $request->header('Authorization');
        $user = User::where('api_token', '=', $token)->first();
        if ($user) {
            $check = Product::findOrFail($id)->first();
            $done = DB::table('orders')
                ->join('users', 'orders.user_id', '=', 'users.id')
                ->where('users.id', '=', $user->id)
                ->join('locations', 'orders.location_id', '=', 'locations.id')
                ->update(array(
                    'product_id' => $check->id,
                ));
            $updated = DB::table('orders')
                ->join('product', 'orders.product_id', '=', 'products.id')
                ->where('products.id', '=', $check->id)
                ->update(array(
                    'status' => 1,
                    'pstatus' => ($check->pstatus) + 1
                ));
                return response()->json($done);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
