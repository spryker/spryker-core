<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferValidity\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductOfferValidityCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferValidityTransfer;
use Orm\Zed\ProductOfferValidity\Persistence\SpyProductOfferValidity;
use Propel\Runtime\Collection\ObjectCollection;

interface ProductOfferValidityMapperInterface
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $productOfferValidityEntities
     * @param \Generated\Shared\Transfer\ProductOfferValidityCollectionTransfer $productOfferValidityCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferValidityCollectionTransfer
     */
    public function productOfferValidityEntitiesToProductOfferValidityCollectionTransfer(
        ObjectCollection $productOfferValidityEntities,
        ProductOfferValidityCollectionTransfer $productOfferValidityCollectionTransfer
    ): ProductOfferValidityCollectionTransfer;

    /**
     * @param \Orm\Zed\ProductOfferValidity\Persistence\SpyProductOfferValidity $productOfferValidity
     * @param \Generated\Shared\Transfer\ProductOfferValidityTransfer $productOfferValidityTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferValidityTransfer
     */
    public function productOfferValidityEntityToProductOfferValidityTransfer(
        SpyProductOfferValidity $productOfferValidity,
        ProductOfferValidityTransfer $productOfferValidityTransfer
    ): ProductOfferValidityTransfer;
}
