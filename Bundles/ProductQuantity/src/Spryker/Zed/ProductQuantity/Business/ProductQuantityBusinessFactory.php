<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductQuantity\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductQuantity\Business\Model\ProductQuantityReader;
use Spryker\Zed\ProductQuantity\Business\Model\Validator\ProductQuantityRestrictionValidator;

/**
 * @method \Spryker\Zed\ProductQuantity\ProductQuantityConfig getConfig()
 * @method \Spryker\Zed\ProductQuantity\Persistence\ProductQuantityRepositoryInterface getRepository()
 */
class ProductQuantityBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductQuantity\Business\Model\Validator\ProductQuantityRestrictionValidatorInterface
     */
    public function createProductQuantityRestrictionValidator()
    {
        return new ProductQuantityRestrictionValidator($this->createProductQuantityReader());
    }

    /**
     * @return \Spryker\Zed\ProductQuantity\Business\Model\ProductQuantityReaderInterface
     */
    public function createProductQuantityReader()
    {
        return new ProductQuantityReader($this->getRepository());
    }
}
