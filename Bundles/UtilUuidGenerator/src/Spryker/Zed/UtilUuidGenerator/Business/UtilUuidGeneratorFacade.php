<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UtilUuidGenerator\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\UtilUuidGenerator\Business\UtilUuidGeneratorBusinessFactory getFactory()
 * @method \Spryker\Zed\UtilUuidGenerator\Persistence\UtilUuidGeneratorEntityManagerInterface getEntityManager()
 */
class UtilUuidGeneratorFacade extends AbstractFacade implements UtilUuidGeneratorFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return int
     */
    public function generateUuids(string $tableName): int
    {
        return $this->getFactory()
            ->createUuidGenerator()
            ->generate($tableName);
    }
}
