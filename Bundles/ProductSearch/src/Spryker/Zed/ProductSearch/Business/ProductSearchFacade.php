<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Business;

use Generated\Shared\Transfer\PageMapTransfer;
use Generated\Shared\Transfer\ProductSearchPreferencesTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilderInterface;

/**
 * @method \Spryker\Zed\ProductSearch\Business\ProductSearchBusinessFactory getFactory()
 */
class ProductSearchFacade extends AbstractFacade implements ProductSearchFacadeInterface
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
    public function mapDynamicProductAttributes(PageMapBuilderInterface $pageMapBuilder, PageMapTransfer $pageMapTransfer, array $attributes)
    {
        return $this
            ->getFactory()
            ->createSearchProductAttributeMapper()
            ->mapDynamicProductAttributes($pageMapBuilder, $pageMapTransfer, $attributes);
    }

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
    public function activateProductSearch($idProduct, array $localeCollection)
    {
        $this->getFactory()
            ->createProductSearchMarker()
            ->activateProductSearch($idProduct, $localeCollection);
    }

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
    public function deactivateProductSearch($idProduct, array $localeCollection)
    {
        $this->getFactory()
            ->createProductSearchMarker()
            ->deactivateProductSearch($idProduct, $localeCollection);
    }

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
    public function saveProductSearchPreferences(ProductSearchPreferencesTransfer $productSearchPreferencesTransfer)
    {
        $this
            ->getFactory()
            ->createSearchPreferencesSaver()
            ->save($productSearchPreferencesTransfer);
    }

}
