<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UtilText\Business;

/**
 * @method \Spryker\Zed\UtilText\Business\UtilTextBusinessFactory getFactory()
 */
interface UtilTextFacadeInterface
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
    public function generateSlug($value);

}
