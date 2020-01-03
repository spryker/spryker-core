<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Content\Business;

use Spryker\Zed\Content\Business\ContentReader\ContentReader;
use Spryker\Zed\Content\Business\ContentReader\ContentReaderInterface;
use Spryker\Zed\Content\Business\ContentValidator\ContentConstraintsProvider;
use Spryker\Zed\Content\Business\ContentValidator\ContentConstraintsProviderInterface;
use Spryker\Zed\Content\Business\ContentValidator\ContentValidator;
use Spryker\Zed\Content\Business\ContentValidator\ContentValidatorInterface;
use Spryker\Zed\Content\Business\ContentWriter\ContentWriter;
use Spryker\Zed\Content\Business\ContentWriter\ContentWriterInterface;
use Spryker\Zed\Content\Business\KeyProvider\ContentKeyProvider;
use Spryker\Zed\Content\Business\KeyProvider\ContentKeyProviderInterface;
use Spryker\Zed\Content\ContentDependencyProvider;
use Spryker\Zed\Content\Dependency\External\ContentToValidationAdapterInterface;
use Spryker\Zed\Content\Dependency\Service\ContentToUtilUuidGeneratorServiceInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\Content\ContentConfig getConfig()
 * @method \Spryker\Zed\Content\Persistence\ContentEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\Content\Persistence\ContentRepositoryInterface getRepository()
 */
class ContentBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Content\Business\ContentWriter\ContentWriterInterface
     */
    public function createContentWriter(): ContentWriterInterface
    {
        return new ContentWriter(
            $this->getEntityManager(),
            $this->createContentKeyProvider()
        );
    }

    /**
     * @return \Spryker\Zed\Content\Business\ContentReader\ContentReaderInterface
     */
    public function createContentReader(): ContentReaderInterface
    {
        return new ContentReader(
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\Content\Business\ContentValidator\ContentValidatorInterface
     */
    public function createContentValidator(): ContentValidatorInterface
    {
        return new ContentValidator(
            $this->createContentConstraintsProvider(),
            $this->getValidatorAdapter()
        );
    }

    /**
     * @return \Spryker\Zed\Content\Business\ContentValidator\ContentConstraintsProviderInterface
     */
    public function createContentConstraintsProvider(): ContentConstraintsProviderInterface
    {
        return new ContentConstraintsProvider();
    }

    /**
     * @return \Spryker\Zed\Content\Business\KeyProvider\ContentKeyProviderInterface
     */
    public function createContentKeyProvider(): ContentKeyProviderInterface
    {
        return new ContentKeyProvider(
            $this->getUtilUuidGeneratorService(),
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\Content\Dependency\External\ContentToValidationAdapterInterface
     */
    public function getValidatorAdapter(): ContentToValidationAdapterInterface
    {
        return $this->getProvidedDependency(ContentDependencyProvider::ADAPTER_VALIDATION);
    }

    /**
     * @return \Spryker\Zed\Content\Dependency\Service\ContentToUtilUuidGeneratorServiceInterface
     */
    public function getUtilUuidGeneratorService(): ContentToUtilUuidGeneratorServiceInterface
    {
        return $this->getProvidedDependency(ContentDependencyProvider::SERVICE_UTIL_UUID_GENERATOR);
    }
}
