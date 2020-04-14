<?php

namespace App\Http\Controllers;

use App\Models\Birthday;
use App\Models\Gift;
use Illuminate\Http\Request;

class BirthdayGiftsController extends Controller
{
    /**
     * Add a task to the given project.
     *
     * @param Birthday $birthday
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Birthday $birthday)
    {
        $this->authorize('manage-birthday', $birthday);

        request()->validate(['body' => 'required']);

        $gift = $birthday->addGift(request('body'));

        return response()->json($gift);
    }

    /**
     * Update the project.
     *
     * @param  Birthday $birthday
     * @param  Gift    $gift
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Birthday $birthday, Gift $gift)
    {
        $this->authorize('manage-birthday', $gift->birthday);

        $gift->update(request()->validate(['body' => 'required']));

        return redirect($birthday->path());
    }

    public function delete(Birthday $birthday, Gift $gift)
    {
        $this->authorize('manage-birthday', $gift->birthday);

        $gift->delete();
    }
}
