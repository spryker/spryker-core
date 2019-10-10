<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GlossaryStorage\Business;

use Spryker\Zed\GlossaryStorage\Business\Deleter\GlossaryTranslationStorageDeleter;
use Spryker\Zed\GlossaryStorage\Business\Mapper\GlossaryTranslationStorageMapper;
use Spryker\Zed\GlossaryStorage\Business\Writer\GlossaryTranslationStorageWriter;
use Spryker\Zed\GlossaryStorage\GlossaryStorageDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\GlossaryStorage\GlossaryStorageConfig getConfig()
 * @method \Spryker\Zed\GlossaryStorage\Persistence\GlossaryStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\GlossaryStorage\Persistence\GlossaryStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\GlossaryStorage\Persistence\GlossaryStorageQueryContainerInterface getQueryContainer()
 */
class GlossaryStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\GlossaryStorage\Business\Mapper\GlossaryTranslationStorageMapperInterface
     */
    public function createGlossaryTranslationStorageFinder()
    {
        return new GlossaryTranslationStorageMapper();
    }

    /**
     * @return \Spryker\Zed\GlossaryStorage\Business\Writer\GlossaryTranslationStorageWriterInterface
     */
    public function createGlossaryTranslationStorageWriter()
    {
        return new GlossaryTranslationStorageWriter(
            $this->getEventBehaviorFacade(),
            $this->getRepository(),
            $this->getEntityManager(),
            $this->createGlossaryTranslationStorageFinder()
        );
    }

    /**
     * @return \Spryker\Zed\GlossaryStorage\Business\Deleter\GlossaryTranslationStorageDeleterInterface
     */
    public function createGlossaryTranslationStorageDeleter()
    {
        return new GlossaryTranslationStorageDeleter(
            $this->getEventBehaviorFacade(),
            $this->getRepository(),
            $this->getEntityManager(),
            $this->createGlossaryTranslationStorageFinder()
        );
    }

    /**
     * @return \Spryker\Zed\GlossaryStorage\Dependency\Facade\GlossaryStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade()
    {
        return $this->getProvidedDependency(GlossaryStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }
}
