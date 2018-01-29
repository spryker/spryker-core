<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductValidity\Business\Validity;

use DateTime;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Spryker\Shared\ProductValidity\ProductValidityConstants;
use Spryker\Zed\ProductValidity\Persistence\ProductValidityQueryContainerInterface;

class ProductValidityHydrator implements ProductValidityHydratorInterface
{
    protected $productValidityQueryContainer;

    /**
     * @param ProductValidityQueryContainerInterface $productValidityQueryContainer
     */
    public function __construct(ProductValidityQueryContainerInterface $productValidityQueryContainer)
    {
        $this->productValidityQueryContainer = $productValidityQueryContainer;
    }

    /**
     * @param ProductConcreteTransfer $productConcreteTransfer
     *
     * @return ProductConcreteTransfer
     */
    public function hydrate(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer
    {
        $productValidityEntity = $this->productValidityQueryContainer
            ->queryProductValidityByIdProductConcrete(
                $productConcreteTransfer->getIdProductConcrete()
            )
            ->findOne();

        if (!$productValidityEntity) {
            return $productConcreteTransfer;
        }

        /** @var \Orm\Zed\Product\Persistence\SpyProductValidity $validityEntity */
        $productConcreteTransfer->setValidFrom(
            $this->formatDateTime($validityEntity->getValidFrom())
        );
        $productConcreteTransfer->setValidTo(
            $this->formatDateTime($validityEntity->getValidTo())
        );

        return $productConcreteTransfer;
    }

    /**
     * @param \DateTime|null $dateTime
     *
     * @return null|string
     */
    protected function formatDateTime(DateTime $dateTime = null)
    {
        return $dateTime ? $dateTime->format(ProductValidityConstants::VALIDITY_DATE_TIME_FORMAT) : null;
    }
}
