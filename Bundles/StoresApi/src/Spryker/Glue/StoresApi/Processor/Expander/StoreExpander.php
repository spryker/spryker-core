<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\StoresApi\Processor\Expander;

use ArrayObject;
use Generated\Shared\Transfer\ApiStoreAttributesTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Spryker\Glue\StoresApi\Processor\Reader\StoresCountryReaderInterface;
use Spryker\Glue\StoresApi\Processor\Reader\StoresCurrencyReaderInterface;

class StoreExpander implements StoreExpanderInterface
{
    /**
     * @var string
     */
    protected const CURRENCIES_KEY = 'currencies';

    /**
     * @var string
     */
    protected const COUNTRIES_KEY = 'countries';

    /**
     * @var \Spryker\Glue\StoresApi\Processor\Reader\StoresCountryReaderInterface
     */
    protected StoresCountryReaderInterface $countryReader;

    /**
     * @var \Spryker\Glue\StoresApi\Processor\Reader\StoresCurrencyReaderInterface
     */
    protected StoresCurrencyReaderInterface $currencyReader;

    /**
     * @param \Spryker\Glue\StoresApi\Processor\Reader\StoresCountryReaderInterface $countryReader
     * @param \Spryker\Glue\StoresApi\Processor\Reader\StoresCurrencyReaderInterface $currencyReader
     */
    public function __construct(
        StoresCountryReaderInterface $countryReader,
        StoresCurrencyReaderInterface $currencyReader
    ) {
        $this->countryReader = $countryReader;
        $this->currencyReader = $currencyReader;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiStoreAttributesTransfer $apiStoreAttributesTransfer
     * @param array<string, mixed> $storesArray
     * @param \Generated\Shared\Transfer\GlueResourceTransfer $glueResourceTransfer
     *
     * @return \Generated\Shared\Transfer\ApiStoreAttributesTransfer
     */
    public function expandApiStoreAttributesTransfer(
        ApiStoreAttributesTransfer $apiStoreAttributesTransfer,
        array $storesArray,
        GlueResourceTransfer $glueResourceTransfer
    ): ApiStoreAttributesTransfer {
        $apiStoreAttributesTransfer->setCountries(new ArrayObject($this->countryReader->getStoresCountryAttributes($storesArray[$glueResourceTransfer->getIdOrFail()][static::COUNTRIES_KEY])));
        $apiStoreAttributesTransfer->setCurrencies(new ArrayObject($this->currencyReader->getStoresCurrencyAttributes($storesArray[$glueResourceTransfer->getIdOrFail()][static::CURRENCIES_KEY])));

        return $apiStoreAttributesTransfer;
    }
}
