<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AdminInvite;
use App\Models\Shop;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    public function getDataDashboard(Request $request)
    {
        if ($request->hasAny('month')) {
            $list = $this->getListDayInMonth($request->month);
            $m = Date('m');
        } else {
            $m = Date('m');
            $list = $this->getListDayInMonth();
        }
        $response = [];
        if (!$request->hasAny('shop_id')) {
            $arrMoney = DB::select('SELECT SUM(totalprice) as totalmoney, DATE(created_at) as day FROM orders WHERE status = ? AND EXTRACT(MONTH FROM created_at) = ? group by day', [3, $m]);
            $totalMoney = 0;
            foreach ($list as $day) {
                $money = 0;
                foreach ($arrMoney as $revenue) {
                    if ($revenue->day == $day) {
                        $money = $revenue->totalmoney;
                        break;
                    }
                }
                $totalMoney += $money;
                $listMoney[] = $money;
            }
            $response['total'] = array_combine($list, $listMoney);
            $response['shop'] = [];
            $shop = Shop::get();
            $item= $shop[0];
            foreach ($shop as $item) {
                $arrMoney = DB::select('SELECT SUM(totalprice) as totalmoney, DATE(created_at) as day FROM orders WHERE status = ? AND shop_id = ?   AND EXTRACT(MONTH FROM created_at) = ? group by day', [3, $item->id, $m]);
                $totalMoney = 0;
                $listMoney = [];
                foreach ($list as $day) {
                    $money = 0;
                    foreach ($arrMoney as $revenue) {
                        if ($revenue->day == $day) {
                            $money = $revenue->totalmoney;
                            break;
                        }
                    }
                    $totalMoney += $money;
                    $listMoney[] = $money;
                }

                // $item['mounth'] = array_combine($list, $listMoney);
                $item['month'] = $listMoney;
                
                array_push($response['shop'], $item);
            }
            return $response;
        } else {
            $arrMoney = DB::select('SELECT SUM(totalprice) as totalmoney, DATE(created_at) as day FROM orders WHERE status = ? AND shop_id = ?  AND EXTRACT(MONTH FROM created_at) = ? group by day', [3, $request->shop_id, $m]);
        }

        $totalMoney = 0;
        foreach ($list as $day) {
            $money = 0;
            foreach ($arrMoney as $revenue) {
                if ($revenue->day == $day) {
                    $money = $revenue->totalmoney;
                    break;
                }
            }
            $totalMoney += $money;
            $listMoney[] = $money;
        }
        return array_combine($list, $listMoney);
        return ($listMoney);

        // logger($list);
        // $email = 'aaa@gmail.com';
        // Mail::to($email)->send( new AdminInvite($email));
    }
    public function getListDayInMonth($month = 12)
    {
        $arrDay = [];
        $year = date('Y');
        for ($day = 1; $day <= 31; $day++) {
            $time = mktime(12, 0, 0, $month, $day, $year);
            if (date('m', $time) == $month) {
                $arrDay[] = date('Y-m-d', $time);
            }
        }
        return $arrDay;
    }

    public function shop()
    {
        $list = $this->getListDayInMonth(date('m'));
        $shops = Shop::select('id')->get()->pluck('id')->toArray();
        return Shop::withCount('order')->with(['order' => function ($q) {
            $q->whereMonth('created_at', Carbon::today()->month);
            $q->sum('totalPrice');
        }])->get();
    }

    public function shopOrder(Request $request)
    {
        $shops = Shop::select('id')->get()->pluck('id')->toArray();
        return Shop::where('id', $request->shop_id)->withCount('order')->with(['order' => function ($q) {
            $q->whereMonth('created_at', Carbon::today()->month);
            $q->sum('totalPrice');
        }])->get();
    }
}
