<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeGui\Communication\Controller;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Propel\Runtime\Formatter\SimpleArrayFormatter;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductAttributeGui\Business\ProductAttributeGuiFacade getFacade()
 * @method \Spryker\Zed\ProductAttributeGui\Communication\ProductAttributeGuiCommunicationFactory getFactory()
 */
class AttributeController extends AbstractController
{

    const PARAM_ID_PRODUCT_ABSTRACT = 'id-product-abstract';
    const PARAM_ID = 'id';
    const PARAM_SEARCH_TEXT = 'q';
    const PARAM_TERM = 'term';
    const PARAM_LOCALE_CODE = 'locale_code';

    /**
     * @return array
     */
    public function indexAction()
    {
        $productAttributeTable = $this
            ->getFactory()
            ->createProductAttributeTable();

        return $this->viewResponse([
            'attributeTable' => $productAttributeTable->render(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function manageitAction(Request $request)
    {
        $idProductAbstract = $this->castId($request->get(
            static::PARAM_ID_PRODUCT_ABSTRACT
        ));

        $productAbstractTransfer = $this->getFactory()
            ->getProductFacade()
            ->findProductAbstractById($idProductAbstract);

        if (!$productAbstractTransfer) {
            $this->addErrorMessage(sprintf('The product [%s] does not exist.', $idProductAbstract));

            return new RedirectResponse('/product-management');
        }

        $productAttributeValues = $this->getAttributesForProductAbstract($productAbstractTransfer);

        $attributes = $this->getQueryContainer()
            ->queryProductAttributeValues()
            ->setFormatter(new SimpleArrayFormatter())
            ->find()
            ->toArray();

        $locales = $this->getFactory()
            ->getLocaleFacade()
            ->getLocaleCollection();

        print_r($attributes);
        die;

        return $this->viewResponse([
            'attributes' => $attributes,
            'productAttributeValues' => $productAttributeValues,
            'locales' => $locales,
        ]);
    }

    /**
     * @return array
     */
    protected function getAttributesForProductAbstract(ProductAbstractTransfer $productAbstractTransfer)
    {
        $localizedAttributes = [];
        foreach ($productAbstractTransfer->getLocalizedAttributes() as $localizedAttributesTransfer) {
            $localizedAttributes[$localizedAttributesTransfer->getLocale()->getLocaleName()] = $localizedAttributesTransfer->getAttributes();
        }

        return ['default' => $productAbstractTransfer->getAttributes()] + $localizedAttributes;
    }

}
