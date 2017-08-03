<?php

namespace App\Http\Middleware;

use Closure;

class AdminLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //驗證登錄訊息 > 沒有登入跳轉至登入頁
        if(!session('user')){
            return redirect('admin/login');
        }
        return $next($request);
    }
}
