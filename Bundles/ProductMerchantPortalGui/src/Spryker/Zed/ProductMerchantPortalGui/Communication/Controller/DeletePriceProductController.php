<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Controller;

use ArrayObject;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class DeletePriceProductController extends AbstractController
{
    protected const RESPONSE_MESSAGE_SUCCESS = 'Success! The Price is deleted.';
    protected const RESPONSE_MESSAGE_ERROR = 'Something went wrong, please try again.';

    protected const RESPONSE_KEY_POST_ACTIONS = 'postActions';
    protected const RESPONSE_KEY_NOTIFICATIONS = 'notifications';
    protected const RESPONSE_KEY_TYPE = 'type';
    protected const RESPONSE_KEY_MESSAGE = 'message';

    protected const RESPONSE_TYPE_REFRESH_TABLE = 'refresh_table';
    protected const RESPONSE_TYPE_SUCCESS = 'success';
    protected const RESPONSE_TYPE_ERROR = 'error';

    /**
     * @phpstan-param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param int[] $priceProductDefaultIds
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function filterPriceProductTransfersByPriceProductDefaultIds(
        ArrayObject $priceProductTransfers,
        array $priceProductDefaultIds
    ): array {
        $priceProductTransfersToRemove = [];

        foreach ($priceProductTransfers as $priceProductTransfer) {
            if (in_array($priceProductTransfer->getPriceDimensionOrFail()->getIdPriceProductDefault(), $priceProductDefaultIds)) {
                $priceProductTransfersToRemove[] = $priceProductTransfer;
            }
        }

        return $priceProductTransfersToRemove;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function createSuccessResponse(): JsonResponse
    {
        $responseData = [
            static::RESPONSE_KEY_POST_ACTIONS => [
                [
                    static::RESPONSE_KEY_TYPE => static::RESPONSE_TYPE_REFRESH_TABLE,
                ],
            ],
            static::RESPONSE_KEY_NOTIFICATIONS => [
                [
                    static::RESPONSE_KEY_TYPE => static::RESPONSE_TYPE_SUCCESS,
                    static::RESPONSE_KEY_MESSAGE => static::RESPONSE_MESSAGE_SUCCESS,
                ],
            ],
        ];

        return new JsonResponse($responseData);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function createErrorResponse(): JsonResponse
    {
        $responseData[static::RESPONSE_KEY_NOTIFICATIONS][] = [
            static::RESPONSE_KEY_TYPE => static::RESPONSE_TYPE_ERROR,
            static::RESPONSE_KEY_MESSAGE => static::RESPONSE_MESSAGE_ERROR,
        ];

        return new JsonResponse($responseData);
    }
}
