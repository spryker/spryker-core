<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Kernel\Communication\Plugin\Fixture;

use Spryker\Shared\Transfer\TransferInterface;

class Request extends \Spryker\Shared\Library\Communication\Request
{

    /**
     * @var \Spryker\Shared\Transfer\TransferInterface
     */
    private $transfer;

    /**
     * @return \Spryker\Shared\Transfer\TransferInterface
     */
    public function getTransfer()
    {
        if ($this->transfer) {
            return $this->transfer;
        }

        return parent::getTransfer();
    }

    /**
     * @param \Spryker\Shared\Transfer\TransferInterface $transfer
     *
     * @return $this
     */
    public function setTransfer(TransferInterface $transfer)
    {
        $this->transfer = $transfer;

        return $this;
    }

    /**
     * @param \Spryker\Shared\Transfer\TransferInterface $transfer
     *
     * @return void
     */
    public function setFixtureTransfer($transfer)
    {
        $this->transfer = $transfer;
    }

}
