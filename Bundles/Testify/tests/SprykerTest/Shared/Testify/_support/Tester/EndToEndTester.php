<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Tester;

use Codeception\Actor;
use SprykerTest\Shared\Testify\Fixtures\FixturesExporterInterface;
use SprykerTest\Shared\Testify\Fixtures\FixturesTrait;
use SprykerTest\Shared\Testify\StepOverride\StepOverrideTrait;

class EndToEndTester extends Actor implements FixturesExporterInterface
{
    use StepOverrideTrait;
    use FixturesTrait;
}
