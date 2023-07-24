<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\StoresApi\Processor\Builder;

use Generated\Shared\Transfer\ApiStoreAttributesTransfer;
use Generated\Shared\Transfer\ApiStoreLocaleAttributesTransfer;
use Generated\Shared\Transfer\GlueErrorTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\StoreStorageTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Glue\StoresApi\Dependency\Client\StoresApiToGlossaryStorageClientInterface;
use Spryker\Glue\StoresApi\StoresApiConfig;
use Symfony\Component\HttpFoundation\Response;

class StoresApiResponseBuilder implements StoresApiResponseBuilderInterface
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
     * @var \Spryker\Glue\StoresApi\Dependency\Client\StoresApiToGlossaryStorageClientInterface
     */
    protected StoresApiToGlossaryStorageClientInterface $glossaryStorageClient;

    /**
     * @param \Spryker\Glue\StoresApi\Dependency\Client\StoresApiToGlossaryStorageClientInterface $glossaryStorageClient
     */
    public function __construct(
        StoresApiToGlossaryStorageClientInterface $glossaryStorageClient
    ) {
        $this->glossaryStorageClient = $glossaryStorageClient;
    }

    /**
     * @param string $currentLocale
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponseTransfer
     * @param \Generated\Shared\Transfer\StoreStorageTransfer|null $storeStorageTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createSingleResourceGlueResponseTransfer(
        string $currentLocale,
        GlueResponseTransfer $glueResponseTransfer,
        ?StoreStorageTransfer $storeStorageTransfer
    ): GlueResponseTransfer {
        if ($storeStorageTransfer !== null) {
            return $this->mapStoreStorageTransferToGlueResponseTransfer($storeStorageTransfer, $glueResponseTransfer);
        }

        return $this->create404GlueResponseTransfer($currentLocale);
    }

    /**
     * @param array<\Generated\Shared\Transfer\StoreStorageTransfer> $storeStorageTransfers
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponseTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function mapStoreStorageTransfersToCollectionResourceGlueResponseTransfer(
        array $storeStorageTransfers,
        GlueResponseTransfer $glueResponseTransfer
    ): GlueResponseTransfer {
        foreach ($storeStorageTransfers as $storeStorageTransfer) {
            $glueResponseTransfer = $this->mapStoreStorageTransferToGlueResponseTransfer($storeStorageTransfer, $glueResponseTransfer);
        }

        return $glueResponseTransfer;
    }

    /**
     * @param array<\Generated\Shared\Transfer\StoreStorageTransfer> $storeStorageTransfers
     *
     * @return array<mixed>
     */
    public function mapStoreStorageTransfersToStoresArray(array $storeStorageTransfers): array
    {
        if ($storeStorageTransfers === []) {
            return $storeStorageTransfers;
        }

        $stores = [];
        foreach ($storeStorageTransfers as $storeStorageTransfer) {
            $stores[$storeStorageTransfer->getNameOrFail()][static::CURRENCIES_KEY] = $storeStorageTransfer->getAvailableCurrencyIsoCodes();
            $stores[$storeStorageTransfer->getNameOrFail()][static::COUNTRIES_KEY] = $storeStorageTransfer->getCountries();
        }

        return $stores;
    }

    /**
     * @param string $currentLocale
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function create404GlueResponseTransfer(string $currentLocale): GlueResponseTransfer
    {
        $glueResponseTransfer = new GlueResponseTransfer();

        $errorMessage = $this->glossaryStorageClient->translate(
            StoresApiConfig::GLOSSARY_KEY_VALIDATION_STORE_NOT_FOUND,
            $currentLocale,
        );

        $glueResponseTransfer
            ->setHttpStatus(Response::HTTP_NOT_FOUND)
            ->addError(
                (new GlueErrorTransfer())
                    ->setStatus(Response::HTTP_NOT_FOUND)
                    ->setMessage($errorMessage)
                    ->setCode(StoresApiConfig::RESPONSE_CODE_STORE_NOT_FOUND),
            );

        return $glueResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\StoreStorageTransfer $storeStorageTransfer
     *
     * @return \Generated\Shared\Transfer\StoreStorageTransfer
     */
    public function mapStoreTransferToStoreStorageTransfer(
        StoreTransfer $storeTransfer,
        StoreStorageTransfer $storeStorageTransfer
    ): StoreStorageTransfer {
        return $storeStorageTransfer->fromArray(
            $storeTransfer->toArray(true),
            true,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\StoreStorageTransfer $storeStorageTransfer
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponseTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    protected function mapStoreStorageTransferToGlueResponseTransfer(
        StoreStorageTransfer $storeStorageTransfer,
        GlueResponseTransfer $glueResponseTransfer
    ): GlueResponseTransfer {
        $apiStoreAttributesTransfer = $this->mapStoreStorageTransferToApiStoreAttributesTransfer($storeStorageTransfer, new ApiStoreAttributesTransfer());

        $resourceTransfer = new GlueResourceTransfer();
        $resourceTransfer->setAttributes($apiStoreAttributesTransfer);
        $resourceTransfer->setId($storeStorageTransfer->getNameOrFail());
        $resourceTransfer->setType(StoresApiConfig::RESOURCE_STORES);
        $glueResponseTransfer->addResource($resourceTransfer);

        return $glueResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreStorageTransfer $storeStorageTransfer
     * @param \Generated\Shared\Transfer\ApiStoreAttributesTransfer $apiStoreAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ApiStoreAttributesTransfer
     */
    protected function mapStoreStorageTransferToApiStoreAttributesTransfer(
        StoreStorageTransfer $storeStorageTransfer,
        ApiStoreAttributesTransfer $apiStoreAttributesTransfer
    ): ApiStoreAttributesTransfer {
        $apiStoreAttributesTransfer->fromArray($storeStorageTransfer->toArray(), true);
        $apiStoreAttributesTransfer->setDefaultCurrency($storeStorageTransfer->getDefaultCurrencyIsoCode());
        $apiStoreAttributesTransfer->setDefaultLocale($storeStorageTransfer->getDefaultLocaleIsoCode());

        return $this->addLocaleToStoresRestAttributes($apiStoreAttributesTransfer, $storeStorageTransfer->getAvailableLocaleIsoCodes());
    }

    /**
     * @param \Generated\Shared\Transfer\ApiStoreAttributesTransfer $apiStoreAttributesTransfer
     * @param array<string> $locales
     *
     * @return \Generated\Shared\Transfer\ApiStoreAttributesTransfer
     */
    protected function addLocaleToStoresRestAttributes(
        ApiStoreAttributesTransfer $apiStoreAttributesTransfer,
        array $locales
    ): ApiStoreAttributesTransfer {
        foreach ($locales as $name) {
            $apiStoreLocaleAttributesTransfer = (new ApiStoreLocaleAttributesTransfer())
                ->setName($name);

            $apiStoreAttributesTransfer->addLocale($apiStoreLocaleAttributesTransfer);
        }

        return $apiStoreAttributesTransfer;
    }
}
