<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\StoresRestApi\Processor\Stores;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\StoreCurrencyRestAttributesTransfer;
use Spryker\Glue\StoresRestApi\Dependency\Client\StoresRestApiToCurrencyClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\StoresRestApi\Processor\Mapper\StoresCurrencyResourceMapperInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Shared\Kernel\Store;
use Symfony\Component\HttpFoundation\Response;

class StoresCurrencyReader implements StoresCurrencyReaderInterface
{
    /**
     * @var \Spryker\Glue\StoresRestApi\Dependency\Client\StoresRestApiToCurrencyClientInterface
     */
    protected $currencyClient;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

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
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\StoresRestApi\Processor\Mapper\StoresCurrencyResourceMapperInterface $storesCurrencyResourceMapper
     * @param \Spryker\Shared\Kernel\Store $store
     */
    public function __construct(
        StoresRestApiToCurrencyClientInterface $currencyClient,
        RestResourceBuilderInterface $restResourceBuilder,
        StoresCurrencyResourceMapperInterface $storesCurrencyResourceMapper,
        Store $store
    ) {
        $this->currencyClient = $currencyClient;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->storesCurrencyResourceMapper = $storesCurrencyResourceMapper;
        $this->store = $store;
    }

    /**
     * @param string $isoCode
     *
     * @return \Generated\Shared\Transfer\StoreCurrencyRestAttributesTransfer
     */
    public function getStoresCurrencyAttributes(string $isoCode): StoreCurrencyRestAttributesTransfer
    {
        $currencyTransfer = $this->currencyClient->fromIsoCode($isoCode);

        return $this->storesCurrencyResourceMapper->mapCurrencyToStoresCurrencyRestAttributes(
            $currencyTransfer, $this->store
        );
    }
}
