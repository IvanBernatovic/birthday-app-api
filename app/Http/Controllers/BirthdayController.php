<?php

namespace App\Http\Controllers;

use App\Models\Birthday;
use Auth;
use Illuminate\Http\Request;

class BirthdayController extends Controller
{
    public function index()
    {
        $birthdays = Auth::user()
            ->birthdays()
            ->with('gifts', 'reminders')
            ->get();

        return response()->json(['birthdays' => $birthdays]);
    }

    public function store()
    {
        $data = $this->validate(request(), [
            'name' => 'required|min:2',
            'date' => 'required|date',
        ]);

        $birthday = auth()->user()->birthdays()->create($data);

        return response()->json($birthday->load('gifts', 'reminders')->refresh());
    }

    public function update(Birthday $birthday)
    {
        $this->authorize('manage-birthday', $birthday);
        $data = $this->validate(request(), [
            'name' => 'required|min:2',
            'date' => 'required|date',
        ]);

        $birthday->update($data);

        return response()->json($birthday);
    }

    public function delete(Birthday $birthday)
    {
        $this->authorize('manage-birthday', $birthday);
        $birthday->delete();

        return response()->json();
    }
}
