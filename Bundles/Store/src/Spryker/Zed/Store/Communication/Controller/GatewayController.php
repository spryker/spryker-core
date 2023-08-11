<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Communication\Controller;

use Generated\Shared\Transfer\StoreCollectionTransfer;
use Generated\Shared\Transfer\StoreCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\Store\Business\StoreFacadeInterface getFacade()
 * @method \Spryker\Zed\Store\Persistence\StoreRepositoryInterface getRepository()
 * @method \Spryker\Zed\Store\Communication\StoreCommunicationFactory getFactory()
 * @method \Spryker\Zed\Store\Persistence\StoreQueryContainerInterface getQueryContainer()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\StoreCriteriaTransfer $storeCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\StoreCollectionTransfer
     */
    public function getStoreCollectionAction(StoreCriteriaTransfer $storeCriteriaTransfer): StoreCollectionTransfer
    {
        return $this->getFacade()->getStoreCollection($storeCriteriaTransfer);
    }
}
