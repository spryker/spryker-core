<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductStorage;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;

interface ProductStorageClientInterface
{
    /**
     * Specification:
     * - Retrieves a current Store specific ProductAbstract resource from Storage.
     * - Filter the restricted product variants (product concrete) in `attribute_map`.
     *
     * @api
     *
     * @deprecated Use findProductAbstractStorageData(int $idProductAbstract, string $localeName): ?array
     *
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return array
     */
    public function getProductAbstractStorageData($idProductAbstract, $localeName);

    /**
     * Specification:
     * - Retrieves a current Store specific ProductAbstract resource from Storage.
     * - Responds with null if product abstract is restricted.
     * - Filter the restricted product variants (product concrete) in `attribute_map`.
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return array|null
     */
    public function findProductAbstractStorageData(int $idProductAbstract, string $localeName): ?array;

    /**
     * Specification:
     * - Retrieves a current Store specific ProductAbstract resource from Storage.
     * - Responds with null if product abstract is restricted.
     * - Maps raw product data to ProductViewTransfer for the current locale.
     * - Based on the super attributes and the selected attributes of the product the result is abstract product.
     * - Executes a stack of `StorageProductExpanderPluginInterface` plugins that expand result.
     * - Filter the restricted product variants (product concrete) in `attribute_map`.
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param string $localeName
     * @param array $selectedAttributes
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer|null
     */
    public function findProductAbstractViewTransfer(int $idProductAbstract, string $localeName, array $selectedAttributes = []): ?ProductViewTransfer;

    /**
     * Specification:
     * - Retrieves a current Store specific ProductAbstract resource from Storage using specified mapping.
     * - Responds with null if product abstract is restricted.
     * - Filter the restricted product variants (product concrete) in `attribute_map`.
     *
     * @api
     *
     * @param string $mappingType
     * @param string $identifier
     * @param string $localeName
     *
     * @return array|null
     */
    public function findProductAbstractStorageDataByMapping(string $mappingType, string $identifier, string $localeName): ?array;

    /**
     * Specification:
     * - Retrieves a current Store specific ProductConcrete resource from Storage.
     * - Responds with null if product concrete is restricted.
     *
     * @api
     *
     * @deprecated Use findProductConcreteStorageData($idProductConcrete, $localeName): ?array
     *
     * @param int $idProductConcrete
     * @param string $localeName
     *
     * @return array
     */
    public function getProductConcreteStorageData($idProductConcrete, $localeName);

    /**
     * Specification:
     * - Retrieves a current Store specific ProductConcrete resource from Storage.
     *
     * @api
     *
     * @param int $idProductConcrete
     * @param string $localeName
     *
     * @return array|null
     */
    public function findProductConcreteStorageData(int $idProductConcrete, string $localeName): ?array;

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $productIds
     *
     * @return \Generated\Shared\Transfer\ProductConcreteStorageTransfer[]
     */
    public function getProductConcreteStorageTransfers(array $productIds): array;

    /**
     * Specification:
     * - Retrieves a current Store specific ProductConcrete resource from Storage.
     * - Responds with null if product concrete is restricted.
     * - Maps raw product data to ProductViewTransfer for the current locale.
     * - Based on the super attributes and the selected attributes of the product the result is concrete product.
     * - Executes a stack of `StorageProductExpanderPluginInterface` plugins that expand result.
     *
     * @api
     *
     * @param int $idProductConcrete
     * @param string $localeName
     * @param array $selectedAttributes
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer|null
     */
    public function findProductConcreteViewTransfer(int $idProductConcrete, string $localeName, array $selectedAttributes = []): ?ProductViewTransfer;

    /**
     * Specification:
     * - Retrieves a current Store specific ProductConcrete resource from Storage using specified mapping.
     *
     * @api
     *
     * @param string $mappingType
     * @param string $identifier
     * @param string $localeName
     *
     * @return array|null
     */
    public function findProductConcreteStorageDataByMapping(string $mappingType, string $identifier, string $localeName): ?array;

    /**
     * Specification:
     * - Maps raw product data to ProductViewTransfer for the current locale.
     * - Based on the super attributes and the selected attributes of the product the result might be abstract or concrete product.
     * - Executes a stack of Spryker\Client\ProductStorage\Dependency\Plugin\StorageProductExpanderPluginInterface plugins that
     * can expand the result with extra data.
     * - Filter the restricted product variants (product concrete) in `attribute_map`.
     *
     * @api
     *
     * @param array $data
     * @param string $localeName
     * @param array $selectedAttributes
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function mapProductStorageData(array $data, $localeName, array $selectedAttributes = []);

    /**
     * Specification:
     * - Maps raw product data from Storage to ProductViewTransfer for the provided locale.
     * - Executes a stack of ProductViewExpanderPluginInterface plugins on ProductViewTransfer but excludes ProductConcreteViewExpanderExcluderPluginInterface.
     *
     * @api
     *
     * @param array $data
     * @param string $localeName
     * @param array $selectedAttributes
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function mapProductAbstractStorageData(array $data, $localeName, array $selectedAttributes = []);

    /**
     * Specification:
     * - Checks if products abstract is restricted.
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return bool
     */
    public function isProductAbstractRestricted(int $idProductAbstract): bool;

    /**
     * Specification:
     * - Checks if products concrete is restricted.
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return bool
     */
    public function isProductConcreteRestricted(int $idProductConcrete): bool;

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $mappingType
     * @param string $identifier
     *
     * @return array|null
     */
    public function findProductConcreteStorageDataByMappingForCurrentLocale(string $mappingType, string $identifier): ?array;

    /**
     * Specification:
     * - maps given storage data to ProductConcreteTransfer.
     * - executes ProductConcreteExpanderPluginInterface plugin stack.
     *
     * @api
     *
     * @param array $productStorageData
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function mapProductStorageDataToProductConcreteTransfer(array $productStorageData): ProductConcreteTransfer;

    /**
     * Specification:
     * - Finds product abstract data by array of product abstract ids and local name.
     * - Result will be indexed by product abstract id, e.g. `[%product_abstract_id% => [%product_abstract_storage_data%]]`.
     *
     * @api
     *
     * @param int[] $productAbstractIds
     * @param string $localeName
     *
     * @return array
     */
    public function getBulkProductAbstractStorageDataByProductAbstractIdsAndLocaleName(array $productAbstractIds, string $localeName): array;

    /**
     * Specification:
     * - Retrieves store specific ProductAbstract resources from Storage.
     * - Filters restricted products.
     * - Maps raw product data to ProductViewTransfers for the current locale.
     * - Executes a stack of `StorageProductExpanderPluginInterface` plugins that expand result.
     *
     * @api
     *
     * @param int[] $productAbstractIds
     * @param string $localeName
     * @param array $selectedAttributes
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    public function getProductAbstractViewTransfers(array $productAbstractIds, string $localeName, array $selectedAttributes = []): array;

    /**
     * Specification:
     * - Retrieves store specific ProductConcrete resources from Storage.
     * - Filters restricted concrete products.
     * - Maps raw product data to ProductViewTransfers for the current locale.
     * - Executes a stack of `StorageProductExpanderPluginInterface` plugins that expand result.
     *
     * @api
     *
     * @param int[] $productConcreteIds
     * @param string $localeName
     * @param array $selectedAttributes
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    public function getProductConcreteViewTransfers(array $productConcreteIds, string $localeName, array $selectedAttributes = []): array;

    /**
     * Specification:
     * - Retrieves a current Store specific ProductAbstract resources from Storage using specified mapping.
     * - Skips restricted product abstract records.
     * - Filter the restricted product variants (product concrete) in `attribute_map`.
     *
     * @api
     *
     * @param string $mappingType
     * @param string[] $identifiers
     * @param string $localeName
     *
     * @return array
     */
    public function findBulkProductAbstractStorageDataByMapping(string $mappingType, array $identifiers, string $localeName): array;

    /**
     * Specification:
     * - Retrieves a current Store specific ProductConcrete resources from Storage using specified mapping.
     *
     * @api
     *
     * @param string $mappingType
     * @param string[] $identifiers
     * @param string $localeName
     *
     * @return array
     */
    public function getBulkProductConcreteStorageDataByMapping(
        string $mappingType,
        array $identifiers,
        string $localeName
    ): array;

    /**
     * Specification:
     * - Retrieves a current Store specific product abstract ids from Storage using specified mapping.
     *
     * @api
     *
     * @param string $mappingType
     * @param string[] $identifiers
     * @param string $localeName
     *
     * @return int[]
     */
    public function getBulkProductAbstractIdsByMapping(
        string $mappingType,
        array $identifiers,
        string $localeName
    ): array;

    /**
     * Specification:
     * - Retrieves a current Store specific product concrete ids from Storage using specified mapping.
     *
     * @api
     *
     * @param string $mappingType
     * @param string[] $identifiers
     * @param string $localeName
     *
     * @return int[]
     */
    public function getBulkProductConcreteIdsByMapping(
        string $mappingType,
        array $identifiers,
        string $localeName
    ): array;
}
