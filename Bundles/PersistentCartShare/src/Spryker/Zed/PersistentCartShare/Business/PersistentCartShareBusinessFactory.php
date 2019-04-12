<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCartShare\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\PersistentCartShare\Business\Model\QuoteForPreviewReader;
use Spryker\Zed\PersistentCartShare\Business\Model\ResourceDataReader;
use Spryker\Zed\PersistentCartShare\Dependency\Facade\PersistentCartShareToQuoteFacadeInterface;
use Spryker\Zed\PersistentCartShare\Dependency\Facade\PersistentCartShareToResourceShareFacadeInterface;
use Spryker\Zed\PersistentCartShare\Dependency\Service\PersistentCartShareToUtilEncodingServiceInterface;
use Spryker\Zed\PersistentCartShare\PersistentCartShareDependencyProvider;

/**
 * @method \Spryker\Zed\PersistentCartShare\PersistentCartShareConfig getConfig()
 */
class PersistentCartShareBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\PersistentCartShare\Dependency\Facade\PersistentCartShareToResourceShareFacadeInterface
     */
    public function getResourceShareFacade(): PersistentCartShareToResourceShareFacadeInterface
    {
        return $this->getProvidedDependency(PersistentCartShareDependencyProvider::FACADE_RESOURCE_SHARE);
    }

    /**
     * @return \Spryker\Zed\PersistentCartShare\Dependency\Facade\PersistentCartShareToQuoteFacadeInterface
     */
    public function getQuoteFacade(): PersistentCartShareToQuoteFacadeInterface
    {
        return $this->getProvidedDependency(PersistentCartShareDependencyProvider::FACADE_QUOTE);
    }

    /**
     * @return \Spryker\Zed\PersistentCartShare\Business\Model\QuoteForPreviewReader
     */
    public function createQuoteForPreviewReader(): QuoteForPreviewReader
    {
        return new QuoteForPreviewReader(
            $this->createResourceDataReader(),
            $this->getResourceShareFacade(),
            $this->getQuoteFacade()
        );
    }

    /**
     * @return \Spryker\Zed\PersistentCartShare\Business\Model\ResourceDataReader
     */
    public function createResourceDataReader(): ResourceDataReader
    {
        return new ResourceDataReader(
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \Spryker\Zed\PersistentCartShare\Dependency\Service\PersistentCartShareToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): PersistentCartShareToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(PersistentCartShareDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
