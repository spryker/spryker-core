<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\StoresRestApi\Processor\Stores;

use Spryker\Glue\StoresRestApi\Dependency\Client\StoresRestApiToCurrencyClientInterface;
use Spryker\Glue\StoresRestApi\Processor\Mapper\StoresCurrencyResourceMapperInterface;
use Spryker\Shared\Kernel\Store;

class StoresCurrencyReader implements StoresCurrencyReaderInterface
{
    /**
     * @var \Spryker\Glue\StoresRestApi\Dependency\Client\StoresRestApiToCurrencyClientInterface
     */
    protected $currencyClient;

    /**
     * @var \Spryker\Glue\StoresRestApi\Processor\Mapper\StoresCurrencyResourceMapperInterface
     */
    protected $storesCurrencyResourceMapper;

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @param \Spryker\Glue\StoresRestApi\Dependency\Client\StoresRestApiToCurrencyClientInterface $currencyClient
     * @param \Spryker\Glue\StoresRestApi\Processor\Mapper\StoresCurrencyResourceMapperInterface $storesCurrencyResourceMapper
     * @param \Spryker\Shared\Kernel\Store $store
     */
    public function __construct(
        StoresRestApiToCurrencyClientInterface $currencyClient,
        StoresCurrencyResourceMapperInterface $storesCurrencyResourceMapper,
        Store $store
    ) {
        $this->currencyClient = $currencyClient;
        $this->storesCurrencyResourceMapper = $storesCurrencyResourceMapper;
        $this->store = $store;
    }

    /**
     * @param array $isoCodes
     *
     * @return \Generated\Shared\Transfer\StoreCurrencyRestAttributesTransfer[]
     */
    public function getStoresCurrencyAttributes(array $isoCodes): array
    {
        $storeCurrencyAttributes = [];

        foreach ($isoCodes as $isoCode) {
            $currencyTransfer = $this->currencyClient->fromIsoCode($isoCode);
            $storeCurrencyAttributes[] = $this->storesCurrencyResourceMapper->mapCurrencyToStoresCurrencyRestAttributes(
                $currencyTransfer,
                $this->store
            );
        }

        return $storeCurrencyAttributes;
    }
}
