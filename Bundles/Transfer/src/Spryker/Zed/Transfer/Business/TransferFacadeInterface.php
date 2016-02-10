<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Transfer\Business;

use Psr\Log\LoggerInterface;

interface TransferFacadeInterface
{

    /**
     * @param \Psr\Log\LoggerInterface $messenger
     *
     * @return void
     */
    public function generateTransferObjects(LoggerInterface $messenger);

    /**
     * @return void
     */
    public function deleteGeneratedTransferObjects();

}
