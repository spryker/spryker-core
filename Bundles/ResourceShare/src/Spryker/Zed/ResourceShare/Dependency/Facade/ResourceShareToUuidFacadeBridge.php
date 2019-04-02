<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ResourceShare\Dependency\Facade;

use Generated\Shared\Transfer\UuidGeneratorConfigurationTransfer;
use Generated\Shared\Transfer\UuidGeneratorReportTransfer;

class ResourceShareToUuidFacadeBridge implements ResourceShareToUuidFacadeInterface
{
    /**
     * @var \Spryker\Zed\Uuid\Business\UuidFacadeInterface
     */
    protected $uuidFacade;

    /**
     * @param \Spryker\Zed\Uuid\Business\UuidFacadeInterface $uuidFacade
     */
    public function __construct($uuidFacade)
    {
        $this->uuidFacade = $uuidFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\UuidGeneratorConfigurationTransfer $uuidGeneratorConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\UuidGeneratorReportTransfer
     */
    public function generateUuids(UuidGeneratorConfigurationTransfer $uuidGeneratorConfigurationTransfer): UuidGeneratorReportTransfer
    {
        return $this->uuidFacade->generateUuids($uuidGeneratorConfigurationTransfer);
    }
}
