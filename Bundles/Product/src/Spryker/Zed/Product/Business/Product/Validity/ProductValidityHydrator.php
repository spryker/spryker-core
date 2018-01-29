<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Validity;

use DateTime;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Spryker\Shared\Product\ProductConstants;

class ProductValidityHydrator implements ProductValidityHydratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productTransfer
     * @param \Orm\Zed\Product\Persistence\SpyProduct $productEntity
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function hydrateProduct(
        ProductConcreteTransfer $productTransfer,
        SpyProduct $productEntity
    ): ProductConcreteTransfer {

        $validityEntity = $productEntity->getSpyProductValidities()->getFirst();

        if ($validityEntity) {
            /** @var \Orm\Zed\Product\Persistence\SpyProductValidity $validityEntity */
            $productTransfer->setValidFrom(
                $this->formatDateTime($validityEntity->getValidFrom())
            );
            $productTransfer->setValidTo(
                $this->formatDateTime($validityEntity->getValidTo())
            );
        }

        return $productTransfer;
    }

    /**
     * @param \DateTime|null $dateTime
     *
     * @return null|string
     */
    protected function formatDateTime(DateTime $dateTime = null)
    {
        return $dateTime ? $dateTime->format(ProductConstants::VALIDITY_DATE_TIME_FORMAT) : null;
    }
}
