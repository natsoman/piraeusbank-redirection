<?php

namespace Natso\Piraeus\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    public $scopeConfig;
    public $order;
    public $cart;
    protected $_objectManager;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Sales\Api\Data\OrderInterface $order,
        \Magento\Checkout\Model\Cart $cart,
        \Magento\Framework\ObjectManagerInterface $_objectManager
    ) {
        $this->order            = $order;
        $this->cart             = $cart;
        $this->scopeConfig      = $scopeConfig;
        $this->_objectManager   = $_objectManager;
    }

    public function getTicketData()
    {
        $checkout = $this->_objectManager->get('Magento\Checkout\Model\Type\Onepage')->getCheckout();

        $this->order->loadByIncrementId($checkout->getLastRealOrderId());

        $acquirerId = $this->scopeConfig->getValue(
            'payment/piraeus/acquirer_id',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        $merchantId = $this->scopeConfig->getValue(
            'payment/piraeus/merchant_id',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        $posId = $this->scopeConfig->getValue(
            'payment/piraeus/pos_id',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        $username = $this->scopeConfig->getValue(
            'payment/piraeus/username',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        $password = $this->scopeConfig->getValue(
            'payment/piraeus/password',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        $requestType = $this->scopeConfig->getValue(
            'payment/piraeus/request_type',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        $expirePreauth = $this->scopeConfig->getValue(
            'payment/piraeus/expire_preauth',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        $ai = $this->order->getPayment()->getAdditionalInformation();

        if ( isset($ai['installments']) ) {
            $orderInstallments = $ai['installments'];
        } else {
            $orderInstallments = '';
        }

        $ticketRequest = array(
            'AcquirerId'        => $acquirerId,
            'MerchantId'        => $merchantId,
            'PosId'             => $posId,
            'Username'          => $username,
            'Password'          => hash('md5', $password),
            'RequestType'       => $requestType,
            'CurrencyCode'      => '978',
            'MerchantReference' => $checkout->getLastRealOrderId(),
            'Amount'            => round($this->order->getData('base_grand_total'), 2),
            'Installments'      => $orderInstallments,
            'ExpirePreauth'     => $expirePreauth,
            'Bnpl'              => '',
            'Parameters'        => ''
        );

        return $ticketRequest;
    }

    public function getPostData()
    {
        $checkout = $this->_objectManager->get('Magento\Checkout\Model\Type\Onepage')->getCheckout();

        $acquirerId = $this->scopeConfig->getValue(
            'payment/piraeus/acquirer_id',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        $merchantId = $this->scopeConfig->getValue(
            'payment/piraeus/merchant_id',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        $posId = $this->scopeConfig->getValue(
            'payment/piraeus/pos_id',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        $username = $this->scopeConfig->getValue(
            'payment/piraeus/username',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        $postData = array(
            'AcquirerId'        => $acquirerId,
            'MerchantId'        => $merchantId,
            'PosId'             => $posId,
            'User'              => $username,
            'MerchantReference' => $checkout->getLastRealOrderId(),
            'LanguageCode'      => 'el-GR',
            'Parameters'        => ''
        );

        return $postData;
    }

    public function getInstallments()
    {
        return $this->scopeConfig->getValue(
            'payment/piraeus/installments',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getAvailableInstallments() {
        $available = array();
        $installments = $this->getInstallments();
        $bgt = $this->cart->getQuote()->getData('base_grand_total');
        $installments = explode(";",$installments);
        foreach ($installments as $inst) {
            $inst = explode(":",$inst);
            if ($inst[0] <= $bgt) {
                array_push($available, $inst[1]);
            }
        }
        return $available;
    }

    public function getTicketUrl()
    {
        return $this->scopeConfig->getValue(
            'payment/piraeus/ticket_url',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getPostUrl()
    {
        return $this->scopeConfig->getValue(
            'payment/piraeus/post_url',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}
