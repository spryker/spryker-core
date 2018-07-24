<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Api\Communication\Plugin;

use Codeception\Test\Unit;
use Spryker\Zed\Api\ApiConfig;
use Spryker\Zed\Api\Communication\Plugin\BlacklistServerVariableFilterStrategy;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Api
 * @group Communication
 * @group Plugin
 * @group BlacklistServerVariableFilterStrategyTest
 * Add your own group annotations below this line
 */
class BlacklistServerVariableFilterStrategyTest extends Unit
{
    /**
     * @return void
     */
    public function testFilter()
    {
        //Arrange
        $strategy = new BlacklistServerVariableFilterStrategy();
        $configMock = $this->getMockBuilder(ApiConfig::class)->setMethods(['getServerVariablesBlacklist'])->getMock();
        $configMock->method('getServerVariablesBlacklist')->willReturn(['zip', 'quux']);
        $testData = [
            'foo' => 'fee',
            'bar' => 'baz',
            'zip' => 'zap',
        ];

        //Act
        $result = $strategy->filter($testData, $configMock);

        //Assert
        $this->assertArrayNotHasKey('zip', $result);
        $this->assertArrayNotHasKey('quux', $result);
    }
}
