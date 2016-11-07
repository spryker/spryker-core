<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UtilText\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\UtilText\Business\UtilTextBusinessFactory getFactory()
 */
class UtilTextFacade extends AbstractFacade implements UtilTextFacadeInterface
{

    /**
     * Specification:
     * - Generate slug based on value
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

    /**
     *
     * Specification:
     * - Generates random string for given lenght value
     *
     * @api
     *
     * @param int $length
     *
     * @return string
     */
    public function generateRandomString($length)
    {
        return $this->getFactory()
            ->createStringGenerator()
            ->generateRandomString($length);
    }

}
