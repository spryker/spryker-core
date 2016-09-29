<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Controller;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Shared\Library\Json;
use Spryker\Shared\Url\Url;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\ProductManagement\Business\Product\VariantGenerator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductManagement\Business\ProductManagementFacade getFacade()
 * @method \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainer getQueryContainer()
 * @method \Spryker\Zed\ProductManagement\Communication\ProductManagementCommunicationFactory getFactory()
 */
class VariantController extends AbstractController
{

    const PARAM_SKU = 'sku';
    const PARAM_ATTRIBUTE_COLLECTION = 'attribute_collection';
    const PARAM_ATTRIBUTE_GROUP = 'attribute_group';
    const PARAM_ATTRIBUTE_VALUES = 'attribute_values';
    const PARAM_LOCALIZED_ATTRIBUTE_VALUES = 'localized_attribute_values';
    const PARAM_ID_PRODUCT_CONCRETE = 'id_product_concrete';
    const PARAM_ID_PRODUCT_ABSTRACT = 'id_product_abstract';
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
        $attributes = Json::decode($attributeValuesJson, true) ?: [];
        foreach ($localizedAttributeValuesJsonArray as $locale => $localizedJson) {
            $localizedAttributes[$locale] = Json::decode($localizedJson, true) ?: [];
        }

        $productAbstractTransfer = new ProductAbstractTransfer();
        $productAbstractTransfer->setSku($sku);
        $productAbstractTransfer->setAttributes([]);
        $productAbstractTransfer->setLocalizedAttributes(new \ArrayObject($localizedAttributes));

        $matrixGenerator = new VariantGenerator();
        $matrix = $matrixGenerator->generate($productAbstractTransfer, $attributes);

        $a = [];
        foreach ($matrix as $p) {
            $a[] = $p->toArray(true);
        }

        return new JsonResponse([
            'product_abstract' => $productAbstractTransfer->toArray(true),
            'concrete' => $a
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function toggleEnableProductVariantAction(Request $request)
    {
        $idProductConcrete = $this->castId($request->query->get(self::PARAM_ID_PRODUCT_CONCRETE));
        $idProductAbstract = $this->castId($request->query->get(self::PARAM_ID_PRODUCT_ABSTRACT));

        $enable = (bool)$request->query->get(self::PARAM_ACTIVATE);

        if ($enable) {
            $updateStatus = $this->getFactory()
                ->getProductFacade()
                ->activateProductConcrete($idProductConcrete);
        } else {
            $updateStatus = $this->getFactory()
                ->getProductFacade()
                ->deActivateProductConcrete($idProductConcrete);
        }

        $redirectUrl = Url::generate(
            '/product-management/edit/variant',
            [
                EditController::PARAM_ID_PRODUCT => $idProductConcrete,
                EditController::PARAM_ID_PRODUCT_ABSTRACT => $idProductAbstract

            ]
        )->build();

        if ($updateStatus) {
            $this->addSuccessMessage('Product variant successfully updated.');
        } else {
            $this->addErrorMessage('Failed to change product visibility.');
        }

        return $this->redirectResponse($redirectUrl);
    }
}
