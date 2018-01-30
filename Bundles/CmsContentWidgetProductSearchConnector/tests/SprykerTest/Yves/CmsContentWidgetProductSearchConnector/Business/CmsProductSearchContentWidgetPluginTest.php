<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CmsContentWidgetProductSetConnector\Business;

use Codeception\Test\Unit;
use Spryker\Shared\CmsContentWidget\Dependency\CmsContentWidgetConfigurationProviderInterface;
use Spryker\Yves\CmsContentWidgetProductSearchConnector\Plugin\CmsProductSearchContentWidgetPlugin;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
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
        new CmsProductSearchContentWidgetPlugin(
            $this->createCmsContentWidgetConfigurationProviderMock()
        );
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Shared\CmsContentWidget\Dependency\CmsContentWidgetConfigurationProviderInterface
     */
    protected function createCmsContentWidgetConfigurationProviderMock()
    {
        return $this->getMockBuilder(CmsContentWidgetConfigurationProviderInterface::class)->getMock();
    }
}
