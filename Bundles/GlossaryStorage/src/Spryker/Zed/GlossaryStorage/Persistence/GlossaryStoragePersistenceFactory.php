<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GlossaryStorage\Persistence;

use Orm\Zed\GlossaryStorage\Persistence\SpyGlossaryStorageQuery;
use Spryker\Zed\GlossaryStorage\GlossaryStorageDependencyProvider;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\GlossaryStorage\GlossaryStorageConfig getConfig()
 * @method \Spryker\Zed\GlossaryStorage\Persistence\GlossaryStorageQueryContainer getQueryContainer()
 */
class GlossaryStoragePersistenceFactory extends AbstractPersistenceFactory
{

    /**
     * @return \Orm\Zed\GlossaryStorage\Persistence\SpyGlossaryStorageQuery
     */
    public function createGlossaryStorageQuery()
    {
        return SpyGlossaryStorageQuery::create();
    }

    /**
     * @return \Spryker\Zed\GlossaryStorage\Dependency\QueryContainer\GlossaryStorageToGlossaryQueryContainerInterface
     */
    public function getGlossaryQueryContainer()
    {
        return $this->getProvidedDependency(GlossaryStorageDependencyProvider::QUERY_CONTAINER_GLOSSARY);
    }

}
