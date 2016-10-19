<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Util\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Util\Business\UtilBusinessFactory getFactory()
 */
class UtilFacade extends AbstractFacade implements UtilFacadeInterface
{

    /**
     * Specification:
     * - Create slug based on value
     *
     * @api
     *
     * @param string $value
     *
     * @return string
     */
    public function generateSlug($value)
    {
        return $this->getFactory()
            ->createTextSlug()
            ->generate($value);
    }

}
