<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        //检查是否进入维护模式
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        //检查请求的数据量是否过大
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        //对提交的请求参数进行php函数'trim'处理
        \App\Http\Middleware\TrimStrings::class,
        //将请求参数中空字符串转换为null
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        // 修正代理服务器后的服务器参数
        \App\Http\Middleware\TrustProxies::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            //cookie加密解密
            \App\Http\Middleware\EncryptCookies::class,
            //将cookie添加到x响应中
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            //开启会话
            \Illuminate\Session\Middleware\StartSession::class,
            //认证用户, 此中间件以后Auth类才会生效
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            // 将系统的错误数据注入到视图变量 $errors 中
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            // 检验 CSRF ，防止跨站请求伪造的安全威胁
            \App\Http\Middleware\VerifyCsrfToken::class,
            // 处理路由绑定
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            //记录用户最后活跃时间
            \App\Http\Middleware\RecordLastActivedTime::class
        ],

        'api' => [
            'throttle:60,1',
            'bindings',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        // 接口语言设置
        'change-locale' => \App\Http\Middleware\ChangeLocal::class,
    ];
}
