<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

class ResponseFactory
{
    /**
     * @param string $message
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function createErrorJsonResponse(string $message): JsonResponse
    {
        $builder = $this->createResponseBuilder();

        $builder->addNotification(ResponseBuilder::RESPONSE_TYPE_ERROR, $message);

        return new JsonResponse($builder->build());
    }

    /**
     * @param string $postActionType
     * @param string $message
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function createSuccessJsonResponse(string $postActionType, string $message): JsonResponse
    {
        $builder = $this->createResponseBuilder();

        $builder->addAction($postActionType);
        $builder->addNotification(ResponseBuilder::RESPONSE_TYPE_SUCCESS, $message);

        return new JsonResponse($builder->build());
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\Response\ResponseBuilder
     */
    public function createResponseBuilder(): ResponseBuilder
    {
        return new ResponseBuilder();
    }
}
