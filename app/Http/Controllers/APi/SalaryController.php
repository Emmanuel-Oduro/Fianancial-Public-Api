<?php

namespace App\Http\Controllers\APi;

use App\User;
use App\Bonus;
use App\Salary;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->role_name == 'admin') {

                $salarypaid = Salary::join('users', 'users.id', '=', 'salaries.user_id')
                ->select('users.name', 'users.role_name', 'salaries.*', 'users.id as auth_id')
                ->where('salaries.paid_amount', '!=',  0)
                ->where('salaries.balance', '=',  0)->get();

                $salary = Salary::join('users', 'users.id', '=', 'salaries.user_id')
                ->select('users.name', 'users.id as user_id', 'users.role_name', 'salaries.*')
                ->where('salaries.paid_amount', '=',  0)
                ->where('salaries.paid_month', '!=', date('F'))
                ->get();

            return response()->json(['salary' => $salary, 'salarypaid' => $salarypaid], 200);

            }else{
                return response()->json(['error' => 'UnAuthorised'], 401);
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
        // Assign User Salary
        if (auth()->user()->role_name == 'admin') {
            
            $users = User::find($request->user_id);

            if (!$users) {
                return response()->json(['error' => 'User not fund '], 401);
            }

            $user_name  = $users->name;
            $user_id = $request->user_id;
            $paid_month = $request->paid_month;
            $paid_amount = $request->paid_amount;
            $user_bonus = $request->bonus_amount;
            $basic_salary_amount = $request->basic_salary_amount;
            $paid_date = date('Y-m-d', strtotime($request->paid_date));
            $balance = $basic_salary_amount - $paid_amount;

            $salaryCount = Salary::where('user_id', $user_id)->where('paid_month', $paid_month)->count();

                if ($salaryCount > 0) {

                    return response()->json(['Exist' => 'Salary already assign to user ' .$user_name. ' for month of ' .$paid_month. ' ' ], 401);
                } else {

                    $assignUserSalary = Salary::create([
                        'user_id' =>   $user_id,
                        'basic_salary_amount' =>  $basic_salary_amount,
                        'paid_amount' =>  $paid_amount,
                        'balance' =>  $balance,
                        'paid_month' =>  $paid_month,
                        'paid_date' =>  $paid_date,
                    ]);

                    if (!$assignUserSalary) {
                        return response()->json(['error' => 'Salary Faild to assign to user ' .$user_name. ' for month of ' .$paid_month. ' ' ], 401);
                    } else {

                        if ($user_bonus) {

                            $UserBonus = Bonus::create([
                                'salary_id' =>  $assignUserSalary->id,
                                'user_id' =>  $user_id,
                                'bonus_amount' =>  $user_bonus,
                                'bonus_month' =>  $paid_month,
                            ]);

                        return response()->json(['User ' .$user_name. ' salary for month of ' .$paid_month. ' Successfully Paid ' => $assignUserSalary , 'Bonus for month of ' .$paid_month. ' Successfully Paid ' => $UserBonus ], 200);

                        }
                        return response()->json(['User ' .$user_name. ' salary for month of ' .$paid_month. ' Successfully Paid ' => $assignUserSalary, 'Bonus for month of ' .$paid_month. '' => ' No Bonus for this User'], 200);
                    }

                }
            
        }else{
            return response()->json(['error' => 'UnAuthorised User!  Only Authorized Admin '], 401);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (auth()->user()->role_name == 'admin') {
            
            $UpdateUserSalary = Salary::find($id);
            // return $UpdateUserSalary;
            $users = User::find($UpdateUserSalary->user_id);

            if (!$users) {
                return response()->json(['error' => 'User not fund '], 401);
            }
            return response()->json($UpdateUserSalary, 200);
        }else{
            return response()->json(['error' => 'UnAuthorised User!  Only Authorized Admin '], 401);
        }
    }

    public function UserSalaryHistory($id)
    {
        if (auth()->user()->role_name == 'admin') {
            
            $UsersalaryHistory = Salary::join('users', 'users.id', '=', 'salaries.user_id')
            ->join('bonuses', 'bonuses.user_id', '=', 'salaries.user_id')
            ->select('users.name', 'users.id as user_id', 'users.role_name', 'salaries.*','bonuses.bonus_amount', 'bonuses.bonus_month')
            ->where('users.id', $id)
            ->get();

            $users = User::find($id);

            return response()->json(['history' => $UsersalaryHistory, 'users' => $users], 200);
        }else{
            return response()->json(['error' => 'UnAuthorised User!  Only Authorized Admin '], 401);
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
        if (auth()->user()->role_name == 'admin') {
            
            $UpdateUserSalary = Salary::find($id);
            // return $UpdateUserSalary;
            $users = User::find($UpdateUserSalary->user_id);

            if (!$users) {
                return response()->json(['error' => 'User not fund '], 401);
            }
            return response()->json(['salarypay ' => $UpdateUserSalary, 'users' => $users], 200);
        }else{
            return response()->json(['error' => 'UnAuthorised User!  Only Authorized Admin '], 401);
        }
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
          // Update User Salary
        //   return $request->all();
          if (auth()->user()->role_name == 'admin') {
            
            $UpdateUserSalary = Salary::find($id);
            // return $UpdateUserSalary;
            $users = User::find($UpdateUserSalary->user_id);

            if (!$users) {
                return response()->json(['error' => 'User not fund '], 401);
            }

                $user_name  = $users->name;
                $user_id = $users->id;
                $paid_month = $request->paid_month;
                $user_bonus = $request->bonus_amount;
                $paid_amount = $request->paid_amount;
                $paid_date = $request->paid_date;

                    if ($UpdateUserSalary->basic_salary_amount == $paid_amount) {
                        $UpdateUserSalary->update(['paid_amount' => $paid_amount, 
                                                   'balance' => 0, 'paid_date' => $paid_date,
                                                   'paid_month' => $paid_month]);

                    } elseif ($paid_amount > $UpdateUserSalary->basic_salary_amount) { 

                            $balance =   $paid_amount - $UpdateUserSalary->basic_salary_amount;

                            $UpdateUserSalary->update(['paid_amount' => $paid_amount, 'balance' => $balance ,
                                                       'paid_date' => $paid_date, 'paid_month' => $paid_month]);

                        } else {

                            $balance =   $UpdateUserSalary->basic_salary_amount - $paid_amount;

                            $UpdateUserSalary->update(['paid_amount' => $paid_amount, 'balance' => $balance ,
                                                        'paid_date' => $paid_date , 'paid_month' => $paid_month]);

                    }

                    if (!$UpdateUserSalary) {
                        return response()->json(['error' => 'Salary Faild to Update to user ' .$user_name. ' for month of ' .$paid_month. ' ' ], 401);
                    } else {
                                       
                        $formatUserDetail = ['user_id' => $UpdateUserSalary->user_id, 
                                             'basic_salary_amount' => number_format($UpdateUserSalary->basic_salary_amount, 2),
                                             'paid_amount'  => number_format($UpdateUserSalary->paid_amount,2),
                                             'paid_month'  =>  $UpdateUserSalary->paid_month,
                                             'balance'  => number_format( $UpdateUserSalary->balance,2),
                                             'paid_date'  => $UpdateUserSalary->paid_date,];

                        if ($user_bonus) {

                            $UserBonus = Bonus::find($id);


                            if (!$UserBonus) {
                                $UserBonus = Bonus::create([
                                    'salary_id' =>  $UpdateUserSalary->id,
                                    'user_id' =>  $user_id,
                                    'bonus_amount' =>  $user_bonus,
                                    'bonus_month' =>  $paid_month,
                                ]);

                            } else {

                                $UserBonus->update(['bonus_amount' =>  $user_bonus]);
                            }

                            $formatUserBonus = ['user_id' => $UserBonus->user_id, 
                                             'salary_id' => $UserBonus->salary_id,
                                             'bonus_amount'  => number_format($UserBonus->bonus_amount,2),
                                             'bonus_month'  =>  $UserBonus->bonus_month
                                               ];

                            if (!$UserBonus) {

                                return response()->json(['User ' .$user_name. ' salary for month of ' .$paid_month. ' Updated Successfully ' => $formatUserDetail , 'Bonus for month of ' .$paid_month. ' Successfully Paid ' => $formatUserBonus ], 200);

                            }

                            return response()->json(['User ' .$user_name. ' salary for month of ' .$paid_month. ' Updated Successfully ' => $formatUserDetail , 'Bonus for month of ' .$paid_month. ' Updated Successfully ' => $formatUserBonus ], 200);

                        }
                        $user_salary = ['User ' .$user_name. ' salary for month of ' .$paid_month. ' Successfully Paid ' => $formatUserDetail, 'Bonus for month of ' .$paid_month. '' => ' No Bonus for this User'];
                        return response()->json($user_salary, 200);
                    }
            
        }else{
            return response()->json(['error' => 'UnAuthorised User!  Only Authorized Admin '], 401);
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
