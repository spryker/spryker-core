<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Business;

use Generated\Shared\Transfer\AccessTokenRequestTransfer;
use Generated\Shared\Transfer\MessageAttributesTransfer;
use Generated\Shared\Transfer\MessageValidationResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteValidationResponseTransfer;
use Generated\Shared\Transfer\StoreCollectionTransfer;
use Generated\Shared\Transfer\StoreCriteriaTransfer;
use Generated\Shared\Transfer\StoreResponseTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;

/**
 * @method \Spryker\Zed\Store\Business\StoreBusinessFactory getFactory()
 */
interface StoreFacadeInterface
{
    /**
     * Specification:
     * - Returns currently selected store transfer.
     * - Executes stack of {@link \Spryker\Zed\StoreExtension\Dependency\Plugin\StoreCollectionExpanderPluginInterface} plugins.
     *
     * @api
     *
     * @param bool $fallbackToDefault
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getCurrentStore(bool $fallbackToDefault = false);

    /**
     * Specification:
     * - Reads all active stores and returns list of transfers.
     * - Executes stack of {@link \Spryker\Zed\StoreExtension\Dependency\Plugin\StoreCollectionExpanderPluginInterface} plugins.
     *
     * @api
     *
     * @return array<\Generated\Shared\Transfer\StoreTransfer>
     */
    public function getAllStores();

    /**
     * Specification:
     * - Retrieves filtered stores using StoreCriteriaTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StoreCriteriaTransfer $storeCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\StoreCollectionTransfer
     */
    public function getStoreCollection(StoreCriteriaTransfer $storeCriteriaTransfer): StoreCollectionTransfer;

    /**
     * Specification:
     * - Read store by primary id from database.
     * - Executes stack of {@link \Spryker\Zed\StoreExtension\Dependency\Plugin\StoreCollectionExpanderPluginInterface} plugins.
     *
     * @api
     *
     * @param int $idStore
     *
     * @throws \Spryker\Zed\Store\Business\Model\Exception\StoreNotFoundException
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getStoreById($idStore);

    /**
     * Specification:
     * - Read store by store name.
     * - Executes stack of {@link \Spryker\Zed\StoreExtension\Dependency\Plugin\StoreCollectionExpanderPluginInterface} plugins.
     *
     * @api
     *
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getStoreByName($storeName);

    /**
     * Specification:
     * - Retrieves store by store name.
     * - Executes stack of {@link \Spryker\Zed\StoreExtension\Dependency\Plugin\StoreCollectionExpanderPluginInterface} plugins.
     *
     * @api
     *
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\StoreTransfer|null
     */
    public function findStoreByName(string $storeName): ?StoreTransfer;

    /**
     * Specification:
     * - Reads all shared store from Store transfer and populates data from configuration.
     * - The list of stores with which this store shares database, the value is store name.
     * - Executes stack of {@link \Spryker\Zed\StoreExtension\Dependency\Plugin\StoreCollectionExpanderPluginInterface} plugins.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return array<\Generated\Shared\Transfer\StoreTransfer>
     */
    public function getStoresWithSharedPersistence(StoreTransfer $storeTransfer);

    /**
     * Specification:
     * - Returns store countries.
     *
     * @api
     *
     * @deprecated Unused method will be removed in next major.
     *
     * @return array<string>
     */
    public function getCountries();

    /**
     * Specification:
     * - Validates store transfer in the Quote.
     * - Returns QuoteValidationResponseTransfer.isSuccessful=false if QuoteTransfer.Store does not exist
     * - Returns QuoteValidationResponseTransfer.isSuccessful=false if QuoteTransfer.Store does not have a name
     * - Returns QuoteValidationResponseTransfer.isSuccessful=false if QuoteTransfer.Store has a store that does not exist
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteValidationResponseTransfer
     */
    public function validateQuoteStore(QuoteTransfer $quoteTransfer): QuoteValidationResponseTransfer;

    /**
     * Specification:
     * - Gets array of StoreTransfer by array of store names.
     * - Executes stack of {@link \Spryker\Zed\StoreExtension\Dependency\Plugin\StoreCollectionExpanderPluginInterface} plugins.
     *
     * @api
     *
     * @param array<string> $storeNames
     *
     * @return array<\Generated\Shared\Transfer\StoreTransfer>
     */
    public function getStoreTransfersByStoreNames(array $storeNames): array;

    /**
     * Specification:
     * - Checks if multi-store per Zed is enabled.
     * - Gets the value from module configuration.
     *
     * @api
     *
     * @return bool
     */
    public function isMultiStorePerZedEnabled(): bool;

    /**
     * Specification:
     * - Gets currently selected store transfer.
     * - Fetches all shared stores related to current store transfer.
     * - Returns a list of stores available for current persistence.
     * - Executes stack of {@link \Spryker\Zed\StoreExtension\Dependency\Plugin\StoreCollectionExpanderPluginInterface} plugins.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\Store\Business\StoreFacade::getAllStores()} instead.
     *
     * @return array<\Generated\Shared\Transfer\StoreTransfer>
     */
    public function getStoresAvailableForCurrentPersistence(): array;

    /**
     * Specification:
     * - Creates a Store from provided `StoreTransfer`.
     * - Executes stack of {@link \Spryker\Zed\StoreExtension\Dependency\Plugin\StorePreCreateValidationPluginInterface} plugins.
     * - Executes stack of {@link \Spryker\Zed\StoreExtension\Dependency\Plugin\StorePostCreatePluginInterface} plugins.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreResponseTransfer
     */
    public function createStore(StoreTransfer $storeTransfer): StoreResponseTransfer;

    /**
     * Specification:
     * - Updates a Store from provided `StoreTransfer`.
     * - Executes stack of {@link \Spryker\Zed\StoreExtension\Dependency\Plugin\StorePreUpdateValidationPluginInterface} plugins.
     * - Executes stack of {@link \Spryker\Zed\StoreExtension\Dependency\Plugin\StorePostUpdatePluginInterface} plugins.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreResponseTransfer
     */
    public function updateStore(StoreTransfer $storeTransfer): StoreResponseTransfer;

    /**
     * Specification:
     * - Returns true if dynamic store functionality is enabled.
     *
     * @api
     *
     * @return bool
     */
    public function isDynamicStoreEnabled(): bool;

    /**
     * Specification:
     * - Returns true if the current store is provided in the application.
     *
     * @api
     *
     * @return bool
     */
    public function isCurrentStoreDefined(): bool;

    /**
     * Specification:
     * - Finds Store by `storeReference`.
     * - Returns `StoreTransfer` if Store has provided `storeReference`, otherwise throws the exception.
     * - Executes stack of {@link \Spryker\Zed\StoreExtension\Dependency\Plugin\StoreCollectionExpanderPluginInterface} plugins.
     *
     * @api
     *
     * @param string $storeReference
     *
     * @throws \Spryker\Zed\Store\Business\Exception\StoreReferenceNotFoundException
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getStoreByStoreReference(string $storeReference): StoreTransfer;

    /**
     * Specification:
     * - Expands only if dynamic store is disabled.
     * - Finds a store reference for the currently selected store.
     * - Expands `AccessTokenRequest.accessTokenRequestOptions` with found store reference.
     * - Executes stack of {@link \Spryker\Zed\StoreExtension\Dependency\Plugin\StoreCollectionExpanderPluginInterface} plugins.
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @param \Generated\Shared\Transfer\AccessTokenRequestTransfer $accessTokenRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AccessTokenRequestTransfer
     */
    public function expandAccessTokenRequest(AccessTokenRequestTransfer $accessTokenRequestTransfer): AccessTokenRequestTransfer;

    /**
     * Specification:
     * - Validates if `storeReference` from the message matches `storeReference` of one the configured stores.
     * - Executes stack of {@link \Spryker\Zed\StoreExtension\Dependency\Plugin\StoreCollectionExpanderPluginInterface} plugins.
     *
     * @api
     *
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $messageTransfer
     *
     * @return \Generated\Shared\Transfer\MessageValidationResponseTransfer
     */
    public function validateMessageTransfer(TransferInterface $messageTransfer): MessageValidationResponseTransfer;

    /**
     * Specification:
     * - Expands only if dynamic store is disabled.
     * - When store reference is not provided, expands message attributes with store reference from a store set in the application environment.
     * - Executes stack of {@link \Spryker\Zed\StoreExtension\Dependency\Plugin\StoreCollectionExpanderPluginInterface} plugins.
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @param \Generated\Shared\Transfer\MessageAttributesTransfer $messageAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\MessageAttributesTransfer
     */
    public function expandMessageAttributes(
        MessageAttributesTransfer $messageAttributesTransfer
    ): MessageAttributesTransfer;
}
