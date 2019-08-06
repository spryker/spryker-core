<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Fixtures;

interface FixturesExporterInterface
{
    /**
     * @param \SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface $fixture
     *
     * @return void
     */
    public function exportFixtures(FixturesContainerInterface $fixture): void;
}
