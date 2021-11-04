<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Controller;

use ArrayObject;
use Generated\Shared\Transfer\PriceProductTableViewTransfer;
use Generated\Shared\Transfer\ValidationResponseTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductMerchantPortalGui\Communication\ProductMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepositoryInterface getRepository()
 */
abstract class AbstractDeletePriceProductController extends AbstractController
{
    /**
     * @var string
     */
    protected const RESPONSE_NOTIFICATION_MESSAGE_SUCCESS = 'Success! The Price is deleted.';

    /**
     * @var string
     */
    protected const RESPONSE_NOTIFICATION_MESSAGE_ERROR = 'Something went wrong, please try again.';

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param array<int> $priceProductDefaultIds
     * @param int $volumeQuantity
     *
     * @return \Generated\Shared\Transfer\ValidationResponseTransfer
     */
    protected function deletePrices(
        ArrayObject $priceProductTransfers,
        array $priceProductDefaultIds,
        int $volumeQuantity
    ): ValidationResponseTransfer {
        $priceProductTransfersToRemove = $this->filterPriceProductTransfersByPriceProductDefaultIds(
            $priceProductTransfers,
            $priceProductDefaultIds,
        );

        return $this->getFactory()
            ->createPriceDeleter()
            ->deletePrices($priceProductTransfersToRemove, $volumeQuantity);
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param array<int> $priceProductDefaultIds
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    protected function filterPriceProductTransfersByPriceProductDefaultIds(
        ArrayObject $priceProductTransfers,
        array $priceProductDefaultIds
    ): array {
        $priceProductTransfersToRemove = [];

        foreach ($priceProductTransfers as $priceProductTransfer) {
            $idPriceProductDefault = (int)$priceProductTransfer
                ->getPriceDimensionOrFail()
                ->getIdPriceProductDefault();

            if (in_array($idPriceProductDefault, $priceProductDefaultIds)) {
                $priceProductTransfersToRemove[] = $priceProductTransfer;
            }
        }

        return $priceProductTransfersToRemove;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<int>
     */
    protected function getDefaultPriceProductIds(Request $request): array
    {
        return array_map(
            'intval',
            $this->getFactory()->getUtilEncodingService()->decodeJson(
                $request->get(PriceProductTableViewTransfer::PRICE_PRODUCT_DEFAULT_IDS),
                true,
            ) ?: [],
        );
    }

    /**
     * @return int
     */
    protected function getIdMerchantFromCurrentUser(): int
    {
        return $this->getFactory()
            ->getMerchantUserFacade()
            ->getCurrentMerchantUser()
            ->getIdMerchantOrFail();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function createSuccessResponse(): JsonResponse
    {
        $zedUiFormResponseTransfer = $this->getFactory()
            ->getZedUiFactory()
            ->createZedUiFormResponseBuilder()
            ->addSuccessNotification(static::RESPONSE_NOTIFICATION_MESSAGE_SUCCESS)
            ->addActionRefreshTable()
            ->createResponse();

        return new JsonResponse($zedUiFormResponseTransfer->toArray());
    }

    /**
     * @param string|null $messageError
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function createErrorResponse(?string $messageError = null): JsonResponse
    {
        if ($messageError === null) {
            $messageError = static::RESPONSE_NOTIFICATION_MESSAGE_ERROR;
        }

        $zedUiFormResponseTransfer = $this->getFactory()
            ->getZedUiFactory()
            ->createZedUiFormResponseBuilder()
            ->addErrorNotification($messageError)
            ->createResponse();

        return new JsonResponse($zedUiFormResponseTransfer->toArray());
    }
}
