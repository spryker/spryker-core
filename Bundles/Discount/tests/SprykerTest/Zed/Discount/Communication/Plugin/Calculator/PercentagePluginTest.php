<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Communication\Plugin\Calculator;

use Codeception\Test\Unit;
use Spryker\Zed\Discount\Communication\Plugin\Calculator\PercentagePlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Discount
 * @group Communication
 * @group Plugin
 * @group Calculator
 * @group PercentagePluginTest
 * Add your own group annotations below this line
 */
class PercentagePluginTest extends Unit
{
    /**
     * @return void
     */
    public function testTransformForPersistence()
    {
        $plugin = new PercentagePlugin();

        $result = $plugin->transformForPersistence(11.129);
        $this->assertSame(1113, $result);
    }

    /**
     * @return void
     */
    public function testTransformFromPersistence()
    {
        $plugin = new PercentagePlugin();

        $result = $plugin->transformFromPersistence(1113);
        $this->assertSame(11, $result);
    }
}
