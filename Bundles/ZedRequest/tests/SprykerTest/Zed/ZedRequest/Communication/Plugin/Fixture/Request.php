<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ZedRequest\Communication\Plugin\Fixture;

use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\ZedRequest\Business\Client\Request as ClientRequest;

class Request extends ClientRequest
{
    /**
     * @var \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    private $transfer;

    /**
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function getTransfer(): TransferInterface
    {
        if ($this->transfer) {
            return $this->transfer;
        }

        return parent::getTransfer();
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $transfer
     *
     * @return $this
     */
    public function setTransfer(TransferInterface $transfer)
    {
        $this->transfer = $transfer;

        return $this;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $transfer
     *
     * @return void
     */
    public function setFixtureTransfer(TransferInterface $transfer): void
    {
        $this->transfer = $transfer;
    }
}
