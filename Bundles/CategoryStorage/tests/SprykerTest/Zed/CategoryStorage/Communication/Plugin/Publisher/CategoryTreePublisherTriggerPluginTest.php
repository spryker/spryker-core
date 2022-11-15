<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CategoryStorage\Communication\Plugin\Publisher;

use Codeception\Test\Unit;
use Spryker\Zed\CategoryStorage\Communication\Plugin\Publisher\CategoryTreePublisherTriggerPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CategoryStorage
 * @group Communication
 * @group Plugin
 * @group Publisher
 * @group CategoryTreePublisherTriggerPluginTest
 * Add your own group annotations below this line
 */
class CategoryTreePublisherTriggerPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testGetDataReturnsNotEmptyArrayWithZeroOffset(): void
    {
        // Arrange
        $plugin = new CategoryTreePublisherTriggerPlugin();

        // Act
        $resultData = $plugin->getData(0, 1);

        // Assert
        $this->assertNotEmpty($resultData);
    }

    /**
     * @return void
     */
    public function testGetDataReturnsEmptyArrayWithNotZeroOffset(): void
    {
        // Arrange
        $plugin = new CategoryTreePublisherTriggerPlugin();

        // Act
        $resultData = $plugin->getData(1, 1);

        // Assert
        $this->assertEmpty($resultData);
    }
}
