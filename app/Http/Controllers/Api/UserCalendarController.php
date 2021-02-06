<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\UserCalendar;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Calendar\Request as CalendarRequest;

class UserCalendarController extends Controller
{
    /**
     * Store or update a resource in storage.
     *
     * @param  \App\Http\Requests\Calendar\Request  $request
     * @param  \App\Models\UserCalendar $calendar
     * 
     * @return \Flugg\Responder\Facades\Responder 
     */
    public function save(CalendarRequest $request, UserCalendar $calendar)
    {
        DB::beginTransaction();
        
        try {
            $userId = auth()->user()->user_id;
            $input = $request->all();

            $input['user_id'] = $userId;

            $newCalendar = $calendar->updateOrCreate(
                ['user_id' => $userId],
                $input
            );

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            return responder()->error($e->getMessage())
                              ->respond(500);
        }

        return responder()->success($newCalendar);
    }
}
