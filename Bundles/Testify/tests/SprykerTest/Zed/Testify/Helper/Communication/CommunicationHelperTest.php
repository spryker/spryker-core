<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Testify\Helper\Communication;

use Codeception\Stub;
use Codeception\Test\Unit;
use Spryker\Shared\Testify\TestifyConfig as SharedTestifyConfig;
use Spryker\Zed\Kernel\AbstractBundleConfig;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Testify\TestifyConfig;
use SprykerTest\Shared\Testify\Helper\ConfigHelper;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Testify
 * @group Helper
 * @group Communication
 * @group CommunicationHelperTest
 * Add your own group annotations below this line
 */
class CommunicationHelperTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Testify\TestifyHelperTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetFactoryWillSetConfigIfConfigExists(): void
    {
        // Arrange
        $configHelperStub = $this->tester->createHelperStub(ConfigHelper::class, [
            'resolveClassName' => Stub::consecutive(TestifyConfig::class, SharedTestifyConfig::class),
        ]);

        /** @var \SprykerTest\Zed\Testify\Helper\Communication\CommunicationHelper $communicationHelperStub */
        $communicationHelperStub = $this->getCommunicationHelper($configHelperStub);

        // Act
        $communicationFactory = $communicationHelperStub->getFactory();

        // Assert
        $this->assertInstanceOf(AbstractBundleConfig::class, $communicationFactory->getConfig());
    }

    /**
     * @return void
     */
    public function testGetFactoryWillNotSetConfigIfConfigDoesNotExists(): void
    {
        // Arrange
        $configHelperStub = $this->tester->createHelperStub(ConfigHelper::class, [
            'resolveClassName' => Stub::consecutive(null, SharedTestifyConfig::class),
        ]);

        /** @var \SprykerTest\Zed\Testify\Helper\Communication\CommunicationHelper $communicationHelperStub */
        $communicationHelperStub = $this->getCommunicationHelper($configHelperStub);

        // Act
        $communicationFactory = $communicationHelperStub->getFactory();

        // Assert
        $this->assertInstanceOf(AbstractCommunicationFactory::class, $communicationFactory);
    }

    /**
     * @param \Codeception\RealInstanceType|object|\PHPUnit\Framework\MockObject\MockObject $configHelperStub
     *
     * @return \Codeception\RealInstanceType|object|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getCommunicationHelper($configHelperStub)
    {
        return $this->tester->createHelperStub(CommunicationHelper::class, [
            'createFactory' => function () {
                return new class extends AbstractCommunicationFactory {
                };
            },
            'getConfigHelper' => function () use ($configHelperStub) {
                return $configHelperStub;
            },
        ]);
    }
}
