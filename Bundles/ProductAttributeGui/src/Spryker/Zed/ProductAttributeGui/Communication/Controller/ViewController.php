<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeGui\Communication\Controller;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
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
            ->getFactory()
            ->getProductAttributeFacade()
            ->getProductAbstractAttributeValues($idProductAbstract);

        $valueKeys = $this
            ->getFactory()
            ->getProductAttributeFacade()
            ->extractKeysFromAttributes($values);

        $productAbstractTransfer = $this->getProductAbstractTransfer($idProductAbstract);

        $metaAttributes = $this
            ->getFactory()
            ->getProductAttributeFacade()
            ->getMetaAttributesForProductAbstract($idProductAbstract);

        $localesData = $this->getLocaleData();

        return $this->viewResponse([
            'idProductAbstract' => $idProductAbstract,
            'attributeKeyForm' => $form->createView(),
            'locales' => $this->getLocaleData(),
            'metaAttributes' => $metaAttributes,
            'productAttributeValues' => $values,
            'productAttributeKeys' => $valueKeys,
            'localesJson' => json_encode(array_values($localesData)),
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
            ->getFactory()
            ->getProductAttributeFacade()
            ->getProductAttributeValues($idProduct);

        $valueKeys = $this
            ->getFactory()
            ->getProductAttributeFacade()
            ->extractKeysFromAttributes($values);

        $productTransfer = $this->getProductTransfer($idProduct);
        $productAbstractTransfer = $this->getProductAbstractTransfer(
            $productTransfer->requireFkProductAbstract()->getFkProductAbstract()
        );

        $metaAttributes = $this
            ->getFactory()
            ->getProductAttributeFacade()
            ->getMetaAttributesForProduct($idProduct);

        $localesData = $this->getLocaleData();

        return $this->viewResponse([
            'attributeKeyForm' => $form->createView(),
            'locales' => $localesData,
            'metaAttributes' => $metaAttributes,
            'productAttributeValues' => $values,
            'productAttributeKeys' => $valueKeys,
            'localesJson' => json_encode(array_values($localesData)),
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

        array_unshift($locales, $this->getDefaultLocaleTransfer());

        $localesData = [];
        foreach ($locales as $localeCode => $localeTransfer) {
            $localesData[$localeTransfer->getIdLocale()] = $localeTransfer->toArray();
        }

        ksort($localesData);

        return $localesData;
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function getDefaultLocaleTransfer()
    {
        $defaultLocaleCode = $this->getFactory()
            ->getConfig()
            ->getDefaultLocaleCode();

        $localeTransfer = (new LocaleTransfer())
            ->setIdLocale($defaultLocaleCode)
            ->setLocaleName($defaultLocaleCode);

        return $localeTransfer;
    }

    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function getProductTransfer($idProduct)
    {
        $entity = $this->getFactory()
            ->getProductQueryContainer()
            ->queryProduct()
            ->filterByIdProduct($idProduct)
            ->joinSpyProductLocalizedAttributes()
            ->findOne();

        $productTransfer = new ProductConcreteTransfer();

        if (!$entity) {
            return $productTransfer;
        }

        $productTransfer->setIdProductConcrete($entity->getIdProduct());
        $productTransfer->setFkProductAbstract($entity->getFkProductAbstract());
        $productTransfer->setSku($entity->getSku());

        return $productTransfer;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected function getProductAbstractTransfer($idProductAbstract)
    {
        $entity = $this->getFactory()
            ->getProductQueryContainer()
            ->queryProductAbstract()
            ->filterByIdProductAbstract($idProductAbstract)
            ->joinSpyProductAbstractLocalizedAttributes()
            ->findOne();

        $productAbstractTransfer = new ProductAbstractTransfer();

        if (!$entity) {
            return $productAbstractTransfer;
        }

        $productAbstractTransfer->setIdProductAbstract($entity->getIdProductAbstract());
        $productAbstractTransfer->setSku($entity->getSku());

        return $productAbstractTransfer;
    }

}
