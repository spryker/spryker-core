<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedRequest\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ZedRequest\Business\ZedRequestBusinessFactory getFactory()
 */
class ZedRequestFacade extends AbstractFacade implements ZedRequestFacadeInterface
{
    /**
     * @api
     *
     * @param string|null $bundleControllerAction
     *
     * @return array
     */
    public function getRepeatData($bundleControllerAction)
    {
        return $this->getFactory()->createRepeater()->getRepeatData($bundleControllerAction);
    }
}
