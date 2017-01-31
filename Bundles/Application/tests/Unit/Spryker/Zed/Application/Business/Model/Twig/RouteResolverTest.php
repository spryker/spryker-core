<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Application\Business\Model\Twig;

use PHPUnit_Framework_TestCase;
use Spryker\Zed\Application\Business\Model\Twig\RouteResolver;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Application
 * @group Business
 * @group Model
 * @group Twig
 * @group RouteResolverTest
 */
class RouteResolverTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testBuildRouteFromControllerServiceNameShouldReturnUri()
    {
        $routeResolver = new RouteResolver();
        $incomingString = 'controller.service.DummyBundle.Index.camelCase:camelCaseAction';

        $this->assertEquals(
            'DummyBundle/Index/camel-case',
            $routeResolver->buildRouteFromControllerServiceName($incomingString)
        );
    }

    /**
     * @return void
     */
    public function testBuildRouteFromControllerServiceNameReturnUriFail()
    {
        $routeResolver = new RouteResolver();
        $incomingString = 'controller.service.DummyBundle.Index.camelCase:camelCaseAction';

        $this->assertNotEquals(
            'DummyBundle/Index/camelCase',
            $routeResolver->buildRouteFromControllerServiceName($incomingString)
        );
    }

}
