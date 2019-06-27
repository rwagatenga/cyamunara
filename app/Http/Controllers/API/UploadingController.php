<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
                $save->pstatus='View';
                $save->category_id=$cat->id;
                $save->user_id=$user->id;
                $save->save();
            }
        }
    }
    public function ProductDisplay(Request $request)
    {
        $token = $request->header('Authorization');
        $user = User::where('api_token', '=', $token)->first();
        if ($user) {
            $done = DB::table('users')

            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->where('users.id', '=', $user->id)
            ->join('users', 'products.user_id', '=', 'users.id')
            ->orderBy('id', 'DESC')
            ->get();
        }
       $done = DB::table('users')
        ->join('roles', 'users.role_id', '=', 'roles.id')
        ->where('users.id', '=', 2)//$user->id)
        ->orwhere('roles.role_name', '=', 'Admin')
        ->(['users.first_name']);
        return response()->json($done);
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
        $rule = [
            'product_name' => 'required',
            'product_type' => 'required',
            'product_quantity' => ''
        ];
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
