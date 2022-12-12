<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listUse()
    {
        return Voucher::where('start', '<=', Carbon::today())->where('end', '>=', Carbon::today())->get();
    }
    public function index()
    {
        return Voucher::orderBy('id','desc')->get();
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    public function store(Request $request)
    {
        return Voucher::create($request->all());
    }

    public function show($id)
    {
        return Voucher::findOrFail($id);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $voucher = Voucher::findOrFail($id)->update($request->all());
        return $voucher;
    }

    public function destroy($id)
    {
        return Voucher::findOrFail($id)->delete();
    }
}
