<?php

namespace Natso\Piraeus\Model;

class Vpos extends \Magento\Payment\Model\Method\AbstractMethod
{
    protected $_code = 'piraeus';
    protected $_isOffline = true;
    protected $_isInitializeNeeded = true;
}