<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttributeGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Spryker\Zed\ProductAttributeGui\Communication\ProductAttributeGuiCommunicationFactory getFactory()
 */
class SaveController extends AbstractController
{
    public const PARAM_ID_PRODUCT_ABSTRACT = 'id-product-abstract';
    public const PARAM_ID_PRODUCT = 'id-product';
    public const PARAM_JSON = 'json';
    public const CSRF_TOKEN_NAME = 'csrf-token';
    public const MESSAGE_INVALID_CSRF_TOKEN = 'Invalid or missing CSRF token';
    public const MESSAGE_PRODUCT_ABSTRACT_ATTRIBUTES_SAVED = 'Product abstract attributes saved';
    public const MESSAGE_PRODUCT_ATTRIBUTES_SAVED = 'Product attributes saved';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function productAbstractAction(Request $request)
    {
        if (!$this->validateCsrfToken($request)) {
            return $this->createJsonResponse(static::MESSAGE_INVALID_CSRF_TOKEN, false, Response::HTTP_FORBIDDEN);
        }

        $idProductAbstract = $this->castId($request->get(
            static::PARAM_ID_PRODUCT_ABSTRACT
        ));

        $json = $request->request->get(static::PARAM_JSON);
        $data = json_decode($json, true);

        $this->getFactory()
            ->getProductAttributeFacade()
            ->saveAbstractAttributes($idProductAbstract, $data);

        return $this->createJsonResponse(static::MESSAGE_PRODUCT_ABSTRACT_ATTRIBUTES_SAVED);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function productAction(Request $request)
    {
        if (!$this->validateCsrfToken($request)) {
            return $this->createJsonResponse(static::MESSAGE_INVALID_CSRF_TOKEN, false);
        }

        $idProduct = $this->castId($request->get(
            static::PARAM_ID_PRODUCT
        ));

        $json = $request->request->get(static::PARAM_JSON);
        $data = json_decode($json, true);

        $this->getFactory()
            ->getProductAttributeFacade()
            ->saveConcreteAttributes($idProduct, $data);

        return $this->createJsonResponse(static::MESSAGE_PRODUCT_ATTRIBUTES_SAVED);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    protected function validateCsrfToken(Request $request): bool
    {
        $csrfTokenValue = $request->request->get(static::CSRF_TOKEN_NAME, '');

        $csrfForm = $this
            ->getFactory()
            ->getAttributeCsrfForm()
            ->submit([
                static::CSRF_TOKEN_NAME => $csrfTokenValue,
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
    protected function createJsonResponse(string $message, bool $isSuccess = true, int $statusCode = Response::HTTP_OK): JsonResponse
    {
        return $this->jsonResponse([
            'success' => $isSuccess,
            'message' => $message,
        ], $statusCode);
    }
}
