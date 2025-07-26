<?php
 
namespace App\Services\Midtrans;
 
use Midtrans\Snap;
 
class CreateSnapTokenService extends Midtrans
{
    protected $order;
 
    public function __construct($order)
    {
        parent::__construct();
 
        $this->order = $order;
    }
 
    public function getSnapToken()
    {
        $params = [
            'transaction_details' => [
                'order_id' => $this->order['nomor'],
                'gross_amount' => $this->order['jumlah'],
            ],
            'item_details' => [
                [
                    'id' => $this->order['id'],
                    'price' => $this->order['jumlah'],
                    'quantity' => 1,
                    'name' => 'Sewa Lapangan',
                ],
            ],
            'customer_details' => [
                'first_name' => $this->order['pelanggan_nama'],
                'email' => $this->order['pelanggan_email'],
                'phone' => $this->order['pelanggan_phone'],
            ]
        ];
 
        $snapToken = Snap::getSnapToken($params);
 
        return $snapToken;
    }
}