<?php

namespace App\Http\Controllers;

use App\Date;
use App\User;
use Faker\Provider\DateTime;
use Illuminate\Http\Request;
use function response;

class DateController extends Controller
{

//    public function toggle(Request $request)
//    {
//        $date = Date::find($request->date_id);
//        $date->active = !$date->active;
//        $date->save();
//    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
//        $request->validate();

        /**
         * this data will return with:
         * ::where(start_date > date::tomorrow)->orWhere(date.user_id = $request->user_id)->orderBy(start_date)
         * and if $request->myPrevDates === true we use the orWhere above
         */

        return Date::with('user', 'payment', 'topic')->orderBy('created_at')->get();
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Date $date
     * @return \Illuminate\Http\Response
     */
    public function show(Date $date)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Date $date
     * @return \Illuminate\Http\Response
     */
    public function edit(Date $date)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Date $date
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Date $date)
    {
        if ($request->type === 'toggleStatus') {
            if (User::find($request->user_id)->isAdmin) {
                $date->active = !$date->active;
                $date->save();
                return $date;
            }
        } elseif ($request->type === 'edit') {

        }

        return 'failed';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Date $date
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Date $date, Request $request)
    {
        if (User::find($request->user_id)->isAdmin) {
            if ($date->delete()) {
                return 'deleted';
            };
        }
        return 'failed';
    }
}
