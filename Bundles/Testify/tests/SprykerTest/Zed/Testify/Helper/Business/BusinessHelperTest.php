<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Testify\Helper\Business;

use Codeception\Stub;
use Codeception\Test\Unit;
use Spryker\Shared\Testify\TestifyConfig as SharedTestifyConfig;
use Spryker\Zed\Kernel\AbstractBundleConfig;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Testify\TestifyConfig;
use SprykerTest\Shared\Testify\Helper\ConfigHelper;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Testify
 * @group Helper
 * @group Business
 * @group BusinessHelperTest
 * Add your own group annotations below this line
 */
class BusinessHelperTest extends Unit
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

        /** @var \SprykerTest\Zed\Testify\Helper\Business\BusinessHelper $businessHelperStub */
        $businessHelperStub = $this->getBusinessHelper($configHelperStub);

        // Act
        $businessFactory = $businessHelperStub->getFactory();

        // Assert
        $this->assertInstanceOf(AbstractBundleConfig::class, $businessFactory->getConfig());
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

        /** @var \SprykerTest\Zed\Testify\Helper\Business\BusinessHelper $businessHelperStub */
        $businessHelperStub = $this->getBusinessHelper($configHelperStub);

        // Act
        $businessFactory = $businessHelperStub->getFactory();

        // Assert
        $this->assertInstanceOf(AbstractBusinessFactory::class, $businessFactory);
    }

    /**
     * @param \Codeception\RealInstanceType|object|\PHPUnit\Framework\MockObject\MockObject $configHelperStub
     *
     * @return \Codeception\RealInstanceType|object|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getBusinessHelper($configHelperStub)
    {
        return $this->tester->createHelperStub(BusinessHelper::class, [
            'createFactory' => function () {
                return new class extends AbstractBusinessFactory {
                };
            },
            'getConfigHelper' => function () use ($configHelperStub) {
                return $configHelperStub;
            },
        ]);
    }
}
