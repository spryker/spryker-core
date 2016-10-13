<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Discount\Communication\Plugin\Calculator;

use Codeception\TestCase\Test;
use Spryker\Zed\Discount\Communication\Plugin\Calculator\FixedPlugin;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Discount
 * @group Communication
 * @group Plugin
 * @group Calculator
 * @group FixedPluginTest
 */
class FixedPluginTest extends Test
{

    /**
     * @return void
     */
    public function testTransformForPersistence()
    {
        $plugin = new FixedPlugin();

        $result = $plugin->transformForPersistence(11.129);
        $this->assertSame(1113, $result);
    }

    /**
     * @return void
     */
    public function testTransformFromPersistence()
    {
        $plugin = new FixedPlugin();

        $result = $plugin->transformFromPersistence(1113);
        $this->assertSame('11,13', $result);
    }

}
