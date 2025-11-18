<?php

return [
    'partner_code' => 'MOMOBKUN20180529',
    'access_key'   => 'klm05TvNBzhg7h7j',
    'secret_key'   => 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa',
    'endpoint'     => 'https://test-payment.momo.vn/v2/gateway/api/create',
    // 'redirect_url' => 'https://strainingly-ahull-debbie.ngrok-free.dev/momo/return',
    // 'notify_url'   => 'https://strainingly-ahull-debbie.ngrok-free.dev/momo/notify',
    'notify_url' => "http://localhost:8000/momo/notify",
    'redirect_url' => 'http://localhost:8000/momo/return',
];
