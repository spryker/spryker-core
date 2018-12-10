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
 * @method \Spryker\Zed\UtilUuidGenerator\Persistence\UtilUuidGeneratorRepositoryInterface getRepository()
 */
class UtilUuidGeneratorFacade extends AbstractFacade implements UtilUuidGeneratorFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $tableAlias
     *
     * @return int
     */
    public function generateUuids(string $tableAlias): int
    {
        return $this->getFactory()
            ->createUuidGenerator()
            ->generate($tableAlias);
    }
}
