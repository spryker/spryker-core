<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Store\Business\StoreBusinessFactory getFactory()
 */
class StoreFacade extends AbstractFacade implements StoreFacadeInterface
{

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getCurrentStore()
    {
        return $this->getFactory()->createStoreReader()->getCurrentStore();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\StoreTransfer[]
     */
    public function getAllStores()
    {
        return $this->getFactory()->createStoreReader()->getAllStores();
    }

}
