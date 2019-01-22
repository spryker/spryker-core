<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductAttributeGui\Communication\ProductAttributeGuiCommunicationFactory getFactory()
 */
class SaveController extends AbstractController
{
    public const PARAM_ID_PRODUCT_ABSTRACT = 'id-product-abstract';
    public const PARAM_ID_PRODUCT = 'id-product';
    public const PARAM_JSON = 'json';
    public const MESSAGE_INVALID_CSRF_TOKEN = 'Invalid or missing CSRF token';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function productAbstractAction(Request $request)
    {
        if (!$this->validateCsrfToken($request)) {
            return $this->sendJsonResponse(static::MESSAGE_INVALID_CSRF_TOKEN, false, 403);
        }

        $idProductAbstract = $this->castId($request->get(
            static::PARAM_ID_PRODUCT_ABSTRACT
        ));

        $json = $request->request->get(static::PARAM_JSON);
        $data = json_decode($json, true);

        $this->getFactory()
            ->getProductAttributeFacade()
            ->saveAbstractAttributes($idProductAbstract, $data);

        return $this->sendJsonResponse('Product abstract attributes saved');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function productAction(Request $request)
    {
        if (!$this->validateCsrfToken($request)) {
            return $this->sendJsonResponse(static::MESSAGE_INVALID_CSRF_TOKEN, false);
        }

        $idProduct = $this->castId($request->get(
            static::PARAM_ID_PRODUCT
        ));

        $json = $request->request->get(static::PARAM_JSON);
        $data = json_decode($json, true);

        $this->getFactory()
            ->getProductAttributeFacade()
            ->saveConcreteAttributes($idProduct, $data);

        return $this->sendJsonResponse('Product attributes saved');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    protected function validateCsrfToken(Request $request): bool
    {
        $csrfTokenName = $this->getFactory()
            ->getConfig()
            ->getCrsfTokenName();

        $csrfTokenValue = $request->request->get($csrfTokenName, '');

        $csrfForm = $this
            ->getFactory()
            ->getAttributeCsrfForm()
            ->submit([
                $csrfTokenName => $csrfTokenValue,
            ]);

        return $csrfForm->isValid();
    }

    /**
     * @param string $message
     * @param bool $isSuccess
     * @param int $statusCode
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function sendJsonResponse(string $message, bool $isSuccess = true, int $statusCode = 200): JsonResponse
    {
        return $this->jsonResponse([
            'success' => $isSuccess,
            'message' => $message,
        ], $statusCode);
    }
}
