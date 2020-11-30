<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Dependency\Facade;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;

interface PriceProductOfferToPriceProductFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function persistProductConcretePriceCollection(ProductConcreteTransfer $productConcreteTransfer);

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    public function getValidCurrencyAssignedToStoreConstraint(): SymfonyConstraint;

    /**
     * @return void
     */
    public function deleteOrphanPriceProductStoreEntities(): void;
}
