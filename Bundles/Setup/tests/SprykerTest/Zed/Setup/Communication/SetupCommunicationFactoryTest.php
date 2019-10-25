<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Setup\Communication;

use Codeception\Test\Unit;
use Spryker\Zed\Setup\Communication\SetupCommunicationFactory;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Setup
 * @group Communication
 * @group SetupCommunicationFactoryTest
 * Add your own group annotations below this line
 */
class SetupCommunicationFactoryTest extends Unit
{
    /**
     * @return void
     */
    public function testGetSetupInstallCommandNamesMustReturnArray()
    {
        $communicationFactory = new SetupCommunicationFactory();

        $this->assertIsArray($communicationFactory->getSetupInstallCommandNames());
    }
}
