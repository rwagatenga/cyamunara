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

//-----Integrating Other Classes----
use DB;
use Validator;
use Redirect;


class UploadingController extends Controller
{
     private $apiToken;
    public function __construct()
    {
        // Unique Token
        $this->apiToken = uniqid(base64_encode(str_random(60)));
    }

    /**
    * Insert of Product Category
    *
    * @return \Illuminate\Http\Response
    */
    public function ProductCategoryInsert(Request $request)
    {
        //
        $token = $request->header('Authorization');
        $user = User::where('api_token',$token)->first();
        if ($user) {
            $rules = [
            'product_category' => 'required',
            ];
            //validate
            $validate = Validator::make($request->product_category, $rules);
            if ($validate->fails) {
                $message = $validate->messages();
                if ($message) {
                    return response()->json($message);
                }
            }
            else {
                $insert = Product_category::insert($request->product_category);
            }
        }
        else {
            return response()->json([
                'message' => 'Invalid Login'
            ]);
        }
        
    }

     /**
     * Product Form.
     *
     * @return \Illuminate\Http\Response
     */
     public function ProductForm(Request $request)
     {
        $token = $request->header('Authorization');
        $user = User::where('api_token', '=', $token)->first();
        if ($user) {
            $cate = Product_category::get(['category_name']);
            $cat = response()->json($cate);
             return $cat;
            
        }
     }

    /**
     * Insert Product.
     *
     * @return \Illuminate\Http\Response
     */
    public function ProductInsert(Request $request)
    {
        $token = $request->header('Authorization');
        $user = User::where('api_token', '=', $token)->first();
        if ($user) {
            $rules = [
                'category_name' => 'required',
                'product_name' => 'required',
                'product_type' => 'required',
                'product_model' => 'required',
                'product_quantity' => 'required|integer|min:1',
                'product_price' => 'required',
                'first_photo' => 'required|image|max:3000|mimes:jpg,jpeg, png',
                'other_photos*' => 'required|image|mimes:jpg,jpeg',
                'description' => 'required',
                'province' => 'required|min:3',
                'district' => 'required',
                'sector' => 'required',
            ];
            $validate = Validator::make($request->all(), $rules);
            if ($validate->fails()) {
                $message = $validate->messages();
                if ($message) {
                    return response()->json([
                        'message' => $message,
                    ]);
                }
            }
            else {
                $cat = Product_category::where('category_name', '=', $request->category_name)->first();
                $loc = DB::table('products')
                    ->join('locations', 'products.location_id', '=', 'location.id')
                    ->where('locations.province', '=', $request->province)
                    ->where('locations.district', '=', $request->district)
                    ->where('locations.sector', '=', $request->sector)
                    ->get();
                $file = $request->file('first_photo');
                $filename = $request->first_photo->getClientOriginalName();
                $distination = public_path().'/first_images';
                $file->move($distination, $filename);
                    
                    foreach ($request->file('other_photos') as $images) {
                        $names = $images->getClientOriginalName();
                        $images->move(public_path().'/other_images', $names);
                        $datas[] = $names;
                    }

                $save = new Product();
                $save->product_name=$request->product_name;
                $save->product_type=$request->product_type;
                $save->product_model=$request->product_model;
                $save->product_quantity=$request->product_quantity;
                $save->product_price=$request->prouduct_price;
                $save->first_photo=$request->first_photo;
                $save->other_photos=json_encode($datas);
                $save->description=$request->description;
                $save->status=0;
                $save->pstatus=0;
                $save->category_id=$cat->id;
                $save->user_id=$user->id;
                $save->location_id=$loc[0]->id;
                $save->save();
            }
        }
    }
    public function ProductDisplay(Request $request)
    {
        $token = $request->header('Authorization');
        $user = User::where('api_token', '=', $token)->first();
        if ($user) {
            $done = DB::table('products')
            ->join('users', 'products.user_id', '=', 'users.id')
            ->where('users.id', '=', $user->id)
            ->orWhere('users.role_id', '=', 1)
            ->join('locations', 'products.location_id', '=', 'locations.id')
            ->orderBy('id', 'DESC')
            ->get();
            return response()->json($done);
        }
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
    public function ProductShow($id)
    {
        //
        $token = $request->header('Authorization');
        $user = User::where('api_token', '=', $token)->first();
        if ($user) {
            $check = Product::findOrFail($id)->first();
             $done = DB::table('products')
                ->join('users', 'products.user_id', '=', 'users.id')
                ->where('users.id', '=', $user->id)
                ->where('products.id', '=', $check->id)
                ->orWhere('users.role_id', '=', 1)
                ->join('locations', 'products.location_id', '=', 'locations.id')
                ->orderBy('id', 'DESC')
                ->get();
                return response()->json($done);
        }
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
    public function ProductUpdate(Request $request, $id)
    {
        //
        $token = $request->header('Authorization');
        $user = User::where('api_token', '=', $token)->first();
        if ($user) {
            $check = Product::findOrFail($id)->first();
             $done = DB::table('products')
                ->join('users', 'products.user_id', '=', 'users.id')
                ->where('users.id', '=', $user->id)
                ->where('products.id', '=', $check->id)
                ->orWhere('users.role_id', '=', 1)
                ->join('locations', 'products.location_id', '=', 'locations.id')
                ->orderBy('id', 'DESC')
                ->get();
            $done = DB::table('products')
                ->join('users', 'products.user_id', '=', 'users.id')
                ->where('users.id', '=', $user->id)
                ->where('products.id', '=', $check->id)
                ->orWhere('users.role_id', '=', 1)
                ->join('locations', 'products.location_id', '=', 'locations.id')
                ->update(array(
                    'product_name' => $request->product_name,
                    'product_type' => $request->product_type,
                    'product_model' => $request->product_model,
                    'product_quantity' => $request->product_quantity,
                    'product_price' => $request->prouduct_price,
                    'description' => $request->description,
                    'status' => $request->status,
                    'pstatus' => $request->pstatus,
                    'province' => $request->province,
                    'district' => $request->district,
                    'sector' => $request->sector,
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
    public function test(Request $request)
    {
        $request->test;
        if ($request->test == 'hello') {
            return response()->json($request->test);
        }
        else {
            return response()->json("Wapi 2");
        }
    }
}
