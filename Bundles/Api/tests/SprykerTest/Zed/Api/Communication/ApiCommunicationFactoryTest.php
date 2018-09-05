<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Api\Communication;

use Codeception\Test\Unit;
use Spryker\Shared\Api\ApiConstants;
use Spryker\Zed\Api\ApiConfig;
use Spryker\Zed\Api\Communication\ApiCommunicationFactory;
use Spryker\Zed\Api\Communication\Plugin\BlacklistServerVariableFilterStrategy;
use Spryker\Zed\Api\Communication\Plugin\ServerVariableFilterer;
use Spryker\Zed\Api\Communication\Plugin\ServerVariableFilterStrategyInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Api
 * @group Communication
 * @group ApiCommunicationFactoryTest
 * Add your own group annotations below this line
 */
class ApiCommunicationFactoryTest extends Unit
{
    /**
     * @return void
     */
    public function testCreateServerVariableFilterer()
    {
        //Arrange
        $factory = new ApiCommunicationFactory();
        $factory->setConfig(new ApiConfig());

        //Assert
        $this->assertInstanceOf(ServerVariableFilterer::class, $factory->createServerVariableFilterer());
    }

    /**
     * @return void
     */
    public function testCreateServerVariableFilterStrategyShouldReturnDefaultStrategy()
    {
        //Arrange
        $factory = new ApiCommunicationFactory();

        $factory->setConfig(new ApiConfig());

        //Assert
        $this->assertInstanceOf(ServerVariableFilterStrategyInterface::class, $factory->createServerVariableFilterStrategy());
    }

    /**
     * @return void
     */
    public function testCreateServerVariableFilterStrategyShouldReturnStrategyByConfig()
    {
        //Arrange
        $factory = new ApiCommunicationFactory();

        $configMock = $this->getMockBuilder(ApiConfig::class)->setMethods(['getServerVariablesFilterStrategy'])->getMock();
        $configMock->method('getServerVariablesFilterStrategy')->willReturn(ApiConstants::ENV_SERVER_VARIABLE_FILTER_STRATEGY_BLACKLIST);
        $factory->setConfig($configMock);

        //Assert
        $this->assertInstanceOf(BlacklistServerVariableFilterStrategy::class, $factory->createServerVariableFilterStrategy());
    }

    /**
     * @return void
     */
    public function testCreateServerVariableFilterStrategyShouldThrowExceptionWhenUnExistentStrategySetInConfig()
    {
        //Arrange
        $factory = new ApiCommunicationFactory();

        $configMock = $this->getMockBuilder(ApiConfig::class)->setMethods(['getServerVariablesFilterStrategy'])->getMock();
        $configMock->method('getServerVariablesFilterStrategy')->willReturn('non-existent-strategy');
        $factory->setConfig($configMock);

        //Assert
        $this->expectException(InvalidArgumentException::class);

        //Act
        $factory->createServerVariableFilterStrategy();
    }
}
