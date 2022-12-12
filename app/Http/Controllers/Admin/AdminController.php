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
    public function getDataDashboard()
    {
        $list = $this->getListDayInMonth();

        // $arrMoney =DB::select('SELECT SUM(totalprice) as totalmoney, DATE(created_at) as day FROM orders WHERE status = ? AND shop_id = ? AND EXTRACT(MONTH FROM created_at) = ? group by day', [0, 1, Date('m')]);
        $arrMoney =DB::select('SELECT SUM(totalprice) as totalmoney, DATE(created_at) as day FROM orders WHERE status = ? AND shop_id = ? AND EXTRACT(MONTH FROM created_at) = ? group by day', [0, 1, Date('m')]);

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
    public function getListDayInMonth()
    {
        $arrDay = [];
        $month = date('m');
        $year = date('Y');
        for ($day = 1; $day <= 31; $day++) {
            $time = mktime(12, 0, 0, $month, $day, $year);
            if (date('m', $time) == $month) {
                $arrDay[] = date('Y-m-d', $time);
            }
        }
        return $arrDay;
    }

    public function shop() {
        $list = $this->getListDayInMonth();
        $shops = Shop::select('id')->get()->pluck('id')->toArray();
        return Shop::withCount('order')->with(['order' => function($q) {
            $q->whereMonth('created_at', Carbon::today()->month);
            $q->sum('totalPrice');
        }])->get();
    }
}
