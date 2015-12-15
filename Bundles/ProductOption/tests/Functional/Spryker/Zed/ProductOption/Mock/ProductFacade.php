<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

/**
 * TODO: Check if we need to mock exactly this class or just an interface
 */
namespace Functional\Spryker\Zed\ProductOption\Mock;

use Spryker\Zed\Product\Business\ProductFacade as SprykerProductFacade;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToProductInterface;

class ProductFacade extends SprykerProductFacade implements ProductOptionToProductInterface
{
}
