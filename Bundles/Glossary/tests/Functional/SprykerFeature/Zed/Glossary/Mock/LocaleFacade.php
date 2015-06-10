<?php

/*
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace Functional\SprykerFeature\Zed\Glossary\Mock;

use SprykerEngine\Zed\Locale\Business\LocaleFacade as SprykerLocaleFacade;
use SprykerFeature\Zed\Glossary\Dependency\Facade\GlossaryToLocaleInterface;

class LocaleFacade extends SprykerLocaleFacade implements GlossaryToLocaleInterface
{
}
