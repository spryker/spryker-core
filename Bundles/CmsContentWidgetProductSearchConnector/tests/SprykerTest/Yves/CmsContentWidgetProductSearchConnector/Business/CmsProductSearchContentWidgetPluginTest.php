<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\CmsContentWidgetProductSetConnector\Business;

use Codeception\Test\Unit;
use Spryker\Shared\CmsContentWidget\Dependency\CmsContentWidgetConfigurationProviderInterface;
use Spryker\Yves\CmsContentWidget\Dependency\CmsContentWidgetPluginInterface;
use Spryker\Yves\CmsContentWidgetProductSearchConnector\Plugin\CmsProductSearchContentWidgetPlugin;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Yves
 * @group CmsContentWidgetProductSetConnector
 * @group Business
 * @group CmsProductSearchContentWidgetPluginTest
 * Add your own group annotations below this line
 */
class CmsProductSearchContentWidgetPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testCmsProductSearchContentWidgetPluginCreated()
    {
        $plugin = new CmsProductSearchContentWidgetPlugin(
            $this->createCmsContentWidgetConfigurationProviderMock()
        );

        $this->assertInstanceOf(CmsContentWidgetPluginInterface::class, $plugin);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\CmsContentWidget\Dependency\CmsContentWidgetConfigurationProviderInterface
     */
    protected function createCmsContentWidgetConfigurationProviderMock()
    {
        return $this->getMockBuilder(CmsContentWidgetConfigurationProviderInterface::class)->getMock();
    }
}
