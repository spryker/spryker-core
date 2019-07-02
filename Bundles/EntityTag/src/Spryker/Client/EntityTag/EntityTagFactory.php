<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\EntityTag;

use Spryker\Client\EntityTag\Dependency\Client\EntityTagToStorageClientInterface;
use Spryker\Client\EntityTag\Dependency\Service\EntityTagToSynchronizationServiceInterface;
use Spryker\Client\EntityTag\Dependency\Service\EntityTagToUtilEncodingServiceInterface;
use Spryker\Client\EntityTag\Dependency\Service\EntityTagToUtilTextServiceInterface;
use Spryker\Client\EntityTag\Storage\EntityTagKeyGenerator;
use Spryker\Client\EntityTag\Storage\EntityTagKeyGeneratorInterface;
use Spryker\Client\EntityTag\Storage\EntityTagReader;
use Spryker\Client\EntityTag\Storage\EntityTagReaderInterface;
use Spryker\Client\EntityTag\Storage\EntityTagWriter;
use Spryker\Client\EntityTag\Storage\EntityTagWriterInterface;
use Spryker\Client\Kernel\AbstractFactory;

/**
 * @method \Spryker\Client\EntityTag\EntityTagConfig getConfig()
 */
class EntityTagFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\EntityTag\Storage\EntityTagReaderInterface
     */
    public function createEntityTagReader(): EntityTagReaderInterface
    {
        return new EntityTagReader(
            $this->createEntityTagKeyGenerator(),
            $this->getStorageClient()
        );
    }

    /**
     * @return \Spryker\Client\EntityTag\Storage\EntityTagKeyGeneratorInterface
     */
    public function createEntityTagKeyGenerator(): EntityTagKeyGeneratorInterface
    {
        return new EntityTagKeyGenerator(
            $this->getSynchronizationService()
        );
    }

    /**
     * @return \Spryker\Client\EntityTag\Storage\EntityTagWriterInterface
     */
    public function createEntityTagWriter(): EntityTagWriterInterface
    {
        return new EntityTagWriter(
            $this->createEntityTagKeyGenerator(),
            $this->getStorageClient(),
            $this->getUtilTextService(),
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \Spryker\Client\EntityTag\Dependency\Client\EntityTagToStorageClientInterface
     */
    public function getStorageClient(): EntityTagToStorageClientInterface
    {
        return $this->getProvidedDependency(EntityTagDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\EntityTag\Dependency\Service\EntityTagToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): EntityTagToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(EntityTagDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Client\EntityTag\Dependency\Service\EntityTagToUtilTextServiceInterface
     */
    public function getUtilTextService(): EntityTagToUtilTextServiceInterface
    {
        return $this->getProvidedDependency(EntityTagDependencyProvider::SERVICE_UTIL_TEXT);
    }

    /**
     * @return \Spryker\Client\EntityTag\Dependency\Service\EntityTagToSynchronizationServiceInterface
     */
    public function getSynchronizationService(): EntityTagToSynchronizationServiceInterface
    {
        return $this->getProvidedDependency(EntityTagDependencyProvider::SERVICE_SYNCHRONIZATION);
    }
}
