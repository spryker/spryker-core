<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSearch\Business;

use Generated\Shared\Transfer\MerchantCollectionTransfer;
use Generated\Shared\Transfer\MerchantCriteriaFilterTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\MerchantSearch\Business\MerchantSearchBusinessFactory getFactory()
 * @method \Spryker\Zed\MerchantSearch\Persistence\MerchantSearchRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantSearch\Persistence\MerchantSearchEntityManagerInterface getEntityManager()
 */
class MerchantSearchFacade extends AbstractFacade implements MerchantSearchFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantCriteriaFilterTransfer $merchantCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCollectionTransfer
     */
    public function getActiveMerchants(MerchantCriteriaFilterTransfer $merchantCriteriaFilterTransfer): MerchantCollectionTransfer
    {
        return $this->getFactory()
            ->createMerchantReader()
            ->getActiveMerchants($merchantCriteriaFilterTransfer);
    }
}
