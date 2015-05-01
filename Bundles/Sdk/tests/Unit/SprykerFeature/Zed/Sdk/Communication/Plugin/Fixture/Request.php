<?php

namespace Unit\SprykerFeature\Zed\Sdk\Communication\Plugin\Fixture;

use SprykerFeature\Shared\Library\TransferObject\TransferInterface;

class Request extends \SprykerFeature\Shared\Library\Communication\Request
{
    private $transfer;

    public function getTransfer()
    {
        if ($this->transfer) {
            return $this->transfer;
        }

        return parent::getTransfer();
    }

    public function setTransfer(TransferInterface $transfer)
    {
        $this->transfer = $transfer;
    }
}
 