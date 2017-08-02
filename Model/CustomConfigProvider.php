<?php

namespace Natso\Piraeus\Model;

use Magento\Checkout\Model\ConfigProviderInterface;

class CustomConfigProvider implements ConfigProviderInterface
{

    protected $_helper;

    public function __construct(
        \Natso\Piraeus\Helper\Data $helper
    ) {
        $this->_helper = $helper;
    }

    public function getConfig()
    {
        $config = [
            'payment' => [
                'piraeus' => [
                    'installments' => $this->_helper->getAvailableInstallments()
                ]
            ]
        ];
        return $config;
    }
}