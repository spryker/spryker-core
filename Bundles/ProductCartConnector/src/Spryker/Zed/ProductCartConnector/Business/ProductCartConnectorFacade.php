<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCartConnector\Business;

use Generated\Shared\Transfer\ChangeTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductCartConnector\Business\ProductCartConnectorBusinessFactory getFactory()
 */
class ProductCartConnectorFacade extends AbstractFacade implements ProductCartConnectorFacadeInterface
{

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ChangeTransfer $change
     *
     * @return \Generated\Shared\Transfer\ChangeTransfer
     */
    public function expandItems(ChangeTransfer $change)
    {
        return $this->getFactory()->createProductManager()->expandItems($change);
    }

}
