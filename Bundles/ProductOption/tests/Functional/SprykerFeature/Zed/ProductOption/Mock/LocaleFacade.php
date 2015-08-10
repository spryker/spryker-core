<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\ProductOption\Mock;

use SprykerEngine\Zed\Locale\Business\LocaleFacade as SprykerLocaleFacade;
use SprykerFeature\Zed\ProductOption\Dependency\Facade\ProductOptionToLocaleInterface;

class LocaleFacade extends SprykerLocaleFacade implements ProductOptionToLocaleInterface
{
}
