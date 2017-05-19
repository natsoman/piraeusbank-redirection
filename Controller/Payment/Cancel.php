<?php

namespace Natso\Piraeus\Controller\Payment;

class Cancel extends \Magento\Framework\App\Action\Action
{
    public $context;
    protected $_order;
    protected $_onepage;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Sales\Model\Order $_order,
        \Magento\Checkout\Model\Type\Onepage $_onepage
    ) {
        $this->context   = $context;
        $this->_order    = $_order;
        $this->_onepage = $_onepage;
        parent::__construct($context);
    }

    public function execute()
    {
        try {
            $checkout = $this->_onepage->getCheckout();
            if ($checkout->getLastRealOrderId()) {
                $this->_order->loadByIncrementId($checkout->getLastRealOrderId());
                $this->_order->setState(\Magento\Sales\Model\Order::STATE_CANCELED, true);
                $this->_order->setStatus(\Magento\Sales\Model\Order::STATE_CANCELED);
                foreach ($this->_order->getAllItems() as $item) { // Cancel order items
                    $item->cancel();
                }
                $this->_order->addStatusToHistory($this->_order->getStatus(), 'Payment canceled from client after redirect.');
                $this->_order->save();
                $this->_redirect('checkout/onepage/failure');
            } else {
                $this->_redirect('/');
            }
        } catch (Exception $e) {
            echo $e;
        }
    }
}
