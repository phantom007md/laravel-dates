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
use function response;
use function session;
use Zarinpal\Laravel\Facade\Zarinpal;

class PaymentController extends Controller
{
    public function calcPayPrice($code, $price)
    {
        if ($discount = Discount::where('code', $code)->get()->first()) {
            $pay_price = $discount->calc($price);
            if ($pay_price) return $pay_price;
        }
        return $pay_price = $price;
    }

    public function index()
    {
//        return Payment::all();
    }

    public function pay(Request $request)
    {
        $request->validate([
            'user_id' => 'integer|required',
            'topic_id' => 'integer|required',
            'horses' => 'integer|required',
            'uri' => 'required',
//            'start_date' => 'required',
        ]);

        $user = User::find($request->user_id);


        $topic_basePrice = Topic::find($request->topic_id)->basePrice;
        $price = floor($request->horses * $topic_basePrice);
        $pay_price = $this->calcPayPrice($request->discount, $price);

        session(['uri' => $request->uri]);

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
            'discount' => $request->discount,
            'authority' => $results['Authority'],
            'user_id' => $user['id'],
            'topic_id' => $request->topic_id,
            'dateTime' => $request->dateTime,
        ]);

//        return Zarinpal::redirect();

        if ($results['Authority']) {
            if (env('ZARINPAL_SANDBOX')) {
                return ['redirect' => 'https://sandbox.zarinpal.com/pg/StartPay/' . $results['Authority']];
            }
            return ['redirect' => 'https://www.zarinpal.com/pg/StartPay/' . $results['Authority']];
        } else {
            return ['redirect' => session('uri') . '?status=failed'];
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
                'dateTime' => $payment->dateTime,
                'topic_id' => $payment->topic_id,
                'user_id' => $payment->user_id,
                'payment_id' => $payment->id,
            ]);
            if ($discount = Discount::where('code', $payment->discount)->get()->first()) {
                $discount->use();
            };
            return redirect(session('uri') . "?status=ok");
        }
        return redirect(session('uri') . "?status=failed");
    }
}
