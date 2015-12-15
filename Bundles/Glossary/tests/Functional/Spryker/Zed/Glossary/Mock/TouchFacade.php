<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\Spryker\Zed\Glossary\Mock;

use Spryker\Zed\Touch\Business\TouchFacade as SprykerTouchFacade;
use Spryker\Zed\Glossary\Dependency\Facade\GlossaryToTouchInterface;

class TouchFacade extends SprykerTouchFacade implements GlossaryToTouchInterface
{
}
