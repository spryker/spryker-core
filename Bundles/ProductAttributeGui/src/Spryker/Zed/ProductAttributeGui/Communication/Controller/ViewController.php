<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeGui\Communication\Controller;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductAttributeGui\Business\ProductAttributeGuiFacade getFacade()
 * @method \Spryker\Zed\ProductAttributeGui\Communication\ProductAttributeGuiCommunicationFactory getFactory()
 */
class ViewController extends AbstractController
{

    const PARAM_ID_PRODUCT_ABSTRACT = 'id-product-abstract';
    const PARAM_ID_PRODUCT = 'id-product';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function productAbstractAction(Request $request)
    {
        $idProductAbstract = $this->castId($request->get(
            static::PARAM_ID_PRODUCT_ABSTRACT
        ));

        $dataProvider = $this->getFactory()->createAttributeKeyFormDataProvider();
        $form = $this
            ->getFactory()
            ->createAttributeKeyForm(
                $dataProvider->getData(),
                $dataProvider->getOptions()
            )
            ->handleRequest($request);

        $values = $this
            ->getFacade()
            ->getProductAbstractAttributeValues($idProductAbstract);

        $productAbstractTransfer = $this->getFacade()->getProductAbstract($idProductAbstract);

        $metaAttributes = $this
            ->getFacade()
            ->getMetaAttributesForProductAbstract($idProductAbstract);

        $localesData = $this->getLocaleData();

        return $this->viewResponse([
            'idProductAbstract' => $idProductAbstract,
            'attributeKeyForm' => $form->createView(),
            'locales' => $this->getLocaleData(),
            'metaAttributes' => $metaAttributes,
            'productAttributeValues' => $values,
            'localesJson' => json_encode($localesData),
            'productAttributeValuesJson' => json_encode($values),
            'metaAttributesJson' => json_encode($metaAttributes),
            'productAbstract' => $productAbstractTransfer,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function productAction(Request $request)
    {
        $idProduct = $this->castId($request->get(
            static::PARAM_ID_PRODUCT
        ));

        $dataProvider = $this->getFactory()->createAttributeKeyFormDataProvider();
        $form = $this
            ->getFactory()
            ->createAttributeKeyForm(
                $dataProvider->getData(),
                $dataProvider->getOptions()
            )
            ->handleRequest($request);

        $values = $this
            ->getFacade()
            ->getProductAttributeValues($idProduct);

        $productTransfer = $this->getFacade()->getProduct($idProduct);
        $productAbstractTransfer = $this->getFacade()->getProductAbstract($productTransfer->getFkProductAbstract());

        $metaAttributes = $this
            ->getFacade()
            ->getMetaAttributesForProduct($idProduct);

        $localesData = $this->getLocaleData();

        return $this->viewResponse([
            'attributeKeyForm' => $form->createView(),
            'locales' => $localesData,
            'metaAttributes' => $metaAttributes,
            'productAttributeValues' => $values,
            'localesJson' => json_encode($localesData),
            'productAttributeValuesJson' => json_encode($values),
            'metaAttributesJson' => json_encode($metaAttributes),
            'productAbstract' => $productAbstractTransfer,
            'product' => $productTransfer,
        ]);
    }

    /**
     * @return array
     */
    protected function getLocaleData()
    {
        $locales = $this->getFactory()
            ->getLocaleFacade()
            ->getLocaleCollection();

        $localeTransfer = new LocaleTransfer();
        $localeTransfer->setIdLocale('_');
        $localeTransfer->setLocaleName('_');

        $locales['_'] = $localeTransfer;

        $localesData = [];
        foreach ($locales as $localeCode => $localeTransfer) {
            $localesData[$localeTransfer->getIdLocale()] = $localeTransfer->toArray();
        }

        ksort($localesData);

        return $localesData;
    }

}
