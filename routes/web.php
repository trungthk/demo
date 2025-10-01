<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);
Route::get('/test', [HomeController::class, 'test']);
Route::get('/user', [UserController::class, 'index']);
Route::get('user/{user}', [UserController::class, 'show']);
Route::get('collection-grabage', function() {
    // $data = [];
    // for ($i = 0; $i < 300000; $i++) {
    //     $data[] = str_repeat("A", 1000); // mỗi phần tử ~1KB
    // }
    // echo "Memory usage: " . memory_get_usage(true) . "\n";
    // echo "Peak usage: " . memory_get_peak_usage(true) . "\n";
    // gc_collect_cycles();
    // // unset($data); // giải phóng bộ nhớ
    // echo "Memory usage after unset: " . memory_get_usage(true) . "\n";
    // echo "Peak usage after unset: " . memory_get_peak_usage(true) . "\n";
    // Dùng SplFixed
    $fixedArray = new SplFixedArray(300000);
    for ($i = 0; $i < 300000; $i++) {
        $fixedArray[$i] = str_repeat("A", 1000); // mỗi phần tử ~1KB
    }
    echo json_encode($fixedArray[0]);
    echo "Memory usage with SplFixedArray: " . memory_get_usage(true) . "\n";
    echo "Peak usage with SplFixedArray: " . memory_get_peak_usage(true) . "\n";
    return "Done";
});