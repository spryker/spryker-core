<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GlossaryStorage\Persistence;

use Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery;
use Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery;
use Orm\Zed\GlossaryStorage\Persistence\SpyGlossaryStorageQuery;
use Spryker\Zed\GlossaryStorage\GlossaryStorageDependencyProvider;
use Spryker\Zed\GlossaryStorage\Persistence\Propel\Mapper\GlossaryStorageMapper;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\GlossaryStorage\GlossaryStorageConfig getConfig()
 * @method \Spryker\Zed\GlossaryStorage\Persistence\GlossaryStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\GlossaryStorage\Persistence\GlossaryStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\GlossaryStorage\Persistence\GlossaryStorageQueryContainerInterface getQueryContainer()
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
     * @deprecated This will be removed without replacement, please check
     * `\Spryker\Zed\GlossaryStorage\Dependency\Facade\GlossaryStorageToGlossaryFacadeInterface::findGlossaryTranslationEntityTransfer()`.
     *
     * @return \Spryker\Zed\GlossaryStorage\Dependency\QueryContainer\GlossaryStorageToGlossaryQueryContainerInterface
     */
    public function getGlossaryQueryContainer()
    {
        return $this->getProvidedDependency(GlossaryStorageDependencyProvider::QUERY_CONTAINER_GLOSSARY);
    }

    /**
     * @return \Spryker\Zed\GlossaryStorage\Persistence\Propel\Mapper\GlossaryStorageMapperInterface
     */
    public function createGlossaryStorageMapper()
    {
        return new GlossaryStorageMapper();
    }

    /**
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery
     */
    public function getGlossaryTranslationQuery(): SpyGlossaryTranslationQuery
    {
        return $this->getProvidedDependency(GlossaryStorageDependencyProvider::PROPEL_QUERY_GLOSSARY_TRANSLATION);
    }

    /**
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery
     */
    public function getGlossaryKeyQuery(): SpyGlossaryKeyQuery
    {
        return $this->getProvidedDependency(GlossaryStorageDependencyProvider::PROPEL_QUERY_GLOSSARY_KEY);
    }
}
