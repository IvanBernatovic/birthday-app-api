<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReminderRequest;
use App\Models\Reminder;
use Auth;
use DB;

class ReminderController extends Controller
{
    public function index()
    {
        $reminders = Auth::user()
            ->reminders()
            ->get();

        return response()->json(['reminders' => $reminders]);
    }

    public function store(StoreReminderRequest $request)
    {
        $reminder = DB::transaction(function () use ($request) {
            return Auth::user()->addReminder($request->validated());
        });

        return response()->json($reminder);
    }

    public function delete(Reminder $reminder)
    {
        $this->authorize('manage-reminder', $reminder);

        $reminder->forceDelete();
    }
}
