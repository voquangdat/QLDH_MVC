<?php
// config/momo.php

return [
    // Thông tin test từ MoMo
    'partner_code' => 'MOMOBKUN20180529',
    'access_key' => 'klm05TvNBzhg7h7j',
    'secret_key' => 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa',
    
    // URL môi trường test
    'endpoint' => 'https://test-payment.momo.vn/v2/gateway/api/create',
    
    // URL callback - thay đổi theo domain của bạn
    'return_url' => 'http://localhost/your-project/payment/return',
    'notify_url' => 'http://localhost/your-project/payment/notify',
    
    // Cấu hình khác
    'request_type' => 'payWithATM', // hoặc 'captureWallet'
];