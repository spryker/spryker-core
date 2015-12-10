<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\Payolution\Business;

use SprykerFeature\Zed\Payolution\Business\Api\Adapter\AdapterInterface;
use SprykerFeature\Zed\Payolution\Business\PayolutionDependencyContainer;
use SprykerFeature\Zed\Payolution\Business\PayolutionFacade;
use SprykerFeature\Zed\Payolution\PayolutionConfig;
use SprykerFeature\Zed\Payolution\Persistence\PayolutionQueryContainer;

class PayolutionFacadeMockBuilder
{

    /**
     * @param AdapterInterface $adapter
     *
     * @return PayolutionFacade
     */
    public static function build(AdapterInterface $adapter, \PHPUnit_Framework_TestCase $testCase)
    {

        // Mock dependency container to override return value of createExecutionAdapter to
        // place a mocked adapter that doesn't establish an actual connection.
        $dependencyContainerMock = self::getDependencyContainerMock($testCase);
        $dependencyContainerMock->setConfig(new PayolutionConfig());
        $dependencyContainerMock
            ->expects($testCase->any())
            ->method('createAdapter')
            ->will($testCase->returnValue($adapter));

        // Dependency container always requires a valid query container. Since we're creating
        // functional/integration tests there's no need to mock the database layer.
        $queryContainer = new PayolutionQueryContainer();
        $dependencyContainerMock->setQueryContainer($queryContainer);

        // Mock the facade to override getDependencyContainer() and have it return out
        // previously created mock.
        $facade = $testCase->getMock(
            'SprykerFeature\Zed\Payolution\Business\PayolutionFacade',
            ['getDependencyContainer']
        );
        $facade->expects($testCase->any())
            ->method('getDependencyContainer')
            ->will($testCase->returnValue($dependencyContainerMock));

        return $facade;
    }

    /**
     * @param \PHPUnit_Framework_TestCase $testCase
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|PayolutionDependencyContainer
     */
    protected static function getDependencyContainerMock(\PHPUnit_Framework_TestCase $testCase)
    {
        $dependencyContainerMock = $testCase->getMock(
            'SprykerFeature\Zed\Payolution\Business\PayolutionDependencyContainer',
            ['createAdapter']
        );

        return $dependencyContainerMock;
    }

}
