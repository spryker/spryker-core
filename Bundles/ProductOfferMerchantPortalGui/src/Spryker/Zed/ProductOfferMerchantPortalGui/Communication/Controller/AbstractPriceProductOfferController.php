<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ProductOfferMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductOfferMerchantPortalGui\Persistence\ProductOfferMerchantPortalGuiRepositoryInterface getRepository()
 */
class AbstractPriceProductOfferController extends AbstractController
{
    /**
     * @var string
     */
    protected const RESPONSE_NOTIFICATION_MESSAGE_SUCCESS = 'Success.';
    /**
     * @var string
     */
    protected const RESPONSE_NOTIFICATION_MESSAGE_ERROR = 'Something went wrong, please try again.';

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function createSuccessJsonResponse(): JsonResponse
    {
        $zedUiFormResponseTransfer = $this->getFactory()
            ->getZedUiFactory()
            ->createZedUiFormResponseBuilder()
            ->addSuccessNotification(
                $this->getFactory()->getTranslatorFacade()->trans(static::RESPONSE_NOTIFICATION_MESSAGE_SUCCESS)
            )
            ->addActionRefreshTable()
            ->createResponse();

        return new JsonResponse($zedUiFormResponseTransfer->toArray());
    }

    /**
     * @param string|null $message
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function createErrorJsonResponse(?string $message = null): JsonResponse
    {
        $message = $this->getFactory()->getTranslatorFacade()->trans(
            $message ?? static::RESPONSE_NOTIFICATION_MESSAGE_ERROR
        );

        $zedUiFormResponseTransfer = $this->getFactory()
            ->getZedUiFactory()
            ->createZedUiFormResponseBuilder()
            ->addErrorNotification($message)
            ->createResponse();

        return new JsonResponse($zedUiFormResponseTransfer->toArray());
    }
}
