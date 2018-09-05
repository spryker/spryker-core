<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Api\Communication\Plugin;

use Codeception\Test\Unit;
use Spryker\Zed\Api\ApiConfig;
use Spryker\Zed\Api\Communication\Plugin\CallbackServerVariableFilterStrategy;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Api
 * @group Communication
 * @group Plugin
 * @group CallbackServerVariableFilterStrategyTest
 * Add your own group annotations below this line
 */
class CallbackServerVariableFilterStrategyTest extends Unit
{
    /**
     * @return void
     */
    public function testFilter()
    {
        //Arrange
        $strategy = new CallbackServerVariableFilterStrategy();
        $configMock = $this->getMockBuilder(ApiConfig::class)->setMethods(['getServerVariablesCallback'])->getMock();
        $callback = function ($serverVariables) {
            unset($serverVariables['foo']);
            $serverVariables['bar'] = strtoupper($serverVariables['bar']);
            return $serverVariables;
        };
        $configMock->method('getServerVariablesCallback')->willReturn($callback);
        $testData = [
            'foo' => 'fee',
            'bar' => 'baz',
            'zip' => 'zap',
        ];

        //Act
        $result = $strategy->filter($testData, $configMock);

        //Assert
        $this->assertArrayNotHasKey('foo', $result);
        $this->assertArrayHasKey('bar', $result);
        $this->assertEquals('BAZ', $result['bar']);
    }
}
