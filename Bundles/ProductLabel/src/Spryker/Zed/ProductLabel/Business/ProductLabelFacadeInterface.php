<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\ProductLabelCollectionTransfer;
use Generated\Shared\Transfer\ProductLabelCriteriaTransfer;
use Generated\Shared\Transfer\ProductLabelResponseTransfer;
use Generated\Shared\Transfer\ProductLabelTransfer;
use Psr\Log\LoggerInterface;

interface ProductLabelFacadeInterface
{
    /**
     * Specification:
     * - Finds a product label for the given ID in the database
     * - Returns a product-label transfer or null in case it does not exist
     *
     * @api
     *
     * @param int $idProductLabel
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer|null
     */
    public function findLabelById($idProductLabel);

    /**
     * Specification:
     * - Finds a product label for the given NAME in the database
     * - Returns a product-label transfer or null in case it does not exist
     *
     * @api
     *
     * @param string $labelName
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer|null
     */
    public function findLabelByLabelName(string $labelName): ?ProductLabelTransfer;

    /**
     * Specification:
     * - Finds all existing product labels in the database
     * - Returns a collection of product-label transfers
     *
     * @api
     *
     * @return array<\Generated\Shared\Transfer\ProductLabelTransfer>
     */
    public function findAllLabels();

    /**
     * Specification:
     * - Finds all product labels for the given abstract-product ID in the database
     * - Returns a collection of product-label transfers
     * - Returns an empty collection if either product-label or abstract-product are missing
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return array<\Generated\Shared\Transfer\ProductLabelTransfer>
     */
    public function findLabelsByIdProductAbstract($idProductAbstract);

    /**
     * Specification:
     * - Finds all product labels for the given abstract-product ID in the database.
     * - Returns a collection of product-label IDs.
     * - Returns an empty collection if either product-label or abstract-product are missing.
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return array<int>
     */
    public function findLabelIdsByIdProductAbstract($idProductAbstract);

    /**
     * Specification:
     * - Finds all product active labels for the given abstract-product ID in the database.
     * - Returns a collection of product-label IDs.
     * - Returns an empty collection if either product-label or abstract-product are missing.
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return array<int>
     */
    public function findActiveLabelIdsByIdProductAbstract($idProductAbstract);

    /**
     * Specification:
     * - Finds all the active product labels using ProductLabelCriteria transfer.
     * - Returns a collection of found product labels.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductLabelCriteriaTransfer $productLabelCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\ProductLabelTransfer>
     */
    public function getActiveLabelsByCriteria(ProductLabelCriteriaTransfer $productLabelCriteriaTransfer): array;

    /**
     * Specification:
     * - Requires ProductLabelTransfer.name, ProductLabelTransfer.isActive and ProductLabelTransfer.isExclusive properties to be set.
     * - Persists new product label entity to database.
     * - Persists product label localized attributes to database.
     * - Persists product label to store relation to database.
     * - Touches product label dictionary active.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return void
     */
    public function createLabel(ProductLabelTransfer $productLabelTransfer);

    /**
     * Specification:
     * - Persists product label changes to database.
     * - Persists product label localized attributes to database.
     * - Persists product label to store relation to database.
     * - Touches product label dictionary active if product label was changed.
     * - Touches product abstract active if product label was activated/deactivated.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return void
     */
    public function updateLabel(ProductLabelTransfer $productLabelTransfer);

    /**
     * Specification:
     * - Expects product label id to be provided.
     * - Removes provided product label from Persistence.
     * - Removes assigned localized attributes in Persistence.
     * - Removes relations between product label and abstract products.
     * - Removes relations between product label and store.
     * - Returns 'isSuccessful=true' if the product label was removed.
     * - Returns 'isSuccessful=false' with error messages otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return \Generated\Shared\Transfer\ProductLabelResponseTransfer
     */
    public function removeLabel(ProductLabelTransfer $productLabelTransfer): ProductLabelResponseTransfer;

    /**
     * Specification:
     * - Finds abstract-product relations for the given product-label ID in the database
     * - Returns list of abstract-product IDs
     * - Returns an empty list if not entity exists for the given product label ID
     *
     * @api
     *
     * @param int $idProductLabel
     *
     * @return array<int>
     */
    public function findProductAbstractRelationsByIdProductLabel($idProductLabel);

    /**
     * Specification:
     * - Persists relations for the given product-label ID and list of abstract-product IDs to database
     *
     * @api
     *
     * @param int $idProductLabel
     * @param array<int> $idsProductAbstract
     *
     * @return void
     */
    public function addAbstractProductRelationsForLabel($idProductLabel, array $idsProductAbstract);

    /**
     * Specification:
     * - Removes relations for the given product-label ID and list of abstract-product IDs from database
     *
     * @api
     *
     * @param int $idProductLabel
     * @param array<int> $idsProductAbstract
     *
     * @return void
     */
    public function removeProductAbstractRelationsForLabel($idProductLabel, array $idsProductAbstract);

    /**
     * Specification:
     * - Finds product-labels that are about to become valid/invalid for the current date
     * - Product-labels that are about to become valid and are not published will cause touching of the dictionary
     * - Product-labels that are about to become valid and are not published will be marked as 'published' in the database
     * - Product-labels that are about to become invalid and are published will cause touching of the dictionary
     * - Product-labels that are about to become invalid and are published will be marked as 'unpublished' in the database
     *
     * @api
     *
     * @return void
     */
    public function checkLabelValidityDateRangeAndTouch();

    /**
     * Specification:
     * - Calls a stack of `ProductLabelRelationUpdaterPluginInterface` to collect necessary information to update product label relations.
     * - Touches product abstract product label relations if isTouchEnabled flag set to TRUE.
     * - The results of the plugins are used to persist product label relation changes into database.
     *
     * @api
     *
     * @param \Psr\Log\LoggerInterface|null $logger
     * @param bool $isTouchEnabled
     *
     * @return void
     */
    public function updateDynamicProductLabelRelations(?LoggerInterface $logger = null, bool $isTouchEnabled = true);

    /**
     * Specification:
     * - Gets product label product abstract relations by product abstract ids.
     *
     * @api
     *
     * @param array<int> $productAbstractIds
     *
     * @return array<\Generated\Shared\Transfer\ProductLabelProductAbstractTransfer>
     */
    public function getProductLabelProductAbstractsByProductAbstractIds(array $productAbstractIds): array;

    /**
     * Specification:
     * - Gets product label product abstract relations by FilterTransfer.
     * - Uses FilterTransfer for pagination.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return array<\Generated\Shared\Transfer\ProductLabelProductAbstractTransfer>
     */
    public function getProductLabelProductAbstractsByFilter(FilterTransfer $filterTransfer): array;

    /**
     * Specification:
     * - Fetches a collection of product labels from the Persistence.
     * - Uses `ProductLabelCriteriaTransfer.pagination.limit` and `ProductLabelCriteriaTransfer.pagination.offset` to paginate results with limit and offset.
     * - Returns `ProductLabelCollectionTransfer` filled with found product labels.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductLabelCriteriaTransfer $productLabelCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductLabelCollectionTransfer
     */
    public function getProductLabelCollection(
        ProductLabelCriteriaTransfer $productLabelCriteriaTransfer
    ): ProductLabelCollectionTransfer;
}
