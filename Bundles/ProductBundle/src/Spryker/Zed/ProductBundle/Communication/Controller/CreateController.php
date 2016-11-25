<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Communication\Controller;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductBundleTransfer;
use Generated\Shared\Transfer\ProductForBundleTransfer;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductBundle\Communication\ProductBundleCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainer getQueryContainer()
 * @method \Spryker\Zed\ProductBundle\Business\ProductBundleFacade getFacade()
 */
class CreateController extends BaseOptionController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {

        $productBundleTransfer = new ProductBundleTransfer();

        $productAbstractTransfer = new ProductAbstractTransfer();
        $productAbstractTransfer->setSku('sku123' . rand(1,999));
        $productAbstractTransfer->setIdTaxSet(1);

        $localizedAttributesTransfer = new LocalizedAttributesTransfer();
        $localizedAttributesTransfer->setDescription('Desription english');
        $localizedAttributesTransfer->setMetaDescription('Meta description english');
        $localizedAttributesTransfer->setMetaKeywords('one, two, three');
        $localizedAttributesTransfer->setMetaTitle('Meta title english');
        $localizedAttributesTransfer->setName('name english');

        $localeTransfer = new LocaleTransfer();
        $localeTransfer->setIdLocale(66);
        $localizedAttributesTransfer->setLocale($localeTransfer);

        $productAbstractTransfer->addLocalizedAttributes($localizedAttributesTransfer);

        $priceProductTransfer = new PriceProductTransfer();
        $priceProductTransfer->setPrice(1000);
        $priceProductTransfer->setPriceTypeName('DEFAULT');
        $productAbstractTransfer->setPrice($priceProductTransfer);

        $productBundleTransfer->setProductAbstract($productAbstractTransfer);

        $productsToBeAsigned = new \ArrayObject();

        $productForBundleTransfer = new ProductForBundleTransfer();
        $productForBundleTransfer->setQuantity(2);
        $productForBundleTransfer->setIdProductConcrete(1);
        $productsToBeAsigned->append($productForBundleTransfer);

        $productForBundleTransfer = new ProductForBundleTransfer();
        $productForBundleTransfer->setQuantity(1);
        $productForBundleTransfer->setIdProductConcrete(2);
        $productsToBeAsigned->append($productForBundleTransfer);

        $productForBundleTransfer = new ProductForBundleTransfer();
        $productForBundleTransfer->setQuantity(1);
        $productForBundleTransfer->setIdProductConcrete(3);
        $productsToBeAsigned->append($productForBundleTransfer);

        $productBundleTransfer->setProductsToBeAssigned($productsToBeAsigned);

        $this->getFacade()->addProductBundle($productBundleTransfer);
    }

}

