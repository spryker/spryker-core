<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Controller;

use ArrayObject;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Service\UtilEncoding\Model\Json;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductManagement\Business\ProductManagementFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductManagement\Communication\ProductManagementCommunicationFactory getFactory()
 */
class VariantController extends AbstractController
{
    const PARAM_SKU = 'sku';
    const PARAM_ATTRIBUTE_COLLECTION = 'attribute_collection';
    const PARAM_ATTRIBUTE_GROUP = 'attribute_group';
    const PARAM_ATTRIBUTE_VALUES = 'attribute_values';
    const PARAM_LOCALIZED_ATTRIBUTE_VALUES = 'localized_attribute_values';
    const PARAM_ID_PRODUCT_CONCRETE = 'id-product';
    const PARAM_ID_PRODUCT_ABSTRACT = 'id-product-abstract';
    const PARAM_ACTIVATE = 'activate';

    /**
     * Request data:
     * - sku: test-sku
     * - localized_attribute_values[de_DE]: {"short_description":"Lorem Ipsum","long_description":"Lorem Ipsum de_DE ..."}
     * - localized_attribute_values[en_US]: {"short_description":"Lorem Ipsum","long_description":"Lorem Ipsum en_US ..."}
     * - attribute_group: {"size":"Size","color":"Color","flavour":"Flavour"}
     * - attribute_values: {"color":{"red":"Red","blue":"Blue"},"flavour":{"sweet":"Cakes"},"size":{"40":"40","41":"41"}}
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request)
    {
        $sku = trim($request->get(self::PARAM_SKU, ''));
        $attributeValuesJson = trim($request->get(self::PARAM_ATTRIBUTE_VALUES, ''));
        $localizedAttributeValuesJsonArray = $request->get(self::PARAM_LOCALIZED_ATTRIBUTE_VALUES, []);

        $localizedAttributes = [];
        $jsonUtil = new Json();
        $attributes = $jsonUtil->decode($attributeValuesJson, true) ?: [];
        foreach ($localizedAttributeValuesJsonArray as $locale => $localizedJson) {
            $localizedAttributes[$locale] = $jsonUtil->decode($localizedJson, true) ?: [];
        }

        $productAbstractTransfer = new ProductAbstractTransfer();
        $productAbstractTransfer->setSku($sku);
        $productAbstractTransfer->setAttributes([]);
        $productAbstractTransfer->setLocalizedAttributes(new ArrayObject($localizedAttributes));

        $matrix = $this->getFactory()->getProductFacade()->generateVariants($productAbstractTransfer, $attributes);

        $a = [];
        foreach ($matrix as $p) {
            $a[] = $p->toArray(true);
        }

        return new JsonResponse([
            'product_abstract' => $productAbstractTransfer->toArray(true),
            'concrete' => $a,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function activateProductVariantAction(Request $request)
    {
        $idProductConcrete = $this->castId($request->query->get(self::PARAM_ID_PRODUCT_CONCRETE));
        $idProductAbstract = $this->castId($request->query->get(self::PARAM_ID_PRODUCT_ABSTRACT));

        $this->getFactory()
            ->getProductFacade()
            ->activateProductConcrete($idProductConcrete);

        $this->addSuccessMessage('Product was activated.');
        $redirectUrl = $this->generateRedirectUrl($idProductAbstract, $idProductConcrete);

        return $this->redirectResponse($redirectUrl);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deactivateProductVariantAction(Request $request)
    {
        $idProductConcrete = $this->castId($request->query->get(self::PARAM_ID_PRODUCT_CONCRETE));
        $idProductAbstract = $this->castId($request->query->get(self::PARAM_ID_PRODUCT_ABSTRACT));

        $this->getFactory()
            ->getProductFacade()
            ->deactivateProductConcrete($idProductConcrete);

        $this->addSuccessMessage('Product was deactivated.');
        $redirectUrl = $this->generateRedirectUrl($idProductAbstract, $idProductConcrete);

        return $this->redirectResponse($redirectUrl);
    }

    /**
     * @param int $idProductAbstract
     * @param int $idProductConcrete
     *
     * @return string
     */
    protected function generateRedirectUrl($idProductAbstract, $idProductConcrete)
    {
        return Url::generate('/product-management/edit/variant', [
            EditController::PARAM_ID_PRODUCT => $idProductConcrete,
            EditController::PARAM_ID_PRODUCT_ABSTRACT => $idProductAbstract,
        ])->build();
    }
}
