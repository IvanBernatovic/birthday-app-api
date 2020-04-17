<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReminderRequest;
use App\Models\Birthday;
use App\Models\Reminder;

class BirthdayRemindersController extends Controller
{
    public function store(StoreReminderRequest $request, Birthday $birthday)
    {

        $this->authorize('manage-birthday', $birthday);

        $reminder = $birthday->addReminder($request->validated());

        return response()->json($reminder);
    }

    public function delete(Birthday $birthday, Reminder $reminder)
    {
        $this->authorize('manage-birthday', $reminder->remindable);

        $reminder->forceDelete();
    }
}
