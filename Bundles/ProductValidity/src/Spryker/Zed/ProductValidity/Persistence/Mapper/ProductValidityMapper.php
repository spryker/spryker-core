<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductValidity\Persistence\Mapper;

use DateTime;
use Generated\Shared\Transfer\ProductValidityTransfer;
use Orm\Zed\ProductValidity\Persistence\SpyProductValidity;
use Spryker\Zed\ProductValidity\ProductValidityConfig;

class ProductValidityMapper
{
    /**
     * @param \Orm\Zed\ProductValidity\Persistence\SpyProductValidity $productValidityEntity
     * @param \Generated\Shared\Transfer\ProductValidityTransfer $productValidityTransfer
     *
     * @return \Generated\Shared\Transfer\ProductValidityTransfer
     */
    public function mapProductValidityEntityToProductValidityTransfer(
        SpyProductValidity $productValidityEntity,
        ProductValidityTransfer $productValidityTransfer
    ): ProductValidityTransfer {
        $validFrom = $productValidityEntity->getValidFrom();
        $validTo = $productValidityEntity->getValidTo();

        return $productValidityTransfer->setIdProductConcrete($productValidityEntity->getFkProduct())
            ->setValidFrom(is_string($validFrom) ? $validFrom : $this->formatDateTime($validFrom))
            ->setValidTo(is_string($validTo) ? $validTo : $this->formatDateTime($validTo));
    }

    /**
     * @param \DateTime|null $dateTime
     *
     * @return string|null
     */
    protected function formatDateTime(?DateTime $dateTime): ?string
    {
        return $dateTime ? $dateTime->format(ProductValidityConfig::VALIDITY_DATE_TIME_FORMAT) : null;
    }
}
