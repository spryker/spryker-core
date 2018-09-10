<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\ProductGroup\Helper;

use Codeception\Module;
use Spryker\Zed\ProductGroup\Business\ProductGroupFacadeInterface;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class ProductGroupDataHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    /**
     * @return \Spryker\Zed\ProductGroup\Business\ProductGroupFacadeInterface
     */
    protected function getCurrencyFacade(): ProductGroupFacadeInterface
    {
        return $this->getLocator()->productGroup()->facade();
    }
}
