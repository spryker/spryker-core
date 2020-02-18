<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStock\Business;

use Generated\Shared\Transfer\MerchantResponseTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\MerchantStock\Business\MerchantStockBusinessFactory getFactory()
 * @method \Spryker\Zed\MerchantStock\Persistence\MerchantStockEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MerchantStock\Persistence\MerchantStockRepositoryInterface getRepository()
 */
class MerchantStockFacade extends AbstractFacade implements MerchantStockFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantResponseTransfer
     */
    public function createMerchantStockByMerchant(MerchantTransfer $merchantTransfer): MerchantResponseTransfer
    {
        return $this->getFactory()->createMerchantStockWriter()->createMerchantStockByMerchant($merchantTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantResponseTransfer
     */
    public function updateMerchantStocksByMerchant(MerchantTransfer $merchantTransfer): MerchantResponseTransfer
    {
        return $this->getFactory()->createMerchantStockWriter()->updateMerchantStocksByMerchant($merchantTransfer);
    }
}
