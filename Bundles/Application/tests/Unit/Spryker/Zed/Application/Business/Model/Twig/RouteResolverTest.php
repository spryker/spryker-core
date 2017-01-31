<?php

namespace Unit\Spryker\Zed\Application\Business\Model\Twig;

use PHPUnit_Framework_TestCase;
use Spryker\Zed\Application\Business\Model\Twig\RouteResolver;

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

class RouteResolverTest extends PHPUnit_Framework_TestCase
{
    public function testBuildRouteFromControllerServiceNameShouldReturnUri()
    {
        $routeResolver = new RouteResolver();
        $incomingString = 'controller.service.DummyBundle.Index.camelCase:camelCaseAction';

        $this->assertEquals(
            'DummyBundle/Index/camel-case',
            $routeResolver->buildRouteFromControllerServiceName($incomingString)
        );
    }

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