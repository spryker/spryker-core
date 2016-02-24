<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\ProductOption\Mock;

use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToProductInterface;
use Spryker\Zed\Product\Business\ProductFacade as SprykerProductFacade;

class ProductFacade extends SprykerProductFacade implements ProductOptionToProductInterface
{
}
