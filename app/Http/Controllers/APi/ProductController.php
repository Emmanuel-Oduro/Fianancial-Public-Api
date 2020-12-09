<?php

namespace App\Http\Controllers\APi;

use App\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index(){

        if (auth()->user()->role_name == "admin") {

            $products = Product::join('users', 'users.id', '=', 'products.user_id')
			->orderBy('user_id', 'desc')
			->select('products.*', 'users.name as user_name')->take(10)->get();
		
            return response()->json([
                'success' => true,
                'data' => $products
              ], 200);

        }else {

            $products = auth()->user()->products;
 
            return response()->json([
                'success' => true,
                'data' => $products
              ], 200);
        }

    }

    public function ProductCategory(Request $request)
    {
        $products = auth()->user()->products;
 
        return response()->json([
            'success' => true,
            'data' => $products
        ], 200);
    }
 
    public function show($id){
        // return Product::find($id);
        if(auth()->user()->role_name == 'admin')
		{
            $products = Product::find($id);
 
            if(!$products){
                return response()->json([
                 'error' => false,
                'data' => 'Product with id ' . $id . ' not found',
             ], 400);
            }
     
            return response()->json([
                'success' => true,
                'data' => $products
            ], 200);
        }
      else {

        $products = auth()->user()->products()->find($id);
 
        if(!$products){
            return response()->json([
             'error' => false,
            'data' => 'Product with id ' . $id . ' not found',
         ], 400);
        }
 
        return response()->json([
            'success' => true,
            'data' => $products
        ], 200);
      }
    }
 
    public function store(Request $request){
        $this->validate($request, [
            'name' => 'required',
            'price' => 'required|integer',
            'product_status' => 'required',
        ]);

        
        // $exploded = explode(',', $request->image);

        // $decoded = base64_decode($exploded[1]);

        // // return $decoded;
        // if (str_contains($exploded[1], 'jpeg')) {
        //     $extension = 'jpg';
        // }else {
        //     $extension = 'png';
        // }

        // $fileName = time().'.'.$extension;
        // $path = public_path('images/products').$extension;
        // file_put_contents($fileName, $exploded);

        $random = Str::random(40);
        $product = new Product();
        $product->name = $request->name;
        $product->slug = $random;
        $product->price = $request->price;
        $product->product_status = $request->product_status;
        // $product->image = $fileName;
        $product->image = '';
 
        if(auth()->user()->products()->save($product)){
            return response()->json([
             'success' => true,
             'data' => $product->toArray(),
        ], 200);
        }else{
             return response()->json([
                 'error' => false,
                 'data' => 'Product could not be found',
             ], 500);
        }
    }
	
	public function edit($id){
		
		if(auth()->user()->role_name == 'admin')
		{
			$products = Product::find($id);
			
			if(!$products){
			
				return response()->json([
				'error' => false,
				'message' => 'Product with id ' . $id . ' not found'
				], 404);
			}else{
				return response()->json([
                'success' => true,
                'data' => $products,
              ], 200);
			}
		 
      }else{
		  $products = auth()->user()->products()->find($id);
		  
		 if(!$products){
			
				return response()->json([
				'error' => false,
				'message' => 'Product with id ' . $id . ' not found'
				], 404);
			}else{
				return response()->json([
                'success' => true,
                'data' => $products,
              ], 200);
			}
	  }
	}
	
 
    public function update(Request $request, $id){

	if(auth()->user()->role_name == 'admin')
		{
			$product = Product::find($id);
      
		if(!$product){
         return response()->json([
                 'error' => false,
                 'data' => 'Product with id ' . $id . ' not found',
         ], 404);
      }
 
      $updated = $product->fill($request->all())->save();
 
      if($updated){
         return response()->json([
                 'success' => true,
                 'message' => 'Product updated successfully',
         ]);
      }else{
         return response()->json([
                 'error' => false,
                 'message' => 'Product could not be updated',
         ], 404);
      }
		}
		else{
      $product = auth()->user()->products()->find($id);
      
      if(!$product){
         return response()->json([
                 'error' => false,
                 'data' => 'Product with id ' . $id . ' not found',
         ], 500);
      }
 
      $updated = $product->fill($request->all())->save();
 
      if($updated){
         return response()->json([
                 'success' => true,
                 'message' => 'Product updated successfully',
         ]);
      }else{
         return response()->json([
                 'error' => false,
                 'message' => 'Product could not be updated',
         ], 401);
      }
	}
   }
 
    public function destroy($id){
		
		if(auth()->user()->role_name == 'admin')
		{
			$product = Product::find($id);
		  if (!$product){
         return response()->json([
                 'error' => false,
                 'message' => 'Product with id ' . $id . ' not found'
         ], 404);
      }
 
      if($product->delete()){
         return response()->json([
                 'success' => true,
                 'message' => "Product deleted successfully"
         ], 200);
      }else{
         return response()->json([
                 'error' => false,
                 'message' => 'Product could not be deleted'
          ], 404);
      }
    }else
	{
      $product = auth()->user()->products()->find($id);
 
      if (!$product){
         return response()->json([
                 'error' => false,
                 'message' => 'Product with id ' . $id . ' not found'
         ], 400);
      }
 
      if($product->delete()){
         return response()->json([
                 'success' => true,
                 'message' => "Product deleted successfully"
         ], 200);
      }else{
         return response()->json([
                 'error' => false,
                 'message' => 'Product could not be deleted'
          ], 500);
      }
    }  if (!$product){
         return response()->json([
                 'error' => false,
                 'message' => 'Product with id ' . $id . ' not found'
         ], 400);
      }
 
      if($product->delete()){
         return response()->json([
                 'success' => true,
                 'message' => "Product deleted successfully"
         ], 200);
      }else{
         return response()->json([
                 'error' => false,
                 'message' => 'Product could not be deleted'
          ], 500);
      }
    }
}
