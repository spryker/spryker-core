<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\Glossary\Mock;

use SprykerEngine\Zed\Locale\Business\LocaleFacade as SprykerLocaleFacade;
use SprykerFeature\Zed\Glossary\Dependency\Facade\GlossaryToLocaleInterface;

class LocaleFacade extends SprykerLocaleFacade implements GlossaryToLocaleInterface
{
}
