<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\Payolution\Business;

use SprykerEngine\Shared\Config;
use SprykerEngine\Zed\Kernel\Business\Factory;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerEngine\Zed\Kernel\Persistence\Factory as PersistenceFactory;
use SprykerFeature\Zed\Payolution\Business\Api\Adapter\AdapterInterface;
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
    static public function build(AdapterInterface $adapter, \PHPUnit_Framework_TestCase $testCase)
    {

        // Mock dependency container to override return value of createExecutionAdapter to
        // place a mocked adapter that doesn't establish an actual connection.
        $factory = new Factory('Payolution');
        $locator = Locator::getInstance();
        $config = Config::getInstance();
        $payolutionConfig = new PayolutionConfig($config, $locator);
        $dependencyContainerMock = $testCase->getMock(
            'SprykerFeature\Zed\Payolution\Business\PayolutionDependencyContainer',
            ['createExecutionAdapter'],
            [
                $factory,
                $locator,
                $payolutionConfig,
            ]
        );
        $dependencyContainerMock
            ->expects($testCase->any())
            ->method('createExecutionAdapter')
            ->will($testCase->returnValue($adapter));

        // Dependency container always requires a valid query container. Since we're creating
        // functional/integration tests there's no need to mock the database layer.
        $persistenceFactory = new PersistenceFactory('Payolution');
        $queryContainer = new PayolutionQueryContainer($persistenceFactory, $locator);
        $dependencyContainerMock->setQueryContainer($queryContainer);

        // Mock the facade to override getDependencyContainer() and have it return out
        // previously created mock.
        $facade = $testCase->getMock(
            'SprykerFeature\Zed\Payolution\Business\PayolutionFacade',
            ['getDependencyContainer'],
            [$factory, $locator]
        );
        $facade->expects($testCase->any())
            ->method('getDependencyContainer')
            ->will($testCase->returnValue($dependencyContainerMock));

        return $facade;
    }

}
