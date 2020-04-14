<?php

namespace App\Http\Controllers;

use App\Models\Birthday;
use App\Models\Reminder;
use Illuminate\Http\Request;

class BirthdayRemindersController extends Controller
{
    public function store(Birthday $birthday)
    {

        $this->authorize('manage-birthday', $birthday);

        $data = request()->validate([
            'before_amount' => 'required|integer|min:0|max:30',
            'before_unit' => 'required|integer|in:1,2',
        ]);

        $data['before_amount'] = (int) $data['before_amount'];
        $data['before_unit'] = (int) $data['before_unit'];

        $reminder = $birthday->addReminder($data);

        return response()->json($reminder);
    }

    public function delete(Birthday $birthday, Reminder $reminder)
    {
        $this->authorize('manage-birthday', $reminder->remindable);

        $reminder->delete();
    }
}
