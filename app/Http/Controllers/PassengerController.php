<?php

namespace App\Http\Controllers;

use App\Passenger;
use App\Ticket;
use foo\bar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class PassengerController extends Controller
{
    public function getPassenger()
    {
        $passengers = Auth::user()->passengers()->latest()->paginate(5);
        return view('Passenger/all', ['passengers' => $passengers]);
    }

    public function edit($id)
    {
        $passenger = Passenger::find($id);
        return view('Passenger.edit', ['passenger' => $passenger]);
    }

    public function update(Request $request, $id)
    {
//        session()->forget('err');

        $passenger = Passenger::find($id);
        $passenger->update([
            'gender' => $request['gender'],
            'fname' => $request['fname'],
            'lname' => $request['lname'],
            'doc_id' => $request['doc_id'],
            'birthday' => $request['birthday']
        ]);

        return redirect(route('getPassenger'));

    }

    public function Delete($id)
    {
        Passenger::destroy($id);
        Alert::error('حذف شد!', '');
        return back();
    }

    public function getTicket($id)
    {
        $tickets = Ticket::wherePassenger_id($id)->latest()->paginate(1);
        if (count($tickets) == 0) {
            alert()->warning('هیچ بلیتی برای این مسافر وجود ندارد!', '');
            return redirect(route('getPassenger'));
        }
        return view('Passenger.tickets', ['tickets' => $tickets]);
    }

    function  toPersianNum($number)
    {
        $number = str_replace("1","۱",$number);
        $number = str_replace("2","۲",$number);
        $number = str_replace("3","۳",$number);
        $number = str_replace("4","۴",$number);
        $number = str_replace("5","۵",$number);
        $number = str_replace("6","۶",$number);
        $number = str_replace("7","۷",$number);
        $number = str_replace("8","۸",$number);
        $number = str_replace("9","۹",$number);
        $number = str_replace("0","۰",$number);
        return $number;
    }


    public function pastPassenger()
    {
        $passengers = Auth::user()->passengers()->limit(3)->get();

        $modal = '
                                        <div class="modal fade" id="ADTModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">اطلاعات مسافران سابق</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">




                                                    </div>
                                                </div>
                                            </div>
                                        </div>';




        $html='<table class="table table-striped table-hover" id="table">
                                            <thead>
                                            <tr class="small">
                                                <th>#</th>
                                                <th>نوع</th>
                                                <th>جنسیت</th>
                                                <th>نام و نام خانوادگی</th>
                                                <th>کد ملی</th>
                                                <th>تاریخ تولد</th>
                                                <th>قیمت بلیت</th>
                                            </tr>
                                            </thead>
                                            <tbody>';

        $i = 0;
        foreach ($passengers as $passenger) {
            $html .='<tr class=\'rows\'> 
                                                    <td>' .
                $this->toPersianNum(++$i) . '
                                                    </td>
                                                    <td>';
            if ($passenger->type == 'ADT')
                $html .= 'بزرگسال';
            elseif ($passenger->type == 'CHD')
                $html .= 'کودک';
            else
                $html .= 'نوزاد';
            $html.='</td>
                    <td>';
            if ($passenger->gender == 0)
                $html .= '<b>خانم</b>';
            else
                $html .= '<b>آقا</b>';
            $html .= '</td>
                                                    <td>'.$passenger->fname . " " . $passenger->lname .'</td>
                                                    <td class="nowrap">'.$this->toPersianNum($passenger->doc_id).'</td>
                                                    <td>' .$this->toPersianNum($passenger->birthday) . '</td>
                                                    <td>' . $this->toPersianNum($passenger->price) . '</td>
                                                </tr>';
        };
        $html .= '
                                            </tbody>
                                        </table>';
        return ['modal' => $modal , 'html' => $html ];

    }


}





