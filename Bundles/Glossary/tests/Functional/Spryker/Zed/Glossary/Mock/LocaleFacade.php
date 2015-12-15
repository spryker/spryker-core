<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\Spryker\Zed\Glossary\Mock;

use Spryker\Zed\Locale\Business\LocaleFacade as SprykerLocaleFacade;
use Spryker\Zed\Glossary\Dependency\Facade\GlossaryToLocaleInterface;

class LocaleFacade extends SprykerLocaleFacade implements GlossaryToLocaleInterface
{
}
