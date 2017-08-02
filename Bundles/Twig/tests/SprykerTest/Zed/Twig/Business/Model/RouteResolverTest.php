<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Twig\Business\Model;

use PHPUnit_Framework_TestCase;
use Spryker\Zed\Twig\Business\Model\RouteResolver;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Twig
 * @group Business
 * @group Model
 * @group RouteResolverTest
 * Add your own group annotations below this line
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
