<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UtilUuidGenerator\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\UtilUuidGenerator\Persistence\Propel\Builder\QueryBuilder;
use Spryker\Zed\UtilUuidGenerator\Persistence\Propel\Builder\QueryBuilderInterface;

/**
 * @method \Spryker\Zed\UtilUuidGenerator\Persistence\UtilUuidGeneratorEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\UtilUuidGenerator\UtilUuidGeneratorConfig getConfig()
 * @method \Spryker\Zed\UtilUuidGenerator\Persistence\UtilUuidGeneratorRepositoryInterface getRepository()
 */
class UtilUuidGeneratorPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Spryker\Zed\UtilUuidGenerator\Persistence\Propel\Builder\QueryBuilderInterface
     */
    public function createQueryBuilder(): QueryBuilderInterface
    {
        return new QueryBuilder();
    }
}
