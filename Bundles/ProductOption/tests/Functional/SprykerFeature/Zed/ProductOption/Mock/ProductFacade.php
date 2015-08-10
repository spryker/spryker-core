<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

/**
 * TODO: Check if we need to mock exactly this class or just an interface
 */
namespace Functional\SprykerFeature\Zed\ProductOption\Mock;

use SprykerFeature\Zed\Product\Business\ProductFacade as SprykerProductFacade;
use SprykerFeature\Zed\ProductOption\Dependency\Facade\ProductOptionToProductInterface;

class ProductFacade extends SprykerProductFacade implements ProductOptionToProductInterface
{
}
