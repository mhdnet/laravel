<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Invite;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class InviteController extends Controller
{
    public function accept(Request $request, Invite $invite)
    {
    }

    public function invite(Request $request, Invite $invite)
    {
        return view('invitations.message');

//        \Auth::logout();
//        \Auth::login(Client::find(13));
//        if (($user = $request->user()) && $user = Client::find($user->id)) {
//
//            return view('auth.message', ['message' => "تهانينا! \n  تم قبول الدعوة"]);
//            if ($user->email == $invite->email || $user->phone == $invite->phone) {
//                $user->accounts()->attach($invite->account_id, ['role' => $invite->role]);
//                $invite->delete();
//                return "Accepted view.";
//            }
//            else
//                 abort(404);
//        }
        $client = Client::where(function (Builder $query) use($invite) {
            if($invite->email)
                $query->where('email', $invite->email);
            elseif ($invite->phone)
                $query->where('phone', $invite->phone);
            else
                $query->whereNull('id');
        })->first();



        return view('invitations.form', ['invite' => $invite, 'client' => $client]);
    }


}
