<?php
namespace SprykerFeature\Shared\ZedRequest\Client;

use SprykerFeature\Shared\Library\TransferObject\TransferInterface;

interface EmbeddedTransferInterface
{
    /**
     * @param TransferInterface $transferObject
     * @return $this
     */
    public function setTransfer(TransferInterface $transferObject);

    /**
     * @return TransferInterface
     */
    public function getTransfer();
}
