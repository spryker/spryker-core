<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Uuid\Business;

use Generated\Shared\Transfer\UuidGeneratorConfigurationTransfer;
use Generated\Shared\Transfer\UuidGeneratorReportTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Uuid\Business\UuidBusinessFactory getFactory()
 * @method \Spryker\Zed\Uuid\Persistence\UuidEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\Uuid\Persistence\UuidRepositoryInterface getRepository()
 */
class UuidFacade extends AbstractFacade implements UuidFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UuidGeneratorConfigurationTransfer $uuidGeneratorConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\UuidGeneratorReportTransfer
     */
    public function generateUuids(UuidGeneratorConfigurationTransfer $uuidGeneratorConfigurationTransfer): UuidGeneratorReportTransfer
    {
        return $this->getFactory()
            ->createUuidGenerator()
            ->generate($uuidGeneratorConfigurationTransfer);
    }
}
