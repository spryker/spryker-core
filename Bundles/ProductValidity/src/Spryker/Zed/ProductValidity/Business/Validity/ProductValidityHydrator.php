<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductValidity\Business\Validity;

use DateTime;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\ProductValidity\Persistence\ProductValidityQueryContainerInterface;

class ProductValidityHydrator implements ProductValidityHydratorInterface
{
    public const VALIDITY_DATE_TIME_FORMAT = 'Y-m-d G:i';

    /**
     * @var \Spryker\Zed\ProductValidity\Persistence\ProductValidityQueryContainerInterface
     */
    protected $productValidityQueryContainer;

    /**
     * @param \Spryker\Zed\ProductValidity\Persistence\ProductValidityQueryContainerInterface $productValidityQueryContainer
     */
    public function __construct(ProductValidityQueryContainerInterface $productValidityQueryContainer)
    {
        $this->productValidityQueryContainer = $productValidityQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
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

        $productConcreteTransfer->setValidFrom(
            $this->formatDateTime($productValidityEntity->getValidFrom())
        );
        $productConcreteTransfer->setValidTo(
            $this->formatDateTime($productValidityEntity->getValidTo())
        );

        return $productConcreteTransfer;
    }

    /**
     * @param \DateTime|null $dateTime
     *
     * @return string|null
     */
    protected function formatDateTime(?DateTime $dateTime = null): ?string
    {
        return $dateTime ? $dateTime->format(static::VALIDITY_DATE_TIME_FORMAT) : null;
    }
}
