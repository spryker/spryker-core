<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DummyEntitiesRestApi;

use Spryker\Glue\DummyEntitiesRestApi\Processor\DummyEntity\Builder\DummyEntityRestResponseBuilder;
use Spryker\Glue\DummyEntitiesRestApi\Processor\DummyEntity\Builder\DummyEntityRestResponseBuilderInterface;
use Spryker\Glue\DummyEntitiesRestApi\Processor\DummyEntity\DummyEntityCreator;
use Spryker\Glue\DummyEntitiesRestApi\Processor\DummyEntity\DummyEntityCreatorInterface;
use Spryker\Glue\DummyEntitiesRestApi\Processor\DummyEntity\DummyEntityDeleter;
use Spryker\Glue\DummyEntitiesRestApi\Processor\DummyEntity\DummyEntityDeleterInterface;
use Spryker\Glue\DummyEntitiesRestApi\Processor\DummyEntity\DummyEntityReader;
use Spryker\Glue\DummyEntitiesRestApi\Processor\DummyEntity\DummyEntityReaderInterface;
use Spryker\Glue\DummyEntitiesRestApi\Processor\DummyEntity\DummyEntityUpdater;
use Spryker\Glue\DummyEntitiesRestApi\Processor\DummyEntity\DummyEntityUpdaterInterface;
use Spryker\Glue\DummyEntitiesRestApi\Processor\DummyEntity\Mapper\DummyEntityMapper;
use Spryker\Glue\DummyEntitiesRestApi\Processor\DummyEntity\Mapper\DummyEntityMapperInterface;
use Spryker\Glue\Kernel\AbstractFactory;

class DummyEntitiesRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\DummyEntitiesRestApi\Processor\DummyEntity\DummyEntityReaderInterface
     */
    public function createDummyEntityReader(): DummyEntityReaderInterface
    {
        return new DummyEntityReader(
            $this->createDummyEntityMapper(),
            $this->createDummyEntityRestResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\DummyEntitiesRestApi\Processor\DummyEntity\DummyEntityCreatorInterface
     */
    public function createDummyEntityCreator(): DummyEntityCreatorInterface
    {
        return new DummyEntityCreator(
            $this->createDummyEntityMapper(),
            $this->createDummyEntityRestResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\DummyEntitiesRestApi\Processor\DummyEntity\DummyEntityUpdaterInterface
     */
    public function createDummyEntityUpdater(): DummyEntityUpdaterInterface
    {
        return new DummyEntityUpdater(
            $this->createDummyEntityReader(),
            $this->createDummyEntityMapper(),
            $this->createDummyEntityRestResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\DummyEntitiesRestApi\Processor\DummyEntity\DummyEntityDeleterInterface
     */
    public function createDummyEntityDeleter(): DummyEntityDeleterInterface
    {
        return new DummyEntityDeleter(
            $this->createDummyEntityReader(),
            $this->createDummyEntityMapper(),
            $this->createDummyEntityRestResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\DummyEntitiesRestApi\Processor\DummyEntity\Mapper\DummyEntityMapperInterface
     */
    public function createDummyEntityMapper(): DummyEntityMapperInterface
    {
        return new DummyEntityMapper();
    }

    /**
     * @return \Spryker\Glue\DummyEntitiesRestApi\Processor\DummyEntity\Builder\DummyEntityRestResponseBuilderInterface
     */
    public function createDummyEntityRestResponseBuilder(): DummyEntityRestResponseBuilderInterface
    {
        return new DummyEntityRestResponseBuilder(
            $this->createDummyEntityMapper(),
            $this->getResourceBuilder()
        );
    }
}
