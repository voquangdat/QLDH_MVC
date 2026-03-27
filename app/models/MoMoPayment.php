<?php
// app/models/MoMoPayment.php

class MoMoPayment {
    // Thông tin test MoMo
    private $partnerCode = 'MOMOBKUN20180529';
    private $accessKey = 'klm05TvNBzhg7h7j';
    private $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
    private $endpoint = 'https://test-payment.momo.vn/v2/gateway/api/create';
    
    // URL callback - cần thay đổi theo domain thực tế
    private $returnUrl;
    private $notifyUrl;
    
    public function __construct() {
        // Lấy base URL động
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $baseUrl = $protocol . '://' . $host;
        
        $this->returnUrl = $baseUrl . '/index.php?page=payment&action=momo_return';
        $this->notifyUrl = $baseUrl . '/index.php?page=payment&action=momo_notify';
    }
    
    /**
     * Tạo yêu cầu thanh toán MoMo
     */
    public function createPayment($orderId, $amount, $orderInfo) {
        // Tạo requestId unique
        $requestId = time() . "";
        $extraData = "";
        $requestType = "payWithATM"; // hoặc 'captureWallet'
        
        // Tạo raw signature
        $rawHash = "accessKey=" . $this->accessKey . 
                   "&amount=" . $amount . 
                   "&extraData=" . $extraData . 
                   "&ipnUrl=" . $this->notifyUrl . 
                   "&orderId=" . $orderId . 
                   "&orderInfo=" . $orderInfo . 
                   "&partnerCode=" . $this->partnerCode . 
                   "&redirectUrl=" . $this->returnUrl . 
                   "&requestId=" . $requestId . 
                   "&requestType=" . $requestType;
        
        // Tạo signature
        $signature = hash_hmac("sha256", $rawHash, $this->secretKey);
        
        // Dữ liệu gửi đi
        $data = [
            'partnerCode' => $this->partnerCode,
            'partnerName' => "VoxFootball",
            'storeId' => "VoxFootballStore",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $this->returnUrl,
            'ipnUrl' => $this->notifyUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature
        ];
        
        // Log request để debug
        error_log("MoMo Request: " . json_encode($data));
        
        // Gửi request đến MoMo
        $result = $this->execPostRequest($this->endpoint, json_encode($data));
        $jsonResult = json_decode($result, true);
        
        // Log response
        error_log("MoMo Response: " . $result);
        
        return $jsonResult;
    }
    
    /**
     * Xác thực signature từ MoMo trả về
     */
    public function verifySignature($data) {
        // Kiểm tra các field bắt buộc
        $requiredFields = ['accessKey', 'amount', 'extraData', 'message', 'orderId', 
                          'orderInfo', 'orderType', 'partnerCode', 'payType', 
                          'requestId', 'responseTime', 'resultCode', 'transId', 'signature'];
        
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                error_log("Missing field in MoMo response: " . $field);
                return false;
            }
        }
        
        // Tạo raw hash để so sánh
        $rawHash = "accessKey=" . $this->accessKey .
                   "&amount=" . $data['amount'] .
                   "&extraData=" . $data['extraData'] .
                   "&message=" . $data['message'] .
                   "&orderId=" . $data['orderId'] .
                   "&orderInfo=" . $data['orderInfo'] .
                   "&orderType=" . $data['orderType'] .
                   "&partnerCode=" . $data['partnerCode'] .
                   "&payType=" . $data['payType'] .
                   "&requestId=" . $data['requestId'] .
                   "&responseTime=" . $data['responseTime'] .
                   "&resultCode=" . $data['resultCode'] .
                   "&transId=" . $data['transId'];
        
        $signature = hash_hmac("sha256", $rawHash, $this->secretKey);
        
        $isValid = ($signature === $data['signature']);
        
        if (!$isValid) {
            error_log("Signature verification failed. Expected: $signature, Got: " . $data['signature']);
        }
        
        return $isValid;
    }
    
    /**
     * Gửi POST request
     */
    private function execPostRequest($url, $data) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        
        $result = curl_exec($ch);
        
        if (curl_errno($ch)) {
            error_log('Curl error: ' . curl_error($ch));
            $result = json_encode(['resultCode' => 99, 'message' => 'Lỗi kết nối: ' . curl_error($ch)]);
        }
        
        curl_close($ch);
        
        return $result;
    }
    
    /**
     * Lấy thông báo lỗi từ resultCode
     */
    public function getErrorMessage($resultCode) {
        $messages = [
            0 => 'Giao dịch thành công',
            9000 => 'Giao dịch đã được xác nhận thành công',
            1000 => 'Giao dịch đã được khởi tạo, chờ người dùng xác nhận thanh toán',
            1001 => 'Giao dịch thất bại do tài khoản người dùng không đủ tiền',
            1002 => 'Giao dịch bị từ chối bởi nhà phát hành tài khoản người dùng',
            1003 => 'Giao dịch bị hủy',
            1004 => 'Giao dịch thất bại do số tiền thanh toán vượt quá hạn mức thanh toán',
            1005 => 'Giao dịch thất bại do url hoặc QR code đã hết hạn',
            1006 => 'Giao dịch thất bại do người dùng từ chối xác nhận thanh toán',
            1007 => 'Giao dịch bị từ chối vì tài khoản người dùng đang ở trạng thái tạm khóa',
            1026 => 'Giao dịch bị hạn chế theo thể lệ chương trình khuyến mãi',
            1080 => 'Giao dịch hoàn tiền bị từ chối. Người dùng đã hủy giao dịch hoàn tiền',
            1081 => 'Giao dịch hoàn tiền bị hủy do thời gian xử lý quá hạn',
            2001 => 'Giao dịch thất bại do sai thông tin',
            3001 => 'Giao dịch bị từ chối vì người dùng chưa xác thực danh tính',
            3002 => 'Giao dịch bị từ chối vì merchant không đúng',
            3003 => 'Giao dịch bị từ chối vì dữ liệu không đúng',
            3004 => 'Giao dịch bị từ chối vì merchant chưa được kích hoạt',
            4001 => 'Giao dịch thất bại do không đủ tiền',
            4010 => 'Giao dịch bị từ chối vì merchant đã đạt hạn mức giao dịch trong ngày',
            4011 => 'Giao dịch bị từ chối vì người dùng đã đạt hạn mức giao dịch trong ngày',
            4100 => 'Giao dịch thất bại do người dùng từ chối thanh toán',
            10 => 'Hệ thống đang được bảo trì',
            99 => 'Lỗi không xác định'
        ];
        
        return $messages[$resultCode] ?? 'Lỗi không xác định';
    }
}
?>