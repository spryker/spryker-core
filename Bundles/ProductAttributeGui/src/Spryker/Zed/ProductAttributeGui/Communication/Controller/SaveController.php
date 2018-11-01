<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductAttributeGui\Communication\ProductAttributeGuiCommunicationFactory getFactory()
 */
class SaveController extends AbstractController
{
    public const PARAM_ID_PRODUCT_ABSTRACT = 'id-product-abstract';
    public const PARAM_ID_PRODUCT = 'id-product';
    public const PARAM_JSON = 'json';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function productAbstractAction(Request $request)
    {
        $idProductAbstract = $this->castId($request->get(
            static::PARAM_ID_PRODUCT_ABSTRACT
        ));

        $json = $request->request->get(static::PARAM_JSON);
        $data = json_decode($json, true);

        $this->getFactory()
            ->getProductAttributeFacade()
            ->saveAbstractAttributes($idProductAbstract, $data);

        return $this->jsonResponse([
            'success' => true,
            'message' => 'Product abstract attributes saved',
        ], 200);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function productAction(Request $request)
    {
        $idProduct = $this->castId($request->get(
            static::PARAM_ID_PRODUCT
        ));

        $json = $request->request->get(static::PARAM_JSON);
        $data = json_decode($json, true);

        $this->getFactory()
            ->getProductAttributeFacade()
            ->saveConcreteAttributes($idProduct, $data);

        return $this->jsonResponse([
            'success' => true,
            'message' => 'Product attributes saved',
        ], 200);
    }
}
