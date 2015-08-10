<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\ProductOption\Mock;

use SprykerFeature\Zed\Product\Business\ProductFacade as SprykerProductFacade;
use SprykerFeature\Zed\ProductOption\Dependency\Facade\ProductOptionToProductInterface;

class ProductFacade extends SprykerProductFacade implements ProductOptionToProductInterface
{
}
