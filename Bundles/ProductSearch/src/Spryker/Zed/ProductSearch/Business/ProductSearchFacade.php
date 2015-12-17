<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductSearch\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Shared\Kernel\Messenger\MessengerInterface;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method ProductSearchBusinessFactory getFactory()
 */
class ProductSearchFacade extends AbstractFacade
{

    /**
     * @param array $productsRaw
     * @param array $processedProducts
     *
     * @return array
     */
    public function enrichProductsWithSearchAttributes(array $productsRaw, array $processedProducts)
    {
        return $this->getFactory()
            ->getProductAttributesTransformer()
            ->buildProductAttributes($productsRaw, $processedProducts);
    }

    /**
     * @param array $productsRaw
     * @param array $processedProducts
     * @param LocaleTransfer $locale
     *
     * @return array
     */
    public function createSearchProducts(array $productsRaw, array $processedProducts, LocaleTransfer $locale)
    {
        return $this->getFactory()
            ->getProductSearchProcessor()
            ->buildProducts($productsRaw, $processedProducts, $locale);
    }

    /**
     * @param MessengerInterface $messenger
     *
     * @return void
     */
    public function install(MessengerInterface $messenger)
    {
        $this->getFactory()->getInstaller($messenger)->install();
    }

}
