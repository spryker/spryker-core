<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Router\Communication\Plugin\Router\RouterEnhancer;

use Codeception\Test\Unit;
use Spryker\Zed\Router\Communication\Plugin\Router\RouterEnhancer\BackwardsCompatibleUrlRouterEnhancerPlugin;
use Symfony\Component\Routing\RequestContext;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Router
 * @group Communication
 * @group Plugin
 * @group Router
 * @group RouterEnhancer
 * @group BackwardsCompatibleUrlRouterEnhancerPluginTest
 * Add your own group annotations below this line
 */
class BackwardsCompatibleUrlRouterEnhancerPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testBeforeMatchConvertsCamelCasedUrlsToDashedUrls()
    {
        $backwardsCompatibleUrlRouterEnhancerPlugin = new BackwardsCompatibleUrlRouterEnhancerPlugin();

        $convertedPathInfo = $backwardsCompatibleUrlRouterEnhancerPlugin->beforeMatch('/someUrl/withCamel/caseVariants', new RequestContext());

        $this->assertSame('/some-url/with-camel/case-variants', $convertedPathInfo);
    }
}
