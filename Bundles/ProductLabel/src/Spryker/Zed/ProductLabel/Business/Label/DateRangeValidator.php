<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business\Label;

use Generated\Shared\Transfer\ProductLabelTransfer;
use Spryker\Zed\ProductLabel\Dependency\Service\ProductLabelToUtilDateTimeInterface;

class DateRangeValidator implements DateRangeValidatorInterface
{

    /**
     * @var \Spryker\Zed\ProductLabel\Dependency\Service\ProductLabelToUtilDateTimeInterface
     */
    protected $dateTimeService;

    /**
     * @param \Spryker\Zed\ProductLabel\Dependency\Service\ProductLabelToUtilDateTimeInterface $dateTimeService
     */
    public function __construct(ProductLabelToUtilDateTimeInterface $dateTimeService)
    {
        $this->dateTimeService = $dateTimeService;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return bool
     */
    public function isBecomingValid(ProductLabelTransfer $productLabelTransfer)
    {
        if (!$this->hasValidityDateRange($productLabelTransfer)) {
            return false;
        }

        if ($productLabelTransfer->getIsPublished()) {
            return false;
        }

        $now = $this->getNow();
        $validFrom = $this->dateTimeService->fromString(
            $productLabelTransfer->getValidFrom()->format('Y-m-d h:m:s')
        );
        $validTo = $this->dateTimeService->fromString(
            $productLabelTransfer->getValidTo()->format('Y-m-d h:m:s')
        );

        $isPastValidFrom = ($validFrom->getTimestamp() <= $now->getTimestamp());
        $isAheadValidTo = ($now->getTimestamp() < $validTo->getTimestamp());

        return ($isPastValidFrom && $isAheadValidTo);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return bool
     */
    public function isBecomingInvalid(ProductLabelTransfer $productLabelTransfer)
    {
        if (!$this->hasValidityDateRange($productLabelTransfer)) {
            return false;
        }

        if (!$productLabelTransfer->getIsPublished()) {
            return false;
        }

        $now = $this->getNow();
        $validTo = $this->dateTimeService->fromString(
            $productLabelTransfer->getValidTo()->format('Y-m-d h:m:s')
        );

        return ($validTo->getTimestamp() <= $now->getTimestamp());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return bool
     */
    protected function hasValidityDateRange(ProductLabelTransfer $productLabelTransfer)
    {
        return ($productLabelTransfer->getValidFrom() && $productLabelTransfer->getValidTo());
    }

    /**
     * @return \DateTime
     */
    protected function getNow()
    {
        $dateTime = $this->dateTimeService->fromString('now');

        return $dateTime;
    }

}
