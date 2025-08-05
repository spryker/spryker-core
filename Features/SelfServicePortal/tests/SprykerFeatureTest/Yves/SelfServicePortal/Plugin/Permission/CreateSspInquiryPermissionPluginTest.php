<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Yves\SelfServicePortal\Plugin\Permission;

use Codeception\Test\Unit;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\CreateSspInquiryPermissionPlugin;

class CreateSspInquiryPermissionPluginTest extends Unit
{
    /**
     * @var \SprykerFeature\Yves\SelfServicePortal\Plugin\Permission\CreateSspInquiryPermissionPlugin
     */
    protected CreateSspInquiryPermissionPlugin $plugin;

    protected function _before(): void
    {
        $this->plugin = new CreateSspInquiryPermissionPlugin();
    }

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
