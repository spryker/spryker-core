<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Dependency\Facade;

use Psr\Log\LoggerInterface;

class PropelToTransferFacadeBridge implements PropelToTransferFacadeInterface
{
    /**
     * @var \Spryker\Zed\Transfer\Business\TransferFacadeInterface
     */
    private $transferFacade;

    /**
     * @param \Spryker\Zed\Transfer\Business\TransferFacadeInterface $transferFacade
     */
    public function __construct($transferFacade)
    {
        $this->transferFacade = $transferFacade;
    }

    /**
     * @param \Psr\Log\LoggerInterface $messenger
     *
     * @return void
     */
    public function generateEntityTransferObjects(LoggerInterface $messenger)
    {
        $this->transferFacade->generateEntityTransferObjects($messenger);
    }

    /**
     * @return void
     */
    public function deleteGeneratedEntityTransferObjects(): void
    {
        $this->transferFacade->deleteGeneratedEntityTransferObjects();
    }
}
