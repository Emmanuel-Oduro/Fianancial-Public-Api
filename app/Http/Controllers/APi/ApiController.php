<?php

namespace App\Http\Controllers\APi;

use App\User;
use App\Bonus;
use App\Salary;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{

    public function index()
    {
        if (auth()->user()->role_name == 'admin') {

            return response()->json(['user' => User::where('role_name', '!=', 'admin')->get()], 200);

            }else{
                return response()->json(['error' => 'UnAuthorised'], 401);
            }
    }

    public function Home()
    {
        if (auth()->user()->role_name == 'admin') {

            $userCount = User::count();
            // return $userCount;
            $salaryCount = Salary::count();
            $productCount = Product::count();

            $upcomingproducts = Product::where('product_status', '=', 'Upcoming')->get();
            $ongoingproducts = Product::where('product_status', '=', 'Ongoing')->get();
            $pastproducts = Product::where('product_status', '=', 'Past')->get();

            return response()->json(['user' => $userCount, 
                                     'salary' => $salaryCount, 
                                     'product' => $productCount,
                                     'upcomingproducts' => $upcomingproducts,
                                     'ongoingproducts' => $ongoingproducts,
                                     'pastproducts' => $pastproducts
                                    ], 200);

            }else{
                return response()->json(['error' => 'UnAuthorised'], 401);
            }
    }
 
    public function show(Request $request, $id)
    {
        if (auth()->user()->role_name == 'admin') {
            
                $users = User::find($id);

                $user_products = Product::where('user_id', $id)->take(3)->get();

                $user_salaries  = Salary::where('user_id', $id)->take(1)->get();

                $user_bonus  = Bonus::where('user_id', $id)->take(1)->get();
    
                $data = response()->json(['User Detail' => $users, 'User Salary History' => $user_salaries, 'User Monthly Bonuses' =>   $user_bonus,  'User Products' => $user_products], 200);
                return $data;
            
        } else {
            return response()->json(['error' => 'UnAuthorised'], 401);
        }
        
    }

    public function edit(Request $request, $id)
    {
        if (auth()->user()->role_name == 'admin') {
            
                $users = User::find($id);

                $user_products = Product::where('user_id', $id)->take(3)->get();

                $user_salaries  = Salary::where('user_id', $id)->take(1)->get();

                $user_bonus  = Bonus::where('user_id', $id)->take(1)->get();
    
                 $data = response()->json(['User Detail' => $users, 'User Salary History' => $user_salaries, 'User Monthly Bonuses' =>   $user_bonus,  'User Products' => $user_products], 200);
                 return $data;
        } else {
            return response()->json(['error' => 'UnAuthorised'], 401);
        }
        
    }

    public function update(Request $request, $id)
    {
        // return $request->all();
        if (auth()->user()->role_name == 'admin') {
            $approve_user = User::find($id);
            $approve_user->update(['user_status' => 'approve']);

            if ($approve_user) {
                return response()->json(['success' => 'User ' .$approve_user->name. ' approved successfully!'], 200);
            } else {
                return response()->json(['error' => 'UnAuthorised'], 401);
            }
            

        } else {
            return response()->json(['error' => 'UnAuthorised User'], 401);
        }
        
    }

    public function registerForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // return $request->all();
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|unique:users',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }
        
        $checkUsers = User::count();

        if ($checkUsers == 0) {

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role_name' => 'admin',
                'user_status' => 'approve',
                'password' => bcrypt($request->password)
            ]);

        }else {

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role_name' => 'customer',
                'user_status' => 'pending',
                'password' => bcrypt($request->password)
            ]);
        }

        $token = $user->createToken('loginaccess')->accessToken;

        return response()->json(['token' => $token], 200);
    }

    public function loginForm()
    {
        return view('auth.login');
    }

    /**
     * Handles Login Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth()->attempt($credentials)) {

            if (auth()->user()->user_status == 'approve') {
                $token = auth()->user()->createToken('loginaccess')->accessToken;
                return response()->json(['token' => $token], 200);
            } else {
                return response()->json(['error' => 'User is not Approved Yet... Please wait your approval email. Thank you!'], 401);
            }

        } else {
            return response()->json(['error' => 'UnAuthorised'], 401);
        }
    }

    /**
     * Returns Authenticated User Details
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userdetails()
    {
        
        return response()->json(['user' => auth()->user()], 200);
        
    }

    public function ProductCategory(Request $request)
    {
        // $products = auth()->user()->products;
        if (auth()->user()->role_name == 'admin') {
            
             $id = $request->user_id;
             $product_status = $request->product_status;

            if ($request->product_status) {

                $users = User::find($id);

                $user_products = Product::where('user_id', $id)->where('product_status', $product_status)->get();
                
                return response()->json(['users' => $users, 'products_category' => $user_products], 200);

            } else {

                $users = User::find($id);

                $user_products = Product::where('user_id', $id)->get();
    
                return response()->json(['users' => $users, 'user_products' => $user_products], 200);
            }
            
        } else {
            return response()->json(['error' => 'UnAuthorised'], 401);
        }
 
        // return response()->json([
        //     'success' => true,
        //     'data' => $products
        // ], 200);
    }

    public function ApproveStatus(Request $request, $id)
    {
        // return $request->all();
        $basic_salary_amount = $request->basic_salary_amount;
        if (auth()->user()->role_name == 'admin') {
            $approve_user = User::find($id);
            $approve_user->update(['user_status' => 'approve']);

            if ($approve_user) {

                $assignUserSalary = Salary::create([
                    'user_id' =>   $id,
                    'basic_salary_amount' =>  5000,
                    'paid_amount' =>  0,
                    'balance' =>  0,
                    'paid_month' =>  '',
                    'paid_date' =>  date('Y-m-d'),
                ]);

                return response()->json(['success' => 'User ' .$approve_user->name. ' approved successfully!'], 200);
            } else {
                return response()->json(['error' => 'UnAuthorised'], 401);
            }
            

        } else {
            return response()->json(['error' => 'UnAuthorised User'], 401);
        }
        
    }

    public function RejectStatus(Request $request, $id)
    {
        // return $request->all();
        if (auth()->user()->role_name == 'admin') {
            $approve_user = User::find($id);
            $approve_user->update(['user_status' => 'reject']);

            if ($approve_user) {

                $assignUserSalary = Salary::where('user_id', $id)->first();
                if ($assignUserSalary) {
                    $assignUserSalary->delete();
                }
                
                return response()->json(['success' => 'User ' .$approve_user->name. ' approved successfully!'], 200);
            } else {
                return response()->json(['error' => 'UnAuthorised'], 401);
            }
            

        } else {
            return response()->json(['error' => 'UnAuthorised User'], 401);
        }
        
    }

}
