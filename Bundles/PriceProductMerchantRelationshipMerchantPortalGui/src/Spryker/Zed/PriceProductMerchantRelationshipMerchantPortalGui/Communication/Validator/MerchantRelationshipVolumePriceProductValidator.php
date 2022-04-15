<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Communication\Validator;

use ArrayObject;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ValidationErrorTransfer;
use Generated\Shared\Transfer\ValidationResponseTransfer;
use Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Dependency\Service\PriceProductMerchantRelationshipMerchantPortalGuiToUtilEncodingServiceInterface;

class MerchantRelationshipVolumePriceProductValidator implements MerchantRelationshipVolumePriceProductValidatorInterface
{
    /**
     * @var string
     */
    protected const ERROR_MESSAGE_CUSTOMER_SPECIFIC_PRICE_QUANTITY = 'A customer specific price requires a quantity of 1.';

    /**
     * @var string
     */
    protected const VOLUME_PRICE_QUANTITY = 'quantity';

    /**
     * @var string
     */
    protected const VOLUME_PRICES_KEY = 'volume_prices';

    /**
     * @var \Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Dependency\Service\PriceProductMerchantRelationshipMerchantPortalGuiToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Dependency\Service\PriceProductMerchantRelationshipMerchantPortalGuiToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(PriceProductMerchantRelationshipMerchantPortalGuiToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\ValidationResponseTransfer
     */
    public function validate(ArrayObject $priceProductTransfers): ValidationResponseTransfer
    {
        $validationResponseTransfer = (new ValidationResponseTransfer())->setIsSuccess(true);

        /** @var \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer */
        foreach ($priceProductTransfers as $priceProductTransfer) {
            if ($this->isMerchantRelationshipVolumePrice($priceProductTransfer)) {
                $validationResponseTransfer
                    ->setIsSuccess(false)
                    ->addValidationError(
                        (new ValidationErrorTransfer())->setMessage(
                            static::ERROR_MESSAGE_CUSTOMER_SPECIFIC_PRICE_QUANTITY,
                        ),
                    );
            }
        }

        return $validationResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return bool
     */
    protected function isMerchantRelationshipVolumePrice(PriceProductTransfer $priceProductTransfer): bool
    {
        if (!$priceProductTransfer->getPriceDimensionOrFail()->getIdMerchantRelationship()) {
            return false;
        }

        $encodedPriceData = (string)$priceProductTransfer->getMoneyValueOrFail()->getPriceData();

        /** @var array<string, mixed> $priceData */
        $priceData = $this->utilEncodingService->decodeJson($encodedPriceData, true) ?? [];
        if (!isset($priceData[static::VOLUME_PRICES_KEY]) || !$priceData[static::VOLUME_PRICES_KEY]) {
            return false;
        }

        return $priceProductTransfer->getPriceDimensionOrFail()->getIdMerchantRelationship() !== null
            && $priceData[static::VOLUME_PRICES_KEY][0][static::VOLUME_PRICE_QUANTITY] > 1;
    }
}
