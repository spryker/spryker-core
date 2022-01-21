<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\QuoteRequestsRestApi\Processor\Validator;

use DateTime;
use Generated\Shared\Transfer\QuoteRequestTransfer;

class QuoteRequestValidator implements QuoteRequestValidatorInterface
{
    /**
     * @var string
     */
    protected const KEY_DELIVERY_DATE = 'delivery_date';

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return bool
     */
    public function validateDeliveryDate(QuoteRequestTransfer $quoteRequestTransfer): bool
    {
        if (!$this->hasDataToValidate($quoteRequestTransfer)) {
            return true;
        }

        $deliveryDate = $quoteRequestTransfer->getLatestVersionOrFail()->getMetadata()[static::KEY_DELIVERY_DATE] ?? null;

        return $deliveryDate === null || $this->isDeliveryDateValid((string)$deliveryDate);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return bool
     */
    protected function hasDataToValidate(QuoteRequestTransfer $quoteRequestTransfer): bool
    {
        return $quoteRequestTransfer->getLatestVersion() && $quoteRequestTransfer->getLatestVersionOrFail()->getMetadata();
    }

    /**
     * @param string $deliveryDate
     *
     * @return bool
     */
    protected function isDeliveryDateValid(string $deliveryDate): bool
    {
        return strtotime($deliveryDate) !== false && (new DateTime())->setTime(0, 0) <= new DateTime($deliveryDate);
    }
}
