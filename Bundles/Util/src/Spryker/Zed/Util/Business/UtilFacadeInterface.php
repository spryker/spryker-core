<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Util\Business;

/**
 * @method \Spryker\Zed\Util\Business\UtilBusinessFactory getFactory()
 */
interface UtilFacadeInterface
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
