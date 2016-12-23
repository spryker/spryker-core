<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\ZedRequest\Communication\Plugin\Fixture;

use Spryker\Shared\Transfer\TransferInterface;
use Spryker\Zed\ZedRequest\Business\Client\Request as ClientRequest;

class Request extends ClientRequest
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
