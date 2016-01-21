<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Setup\Communication;

use Spryker\Zed\Setup\Communication\SetupCommunicationFactory;

/**
 * @group Spryker
 * @group Zed
 * @group Setup
 * @group Communication
 * @group SetupCommunicationFactory
 */
class SetupCommunicationFactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testGetSetupInstallCommandNamesMustReturnArray()
    {
        $communicationFactory = new SetupCommunicationFactory();

        $this->assertInternalType('array', $communicationFactory->getSetupInstallCommandNames());
    }

}
