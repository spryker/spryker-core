<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Communication\Plugin\PageDataExpander;

use Generated\Shared\Transfer\ProductPageSearchTransfer;
use Spryker\Shared\ProductPageSearch\ProductPageSearchConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductPageSearch\Dependency\Plugin\ProductPageDataExpanderInterface;

/**
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductPageSearch\Communication\ProductPageSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacadeInterface getFacade()
 */
class ProductImagePageDataLoaderExpanderPlugin extends AbstractPlugin implements ProductPageDataExpanderInterface
{
    /**
     * @api
     *
     * @param array $productData
     * @param \Generated\Shared\Transfer\ProductPageSearchTransfer $productAbstractPageSearchTransfer
     *
     * @return void
     */
    public function expandProductPageData(array $productData, ProductPageSearchTransfer $productAbstractPageSearchTransfer)
    {
        $images = [];
        $imageSets = $productData[ProductPageSearchConfig::PRODUCT_ABSTRACT_PAGE_LOAD_DATA]->getImages();
        $imageSetsByLocale = $imageSets[$productData['fk_locale']] ?? [];

        /** @var \Orm\Zed\ProductImage\Persistence\SpyProductImageSet[] $imageSetsByLocale */
        foreach ($imageSetsByLocale as $imageSet) {
            $images = array_merge($images, $this->generateImages($imageSet->getSpyProductImageSetToProductImages()));
        }

        $productAbstractPageSearchTransfer->setProductImages($images);
    }

    /**
     * @param \Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImage[] $imagesCollection
     *
     * @return array
     */
    protected function generateImages($imagesCollection)
    {
        $result = [];

        foreach ($imagesCollection as $image) {
            $imageArray = $image->getSpyProductImage()->toArray();
            $imageArray += $image->toArray();
            $result[] = $imageArray;
        }

        return $result;
    }
}
