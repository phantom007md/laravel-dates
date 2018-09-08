<?php

namespace App\Http\Controllers;

use App\Course;
use App\Date;
use App\Discount;
use App\Payment;
use App\Topic;
use App\User;
use Illuminate\Http\Request;
use Mockery\Exception;
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

//        the dsicount scenario will effect the $pay_price var

        $topic_basePrice = Topic::find($request->topic_id)->basePrice;
        $pay_price = floor($request->horses * $topic_basePrice);

        $results = Zarinpal::request(
            route('payments.verify'),
            $pay_price,
            "پرداخت مبلغ $pay_price برای ثبت نوبت خود .",
            $user['email'],
            $user['phone']
        );

        Payment::create([
            'amount' => $pay_price,
            'length' => $request->horses,
            'user_id' => $request->user_id,
            'authority' => $results['Authority'],
            'user_id' => $user['id'],
            'topic_id' => $request->topic_id,
            'start_date' => $request->start_date,
        ]);

//        return Zarinpal::redirect();

        if ($results['Authority']) {
            if (env('ZARINPAL_SANDBOX')) {
                return ['redirect' => 'https://sandbox.zarinpal.com/pg/StartPay/' . $results['Authority']];
            }
            return ['redirect' => 'https://www.zarinpal.com/pg/StartPay/' . $results['Authority']];
        } else {
            return 'failed';
        }
    }

    public function verify(Request $request)
    {
        $payment = Payment::where('authority', $request->Authority)->get()->first();
        $result = Zarinpal::verify('OK', $payment->amount, $request->Authority);
        $payment->status = $result['Status'];
        $payment->RefID = ($result['Status'] === 'success') ? $result['RefID'] : null;
        $payment->save();

        if ($result['Status'] === 'success') {
            Date::create([
                'length' => $payment->length,
                'start_date' => $payment->start_date,
                'topic_id' => $payment->topic_id,
                'user_id' => $payment->user_id,
                'payment_id' => $payment->id,
            ]);
            return redirect('http://localhost:3000?status=ok');
        }
        return redirect('http://localhost:3000?status=failed');


    }
}
