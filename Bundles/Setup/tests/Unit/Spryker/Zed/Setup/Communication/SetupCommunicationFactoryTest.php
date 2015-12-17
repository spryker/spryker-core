<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Setup\Communication;

use Spryker\Zed\Kernel\AbstractUnitTest;
use Spryker\Zed\Setup\Communication\SetupCommunicationFactory;

/**
 * @method SetupCommunicationFactory getFactory()
 */
class SetupCommunicationFactoryTest extends AbstractUnitTest
{

    /**
     * @return void
     */
    public function testCreateSetupInstallCommandNamesMustReturnArray()
    {
        $communicationFactory = $this->getFactory();

        $this->assertInternalType('array', $communicationFactory->createSetupInstallCommandNames());
    }

}
