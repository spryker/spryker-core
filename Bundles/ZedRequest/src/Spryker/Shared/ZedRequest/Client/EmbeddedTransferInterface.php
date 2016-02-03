<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\ZedRequest\Client;

use Spryker\Shared\Transfer\TransferInterface;

interface EmbeddedTransferInterface
{

    /**
     * @param \Spryker\Shared\Transfer\TransferInterface $transferObject
     *
     * @return self
     */
    public function setTransfer(TransferInterface $transferObject);

    /**
     * @return \Spryker\Shared\Transfer\TransferInterface
     */
    public function getTransfer();

}
