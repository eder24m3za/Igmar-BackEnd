<?php

namespace App\Http\Controllers;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Jobs\MandarEmail;

use Illuminate\Http\Request;

class JobsController extends Controller
{
    public function jobs(Request $request)
    {
        MandarEmail::dispatch($request->info)
        ->onQueue("email");
        return response()->json([
           "ejecutando queue"
       ],200);
    }
}
