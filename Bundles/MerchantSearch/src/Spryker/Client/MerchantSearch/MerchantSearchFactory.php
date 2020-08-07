<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantSearch;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\MerchantSearch\Dependency\Client\MerchantSearchToStoreClientInterface;
use Spryker\Client\MerchantSearch\Dependency\Client\MerchantSearchToZedRequestClientInterface;
use Spryker\Client\MerchantSearch\MerchantReader\MerchantReader;
use Spryker\Client\MerchantSearch\MerchantReader\MerchantReaderInterface;
use Spryker\Client\MerchantSearch\Zed\MerchantSearchStub;
use Spryker\Client\MerchantSearch\Zed\MerchantSearchStubInterface;

class MerchantSearchFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\MerchantSearch\Zed\MerchantSearchStubInterface
     */
    public function createMerchantSearchStub(): MerchantSearchStubInterface
    {
        return new MerchantSearchStub(
            $this->getZedRequestClient()
        );
    }

    /**
     * @return \Spryker\Client\MerchantSearch\MerchantReader\MerchantReaderInterface
     */
    public function createMerchantReader(): MerchantReaderInterface
    {
        return new MerchantReader(
            $this->createMerchantSearchStub(),
            $this->getStoreClient()
        );
    }

    /**
     * @return \Spryker\Client\MerchantSearch\Dependency\Client\MerchantSearchToZedRequestClientInterface
     */
    public function getZedRequestClient(): MerchantSearchToZedRequestClientInterface
    {
        return $this->getProvidedDependency(MerchantSearchDependencyProvider::CLIENT_ZED_REQUEST);
    }

    /**
     * @return \Spryker\Client\MerchantSearch\Dependency\Client\MerchantSearchToStoreClientInterface
     */
    public function getStoreClient(): MerchantSearchToStoreClientInterface
    {
        return $this->getProvidedDependency(MerchantSearchDependencyProvider::CLIENT_STORE);
    }
}
