<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MomoService
{
    protected $config;

    public function __construct()
    {
        $this->config = config('momo');
    }

    /**
     * Tạo yêu cầu thanh toán MOMO
     */
    public function createPaymentRequest($orderId, $amount, $orderInfo = null)
    {
        $orderInfo = $orderInfo ?: "Thanh toan don hang $orderId";
        $partnerCode = $this->config['partner_code'];
        $accessKey   = $this->config['access_key'];
        $secretKey   = $this->config['secret_key'];
        $endpoint    = $this->config['endpoint'];
        $returnUrl   = $this->config['redirect_url'];
        $notifyUrl   = $this->config['notify_url'];
        $requestId = $partnerCode . time();
        $requestType = 'payWithATM';
        $extraData = '';
        $rawSignature = "accessKey=$accessKey&amount=$amount&extraData=$extraData&ipnUrl=$notifyUrl&orderId=$orderId&orderInfo=$orderInfo&partnerCode=$partnerCode&redirectUrl=$returnUrl&requestId=$requestId&requestType=$requestType";
        $signature = hash_hmac('sha256', $rawSignature, $secretKey);
        $payload = [
            'partnerCode' => $partnerCode,
            'partnerName' => 'Test Store',
            'storeId' => $partnerCode,
            'requestId' => $requestId,
            'amount' => (string)$amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $returnUrl, 
            'ipnUrl' => $notifyUrl,  
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature
        ];
        try {
            $response = Http::timeout(30)->post($endpoint, $payload);
            $result = $response->json();
            if (isset($result['resultCode']) && $result['resultCode'] == 0) {
                return [
                    'success' => true,
                    'payUrl' => $result['payUrl'],
                    'requestId' => $requestId
                ];
            }
            return [
                'success' => false,
                'message' => $result['message'] ?? 'Lỗi MoMo'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Exception: ' . $e->getMessage()
            ];
        }
    }
}
