<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product;

use Generated\Shared\Transfer\LocalizedAttributesTransfer;

class ProductUrlGenerator implements ProductUrlGeneratorInterface
{

    /**
     * @var \Spryker\Zed\Product\Business\Product\ProductManagerInterface
     */
    protected $productManager;

    /**
     * @param \Spryker\Zed\Product\Business\Product\ProductManagerInterface $productManager
     */
    public function __construct(ProductManagerInterface $productManager)
    {
        $this->productManager = $productManager;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function createAndTouchProductUrls($idProductAbstract)
    {
        $productAbstractTransfer = $this->productManager->getProductAbstractById($idProductAbstract);

        foreach ($productAbstractTransfer->getLocalizedAttributes() as $localizedAttributes) {
            $productAbstractUrl = $this->generateProductUrl(
                $localizedAttributes,
                $productAbstractTransfer->getIdProductAbstract()
            );

            $this->productManager->createAndTouchProductUrlByIdProduct(
                $productAbstractTransfer->getIdProductAbstract(),
                $productAbstractUrl,
                $localizedAttributes->getLocale()
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\LocalizedAttributesTransfer $localizedAttributes
     * @param int $idProductAbstract
     *
     * @return string
     */
    public function generateProductUrl(LocalizedAttributesTransfer $localizedAttributes, $idProductAbstract)
    {
        $productName = $this->slugify($localizedAttributes->getName());

        return '/' . mb_substr($localizedAttributes->getLocale()->getLocaleName(), 0, 2) . '/' . $productName . '-' . $idProductAbstract;
    }

    /**
     * @param string $value
     *
     * @return string
     */
    protected function slugify($value)
    {
        if (function_exists('iconv')) {
            $value = iconv('UTF-8', 'ASCII//TRANSLIT', $value);
        }

        $value = preg_replace("/[^a-zA-Z0-9 -]/", "", $value);
        $value = strtolower($value);
        $value = str_replace(' ', '-', $value);

        return $value;
    }
}
