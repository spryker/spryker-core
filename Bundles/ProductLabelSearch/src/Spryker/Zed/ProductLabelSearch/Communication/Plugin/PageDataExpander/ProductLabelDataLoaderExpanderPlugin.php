<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelSearch\Communication\Plugin\PageDataExpander;

use Generated\Shared\Transfer\ProductPageSearchTransfer;
use Generated\Shared\Transfer\ProductPayloadTransfer;
use Spryker\Shared\ProductPageSearch\ProductPageSearchConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductPageSearch\Dependency\Plugin\ProductPageDataExpanderInterface;

/**
 * @method \Spryker\Zed\ProductLabelSearch\Communication\ProductLabelSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductLabelSearch\ProductLabelSearchConfig getConfig()
 * @method \Spryker\Zed\ProductLabelSearch\Persistence\ProductLabelSearchQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductLabelSearch\Business\ProductLabelSearchFacadeInterface getFacade()
 */
class ProductLabelDataLoaderExpanderPlugin extends AbstractPlugin implements ProductPageDataExpanderInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $productData
     * @param \Generated\Shared\Transfer\ProductPageSearchTransfer $productAbstractPageSearchTransfer
     *
     * @return void
     */
    public function expandProductPageData(array $productData, ProductPageSearchTransfer $productAbstractPageSearchTransfer)
    {
        $productAbstractPageSearchTransfer->setLabelIds(
            $this->getLabelIds($productData, $productAbstractPageSearchTransfer)
        );
    }

    /**
     * @param array $productData
     * @param \Generated\Shared\Transfer\ProductPageSearchTransfer $productAbstractPageSearchTransfer
     *
     * @return array
     */
    protected function getLabelIds(array $productData, ProductPageSearchTransfer $productAbstractPageSearchTransfer)
    {
        $productPayloadTransfer = $this->getProductPayloadTransfer($productData);

        return $productPayloadTransfer->getLabelIds()[$productAbstractPageSearchTransfer->getStore()] ?? [];
    }

    /**
     * @param array $productData
     *
     * @return \Generated\Shared\Transfer\ProductPayloadTransfer
     */
    protected function getProductPayloadTransfer(array $productData): ProductPayloadTransfer
    {
        return $productData[ProductPageSearchConfig::PRODUCT_ABSTRACT_PAGE_LOAD_DATA];
    }
}
