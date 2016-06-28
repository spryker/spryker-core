<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Controller;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Shared\Library\Json;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\ProductManagement\Business\Product\MatrixGenerator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductManagement\Business\ProductManagementFacade getFacade()
 * @method \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainer getQueryContainer()
 * @method \Spryker\Zed\ProductManagement\Communication\ProductManagementCommunicationFactory getFactory()
 */
class MatrixController extends AbstractController
{

    const PARAM_SKU = 'sku';
    const PARAM_ATTRIBUTE_COLLECTION = 'attribute_collection';
    const PARAM_ATTRIBUTE_GROUP = 'attribute_group';
    const PARAM_ATTRIBUTE_VALUES = 'attribute_values';
    const PARAM_LOCALIZED_ATTRIBUTE_VALUES = 'localized_attribute_values';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request)
    {

        $sku = trim((string)$request->get(
            self::PARAM_SKU
        ));

        $attributeValuesJson = trim((string)$request->get(
            self::PARAM_ATTRIBUTE_VALUES
        ));

        $localizedAttributeValuesJsonArray  = $request->get(
            self::PARAM_LOCALIZED_ATTRIBUTE_VALUES
        );

        $attributeCollectionJson = trim((string)$request->get(
            self::PARAM_ATTRIBUTE_COLLECTION
        ));

        $localizedAttributes = [];
        $attributes = Json::decode($attributeValuesJson, true) ?: [];
        foreach ($localizedAttributeValuesJsonArray as $locale => $localizedJson) {
            $localizedAttributes[$locale] = Json::decode($localizedJson, true) ?: [];
        }
        $attributeCollection = Json::decode($attributeCollectionJson, true) ?: [];

        $productAbstractTransfer = new ProductAbstractTransfer();
        $productAbstractTransfer->setSku($sku);
        $productAbstractTransfer->setAttributes($attributes);
        $productAbstractTransfer->setLocalizedAttributes(new \ArrayObject($localizedAttributes));

        $matrixGenerator = new MatrixGenerator();
        $matrix = $matrixGenerator->generate($productAbstractTransfer, $attributeCollection);

        sd($productAbstractTransfer->toArray(true));
        die;
        //return new JsonResponse([]);
    }


}
