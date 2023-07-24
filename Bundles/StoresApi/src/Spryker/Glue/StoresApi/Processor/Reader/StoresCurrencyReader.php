<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\StoresApi\Processor\Reader;

use Generated\Shared\Transfer\ApiStoreCurrencyAttributesTransfer;
use Spryker\Glue\StoresApi\Dependency\Client\StoresApiToCurrencyClientInterface;
use Spryker\Glue\StoresApi\Processor\Mapper\StoresCurrencyResourceMapperInterface;

class StoresCurrencyReader implements StoresCurrencyReaderInterface
{
    /**
     * @var \Spryker\Glue\StoresApi\Dependency\Client\StoresApiToCurrencyClientInterface
     */
    protected $currencyClient;

    /**
     * @var \Spryker\Glue\StoresApi\Processor\Mapper\StoresCurrencyResourceMapperInterface
     */
    protected $storesCurrencyResourceMapper;

    /**
     * @param \Spryker\Glue\StoresApi\Dependency\Client\StoresApiToCurrencyClientInterface $currencyClient
     * @param \Spryker\Glue\StoresApi\Processor\Mapper\StoresCurrencyResourceMapperInterface $storesCurrencyResourceMapper
     */
    public function __construct(
        StoresApiToCurrencyClientInterface $currencyClient,
        StoresCurrencyResourceMapperInterface $storesCurrencyResourceMapper
    ) {
        $this->currencyClient = $currencyClient;
        $this->storesCurrencyResourceMapper = $storesCurrencyResourceMapper;
    }

    /**
     * @param array $isoCodes
     *
     * @return array<\Generated\Shared\Transfer\ApiStoreCurrencyAttributesTransfer>
     */
    public function getStoresCurrencyAttributes(array $isoCodes): array
    {
        $storeCurrencyAttributes = [];

        foreach ($isoCodes as $isoCode) {
            $currencyTransfer = $this->currencyClient->fromIsoCode($isoCode);
            $storeCurrencyAttributes[] = $this->storesCurrencyResourceMapper->mapCurrencyToStoresCurrencyRestAttributes(
                $currencyTransfer,
                new ApiStoreCurrencyAttributesTransfer(),
            );
        }

        return $storeCurrencyAttributes;
    }
}
