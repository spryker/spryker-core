<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Api\Communication\Plugin;

use Codeception\Test\Unit;
use Spryker\Zed\Api\ApiConfig;
use Spryker\Zed\Api\Communication\Plugin\WhitelistServerVariableFilterStrategy;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Api
 * @group Communication
 * @group Plugin
 * @group WhitelistServerVariableFilterStrategyTest
 * Add your own group annotations below this line
 */
class WhitelistServerVariableFilterStrategyTest extends Unit
{
    /**
     * @return void
     */
    public function testFilter()
    {
        //Arrange
        $strategy = new WhitelistServerVariableFilterStrategy();
        $configMock = $this->getMockBuilder(ApiConfig::class)->setMethods(['getServerVariablesWhitelist'])->getMock();
        $configMock->method('getServerVariablesWhitelist')->willReturn(['bar', 'quux']);
        $testData = [
            'foo' => 'fee',
            'bar' => 'baz',
            'zip' => 'zap',
        ];

        //Act
        $result = $strategy->filter($testData, $configMock);

        //Assert
        $this->assertArrayNotHasKey('foo', $result);
        $this->assertArrayNotHasKey('zip', $result);
    }
}
