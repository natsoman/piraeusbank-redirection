<?php

namespace Natso\Piraeus\Block\Payment;

class Redirect extends \Magento\Framework\View\Element\Template
{
    public      $customerSession;
    public      $logger;
    protected   $_helper;

	public function __construct(
        \Magento\Framework\View\Element\Template\Context    $context,
        \Magento\Customer\Model\Session                     $customerSession,
        \Natso\Piraeus\Helper\Data                          $_helper,
        \Psr\Log\LoggerInterface                            $logger,
        array                                               $data = []
        )
	{
        parent::__construct($context, $data);
        $this->customerSession  = $customerSession;
        $this->_helper          = $_helper;
        $this->logger           = $logger;
	}

	public function generateTicket()
	{
        try {
            $soap       = new \Zend\Soap\Client($this->_helper->getTicketUrl());
            $xml        = array('Request' => $this->_helper->getTicketData());
            $response   = $soap->IssueNewTicket($xml);
            $this->logger->debug(print_r($xml, true));
            $this->logger->debug(print_r($response, true));
        }
        catch(Exception $e) {
            //echo $e;
        }
	}

    public function getPostData(){
        return $this->_helper->getPostData();
    }

    public function getPostUrl(){
        return $this->_helper->getPostUrl();
    }
}
