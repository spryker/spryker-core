<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearchConfigStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductSearchConfigStorage\Business\ProductSearchConfigStorageBusinessFactory getFactory()
 */
class ProductSearchConfigStorageFacade extends AbstractFacade implements ProductSearchConfigStorageFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function publish()
    {
        $this->getFactory()->createProductSearchConfigStorageWriter()->publish();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function unpublish()
    {
        $this->getFactory()->createProductSearchConfigStorageWriter()->unpublish();
    }
}
