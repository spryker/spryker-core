<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Yves\SspInquiry\Plugin\Permission;

use Codeception\Test\Unit;
use SprykerFeature\Shared\SspInquiryManagement\Plugin\Permission\CreateSspInquiryPermissionPlugin;

class CreateSspInquiryPermissionPluginTest extends Unit
{
    /**
     * @var \SprykerFeature\Yves\SspInquiryManagement\Plugin\Permission\CreateSspInquiryPermissionPlugin
     */
    protected CreateSspInquiryPermissionPlugin $plugin;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->plugin = new CreateSspInquiryPermissionPlugin();
    }

    /**
     * @return void
     */
    public function testGetKeyReturnsCorrectKey(): void
    {
        // Arrange
        $expectedKey = CreateSspInquiryPermissionPlugin::KEY;

        // Act
        $result = $this->plugin->getKey();

        // Assert
        $this->assertSame($expectedKey, $result);
    }
}
