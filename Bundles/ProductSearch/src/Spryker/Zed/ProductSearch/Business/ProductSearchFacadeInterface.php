<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Business;

use Generated\Shared\Transfer\PageMapTransfer;
use Generated\Shared\Transfer\ProductSearchAttributeTransfer;
use Generated\Shared\Transfer\ProductSearchPreferencesTransfer;
use Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilderInterface;

interface ProductSearchFacadeInterface
{

    /**
     * Specification:
     * - Iterates through the given product attribute associative array where the key is the name and the value is the value of the attributes
     * - If an attribute is configured to be mapped in the page map builder, then it's value will be added to the page map
     * - The data of the returned page map represents a hydrated Elasticsearch document with all the necessary attribute values
     *
     * @api
     *
     * @param \Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilderInterface $pageMapBuilder
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param array $attributes
     *
     * @return \Generated\Shared\Transfer\PageMapTransfer
     */
    public function mapDynamicProductAttributes(PageMapBuilderInterface $pageMapBuilder, PageMapTransfer $pageMapTransfer, array $attributes);

    /**
     * Specification:
     * - Marks the given product to be searchable
     * - Touches the product so next time the collector runs it will process it
     *
     * @api
     *
     * @param int $idProduct
     * @param \Generated\Shared\Transfer\LocaleTransfer[] $localeCollection
     *
     * @return void
     */
    public function activateProductSearch($idProduct, array $localeCollection);

    /**
     * Specification:
     * - Marks the given product to not to be searchable
     * - Touches the product so next time the collector will process it
     *
     * @api
     *
     * @param int $idProduct
     * @param \Generated\Shared\Transfer\LocaleTransfer[] $localeCollection
     *
     * @return void
     */
    public function deactivateProductSearch($idProduct, array $localeCollection);

    /**
     * Specification:
     * - For the given product attribute the search preferences will be updated
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductSearchPreferencesTransfer $productSearchPreferencesTransfer
     *
     * @return void
     */
    public function saveProductSearchPreferences(ProductSearchPreferencesTransfer $productSearchPreferencesTransfer);

    /**
     * Specification:
     * - Returns a filtered list of keys that exists in the persisted product attribute key list but not in the persisted
     * product search attribute list
     *
     * @api
     *
     * @param string $searchText
     * @param int $limit
     *
     * @return array
     */
    public function suggestUnusedSearchKeys($searchText = '', $limit = 10);

    /**
     * TODO: add specification
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductSearchAttributeTransfer $productSearchAttributeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSearchAttributeTransfer
     */
    public function createProductSearchAttribute(ProductSearchAttributeTransfer $productSearchAttributeTransfer);

    /**
     * TODO: add specification
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductSearchAttributeTransfer $productSearchAttributeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSearchAttributeTransfer
     */
    public function updateProductSearchAttribute(ProductSearchAttributeTransfer $productSearchAttributeTransfer);

    /**
     * TODO: add specification
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductSearchAttributeTransfer $productSearchAttributeTransfer
     *
     * @return void
     */
    public function deleteProductSearchAttribute(ProductSearchAttributeTransfer $productSearchAttributeTransfer);

    /**
     * Specification:
     * - Reads a product search attribute entity from the database and returns a fully hydrated transfer representation
     * - Return null if the entity is not found by id
     *
     * @api
     *
     * @param int $idProductSearchAttribute
     *
     * @return \Generated\Shared\Transfer\ProductSearchAttributeTransfer|null
     */
    public function getProductSearchAttribute($idProductSearchAttribute);

    /**
     * TODO: add specification
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ProductSearchAttributeTransfer[]
     */
    public function getProductSearchAttributeList();

    /**
     * TODO: add specification
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductSearchAttributeTransfer[] $productSearchAttributes
     *
     * @return void
     */
    public function updateProductSearchAttributeOrder(array $productSearchAttributes);

}
