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
     * @param string|null $mvc
     *
     * @return string
     */
    public function getRepeatData($mvc)
    {
        return $this->getFactory()->createRepeater()->getRepeatData($mvc);
    }

}
