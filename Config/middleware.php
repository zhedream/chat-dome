<?php

return [
    // 全局中间件
    'middleware'=>[

        // 后置中间件  注意后置中间件 必须 提前注册
        \core\Middlewares\After::class,
        \core\Middlewares\After1::class,

        // 前置中间件
        \core\Middlewares\VerifyCsrfToken::class,
        \core\Middlewares\ValidateFromInput::class,

        
    ],
    // 路由中间件
    'routeMiddleware'=>[
        'CheckLogin'=>\app\Middlewares\CheckLogin::class,
        'PassErrTime'=>\app\Middlewares\PassErrTime::class,

    ],
    'middlewareGroups'=>[
        'web'=>[

        ],
        'api'=>[
            
        ]
    ],
    'middlewareMethods'=>[
        'get'=>[

        ],
        'post'=>[

        ]
    ]


];