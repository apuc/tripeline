<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\Routes;
use App\Models\User;
use App\Models\Profile;
use App\Models\Company;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Helper\EmailHelper;

class PartnerController extends Controller {

    /**
     * Show products list.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|void
     */
    public function index( \Illuminate\Http\Request $request ) {

        try {
            $routes = Partner::select( [ 'countries.name', 'countries.id' ] )->where( 'status', '=', 'open' )
                            ->join( 'countries', 'countries.id', '=', 'routes.route_to_country_id' )
                            ->groupBy( 'countries.id' )
                            ->get();
            return view( 'pages/routes', [ 'routes' => $routes ] );

        } catch ( \Throwable $t ) {
            return view( 'pages/routes', [ 'routes' => [], 'error' => $t->getMessage() ] );
        }
    }


    /**
     * Show products list.
     *
     * @return array
     */
    public function list(): array {
        try {
            $partners = Partner::select( [ 'partners.*' ] )
                             ->where( 'status', '=', 'enabled' )
                            ->get()->toArray();
            return $partners;
        } catch ( \Throwable $t ) {
            return [$t->getMessage()];
        }
    }

    public function add(Request $request) {

        $result = [
            'status' => false,
            'errors' => '',
        ];

        $pass = 'Pass!'. rand(100, 900);
//        $userId = User::max('id') + 1;

        try {
            DB::beginTransaction();

            $user = User::create([
                'email'    => $request->partner['email'],
                'phone'    => $request->partner['phone'],
                'password' => Hash::make($pass),
                'is_admin' => 0,
                'role_id'  => $request->partner['roleId'], //Partner - 4, driver - 5, travel agency - 6
            ]);

            Profile::create([
                'user_id' => $user->id,
                'first_name'   => $request->partner['firstName'],
                'last_name'    => $request->partner['lastName'],
                'city' => $request->partner['city'],
                'english_lvl' => $request->partner['englishLvl'],
            ]);

            Company::create([
                'user_id' => $user->id,
                'name' => $request->partner['companyName'] ?? '',
            ]);

            $partnerData = [
                'name' => $request->partner['firstName'] . ' ' . $request->partner['lastName'],
                'email' => $request->partner['email'],
                'phone' => $request->partner['phone'],
                'company_name' => $request->partner['companyName'] ?? '',
                'city' => $request->partner['city'],
                'english_lvl' => $request->partner['englishLvl'],
            ];

            EmailHelper::sendEmailFromRegPartner($partnerData);

            $result['status'] = true;
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            $result['errors'] = $e->getMessage();
        }

        return $result;
    }

}
