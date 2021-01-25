<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Communication\Plugin\Calculator;

use Codeception\Test\Unit;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Discount\Communication\Plugin\Calculator\FixedPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Discount
 * @group Communication
 * @group Plugin
 * @group Calculator
 * @group FixedPluginTest
 * Add your own group annotations below this line
 */
class FixedPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testTransformForPersistenceShouldConvertDecimalToInteger(): void
    {
        $plugin = new FixedPlugin();

        $result = $plugin->transformForPersistence(11.129);
        $this->assertSame(1113, $result);
    }

    /**
     * @return void
     */
    public function testTransformFromPersistenceShouldConvertIntegerToDecimalWithoutSymbol(): void
    {
        $this->setLocaleForTest();
        $plugin = new FixedPlugin();

        $result = $plugin->transformFromPersistence(1113);

        $this->assertSame(11.13, $result, '');
        $this->assertEqualsWithDelta(11.13, $result, 0.001, '');
    }

    /**
     * @return void
     */
    private function setLocaleForTest(): void
    {
        Store::getInstance()->setCurrentLocale('de_DE');
    }
}
