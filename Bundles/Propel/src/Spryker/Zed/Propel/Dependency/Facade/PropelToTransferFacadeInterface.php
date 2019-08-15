<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Dependency\Facade;

use Psr\Log\LoggerInterface;

interface PropelToTransferFacadeInterface
{
    /**
     * @param \Psr\Log\LoggerInterface $messenger
     *
     * @return void
     */
    public function generateEntityTransferObjects(LoggerInterface $messenger);

    /**
     * @return void
     */
    public function deleteGeneratedEntityTransferObjects(): void;
}
