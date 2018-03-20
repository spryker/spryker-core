<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductQuantityCartConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductQuantityCartConnector\Business\Validator\ProductQuantityRestrictionValidator;

/**
 * @method \Spryker\Zed\ProductQuantityCartConnector\ProductQuantityCartConnectorConfig getConfig()
 */
class ProductQuantityCartConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductQuantityCartConnector\Business\Validator\ProductQuantityRestrictionValidatorInterface
     */
    public function createProductQuantityRestrictionValidator()
    {
        return new ProductQuantityRestrictionValidator();
    }
}
