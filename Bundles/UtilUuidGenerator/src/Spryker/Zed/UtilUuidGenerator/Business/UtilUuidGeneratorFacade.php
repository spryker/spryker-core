<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UtilUuidGenerator\Business;

use Generated\Shared\Transfer\UuidGeneratorConfigurationTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\UtilUuidGenerator\Business\UtilUuidGeneratorBusinessFactory getFactory()
 * @method \Spryker\Zed\UtilUuidGenerator\Persistence\UtilUuidGeneratorEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\UtilUuidGenerator\Persistence\UtilUuidGeneratorRepositoryInterface getRepository()
 */
class UtilUuidGeneratorFacade extends AbstractFacade implements UtilUuidGeneratorFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UuidGeneratorConfigurationTransfer $uuidGeneratorConfigurationTransfer
     *
     * @return int
     */
    public function generateUuids(UuidGeneratorConfigurationTransfer $uuidGeneratorConfigurationTransfer): int
    {
        return $this->getFactory()
            ->createUuidGenerator()
            ->generate($uuidGeneratorConfigurationTransfer);
    }
}
