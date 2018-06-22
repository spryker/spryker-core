<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business;

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
    public function findLabelByLabelName($labelName);

    /**
     * Specification:
     * - Finds all existing product labels in the database
     * - Returns a collection of product-label transfers
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer[]
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
     * @return \Generated\Shared\Transfer\ProductLabelTransfer[]
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
     * @return int[]
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
     * @return int[]
     */
    public function findActiveLabelIdsByIdProductAbstract($idProductAbstract);

    /**
     * Specification:
     * - Persists new product-label entity to database
     * - Touches product-label dictionary active
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
     * - Persists product-label changes to database
     * - Touches product-label dictionary active
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @throws \Spryker\Zed\ProductLabel\Business\Exception\MissingProductLabelException
     *
     * @return void
     */
    public function updateLabel(ProductLabelTransfer $productLabelTransfer);

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
     * @return int[]
     */
    public function findProductAbstractRelationsByIdProductLabel($idProductLabel);

    /**
     * Specification:
     * - Persists relations for the given product-label ID and list of abstract-product IDs to database
     *
     * @api
     *
     * @param int $idProductLabel
     * @param int[] $idsProductAbstract
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
     * @param int[] $idsProductAbstract
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
     * - The results of the plugins are used to persist product label relation changes into database.
     *
     * @api
     *
     * @param \Psr\Log\LoggerInterface|null $logger
     *
     * @return void
     */
    public function updateDynamicProductLabelRelations(?LoggerInterface $logger = null);
}
