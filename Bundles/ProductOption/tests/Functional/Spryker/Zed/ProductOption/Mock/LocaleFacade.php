<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

/**
 * TODO: Check if we need to mock exactly this class or just an interface
 */
namespace Functional\Spryker\Zed\ProductOption\Mock;

use Spryker\Zed\Locale\Business\LocaleFacade as SprykerLocaleFacade;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToLocaleInterface;

class LocaleFacade extends SprykerLocaleFacade implements ProductOptionToLocaleInterface
{
}
