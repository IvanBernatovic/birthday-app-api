<?php

namespace App\Http\Controllers;

use App\Models\Birthday;
use Illuminate\Http\Request;

class BirthdayController extends Controller
{
    public function index()
    {
        $birthdays = auth()
            ->user()
            ->birthdays()
            ->get();

        return response()->json(['birthdays' => $birthdays]);
    }

    public function store()
    {
        $data = $this->validate(request(), [
            'name' => 'required|min:2',
            'date' => 'required|date'
        ]);

        $birthday = auth()->user()->birthdays()->create($data);

        return response()->json($birthday);
    }

    public function update(Birthday $birthday)
    {

    }

    public function delete(Birthday $birthday)
    {
        return $birthday->delete();
    }
}
