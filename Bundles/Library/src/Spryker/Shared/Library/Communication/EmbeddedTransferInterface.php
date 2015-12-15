<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Library\Communication;

use Spryker\Shared\Transfer\TransferInterface;

interface EmbeddedTransferInterface
{

    /**
     * @param TransferInterface $transferObject
     *
     * @return self
     */
    public function setTransfer(TransferInterface $transferObject);

    /**
     * @return TransferInterface
     */
    public function getTransfer();

}
