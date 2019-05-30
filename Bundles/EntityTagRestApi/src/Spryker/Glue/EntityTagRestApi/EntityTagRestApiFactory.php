<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\EntityTagRestApi;

use Spryker\Glue\EntityTagRestApi\Dependency\Client\EntityTagRestApiToEntityTagClientInterface;
use Spryker\Glue\EntityTagRestApi\Dependency\Service\EntityTagRestApiToUtilTextServiceInterface;
use Spryker\Glue\EntityTagRestApi\Processor\EntityTagChecker;
use Spryker\Glue\EntityTagRestApi\Processor\EntityTagCheckerInterface;
use Spryker\Glue\EntityTagRestApi\Processor\EntityTagWriterInterface;
use Spryker\Glue\EntityTagRestApi\Processor\EntityTagResolver;
use Spryker\Glue\EntityTagRestApi\Processor\EntityTagResolverInterface;
use Spryker\Glue\EntityTagRestApi\Processor\EntityTagResponseHeaderFormatter;
use Spryker\Glue\EntityTagRestApi\Processor\EntityTagResponseHeaderFormatterInterface;
use Spryker\Glue\EntityTagRestApi\Processor\EntityTagWriter;
use Spryker\Glue\Kernel\AbstractFactory;

/**
 * @method \Spryker\Glue\EntityTagRestApi\EntityTagRestApiConfig getConfig()
 */
class EntityTagRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\EntityTagRestApi\Processor\EntityTagCheckerInterface
     */
    public function createEntityTagChecker(): EntityTagCheckerInterface
    {
        return new EntityTagChecker(
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Glue\EntityTagRestApi\Processor\EntityTagResolverInterface
     */
    public function createEntityTagResolver(): EntityTagResolverInterface
    {
        return new EntityTagResolver();
    }

    /**
     * @return \Spryker\Glue\EntityTagRestApi\Processor\EntityTagWriterInterface
     */
    public function createEntityTagWriter(): EntityTagWriterInterface
    {
        return new EntityTagWriter(
            $this->getStorageClient(),
            $this->getEntityTagClient()
        );
    }

    /**
     * @return \Spryker\Glue\EntityTagRestApi\Processor\EntityTagResponseHeaderFormatterInterface
     */
    public function createEntityTagResponseHeaderFormatter(): EntityTagResponseHeaderFormatterInterface
    {
        return new EntityTagResponseHeaderFormatter();
    }

    /**
     * @return \Spryker\Glue\EntityTagRestApi\Dependency\Service\EntityTagRestApiToUtilTextServiceInterface
     */
    public function getStorageClient(): EntityTagRestApiToUtilTextServiceInterface
    {
        return $this->getProvidedDependency(EntityTagRestApiDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Glue\EntityTagRestApi\Dependency\Client\EntityTagRestApiToEntityTagClientInterface
     */
    public function getEntityTagClient(): EntityTagRestApiToEntityTagClientInterface
    {
        return $this->getProvidedDependency(EntityTagRestApiDependencyProvider::CLIENT_ENTITY_TAG);
    }
}
