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
//        i should validate the type with the id getting requested here.
        $discount = Discount::where('code', $request->discount)->get()->first();
        $user = User::find($request->user_id);
        switch ($request->type) {
            case ('course') :
                $course = Course::find($request->course_id);
                $discription = " خرید دوره $course->title ";
                if ($discount && $discount->active) {
                    $pay_price = floor($course->price - ($course->price * $discount->discount / 100));
                } else {
                    $pay_price = $course->price;
                }
                break;
            case ('lesson') :
                $lesson = Course::find($request->lesson_id);
                $discription = " خرید درس $lesson->title ";
                if ($discount && $discount->active) {
                    $pay_price = floor($lesson->price - ($lesson->price * $discount->discount / 100));
                } else {
                    $pay_price = $lesson->price;
                }
                break;
        }


        $results = Zarinpal::request(
            route('payments.verify'),
            $pay_price,
            $discription,
            $user['email']
//                add user phone here
        );

        Payment::create([
            'type' => $request->type,
            'amount' => $pay_price,
            'discount_code' => ($discount) ? $request->discount : null,
            'user_id' => $request->user_id,
            'course_id' => $request->course_id,
            'lesson_id' => $request->lesson_id,
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
            $discount = Discount::where('code', $payment->discount_code)->get()->first();
            if ($discount) {
                $discount->active = false;
                $discount->save();
            }

            switch ($payment->type) {
                case('course') :
                    User::find($payment->user_id)->ownCourses()->attach($payment->course_id);
                    break;
                case('lesson') :
                    User::find($payment->user_id)->ownLessons()->attach($payment->lesson_id);
                    break;
            }
        }

//        redirect with flash messages to a route the flash contains the payment
//        response and the code to show the error msg
        return redirect()->route('academy.home')->with('status', $result['Status']);
    }
}
