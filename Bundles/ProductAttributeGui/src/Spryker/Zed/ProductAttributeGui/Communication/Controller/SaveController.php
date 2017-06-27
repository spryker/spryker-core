<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeGui\Communication\Controller;

use Exception;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

/**
 * @method \Spryker\Zed\ProductAttributeGui\Business\ProductAttributeGuiFacade getFacade()
 * @method \Spryker\Zed\ProductAttributeGui\Communication\ProductAttributeGuiCommunicationFactory getFactory()
 */
class SaveController extends AbstractController
{

    const PARAM_ID_PRODUCT_ABSTRACT = 'id-product-abstract';
    const PARAM_JSON = 'json';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request)
    {
        $idProductAbstract = $this->castId($request->get(
            static::PARAM_ID_PRODUCT_ABSTRACT
        ));

        $json = $request->request->get(static::PARAM_JSON);
        $data = json_decode($json, true);

        try {
            $this->getFacade()->updateProductAbstractAttributes($idProductAbstract, $data);
            $result = true;
            $errorMessage = '';
        } catch (Exception $exception) {
            $result = false;
            $errorMessage = $exception->getMessage();
        } catch (Throwable $exception) {
            $result = false;
            $errorMessage = $exception->getMessage();
        }

        return new JsonResponse([
            'success' => $result,
            'errorMessage' => $errorMessage,
        ]);
    }

}
