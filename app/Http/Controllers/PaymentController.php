<?php

namespace App\Http\Controllers;

use App\Course;
use App\Discount;
use App\Payment;
use App\User;
use Illuminate\Http\Request;
use function redirect;
use Zarinpal\Laravel\Facade\Zarinpal;

class PaymentController extends Controller
{
    public function index()
    {
        return Payment::all();
    }

    public function pay(Request $request)
    {
        $user = User::find($request->user_id);

        $results = Zarinpal::request(
            route('payments.verify'),
            $pay_price,
            $user['email'],
            $user['phone']
        );

        Payment::create([
            'amount' => $pay_price,
            'user_id' => $request->user_id,
            'authority' => $results['Authority'],
        ]);

        return Zarinpal::redirect();

    }

    public function verify(Request $request)
    {
        $payment = Payment::where('authority', $request->Authority)->get()->first();
        $result = Zarinpal::verify('OK', $payment->amount, $request->Authority);
        $payment->status = $result['Status'];
        $payment->RefID = ($result['Status'] === 'success') ? $result['RefID'] : null;
        $payment->save();

        if ($result['Status'] === 'success') {
            User::find($payment->user_id)->dates()->save([
                'length' => $request,
            ]);
        }

//        redirect with flash messages to a route the flash contains the payment
//        response and the code to show the error msg
        return redirect()->route('academy.home')->with('status', $result['Status']);
    }
}
