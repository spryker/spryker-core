<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Discount\Communication\Plugin\Calculator;

use Codeception\TestCase\Test;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Discount\Communication\Plugin\Calculator\FixedPlugin;

/**
 * @group Spryker
 * @group Zed
 * @group Communication
 * @group Plugin
 * @group FixedPluginTest
 *
 * @group Functional
 */
class FixedPluginTest extends Test
{

    /**
     * @return void
     */
    public function testTransformForPersistenceShouldConvertDecimalToInteger()
    {
        $plugin = new FixedPlugin();

        $result = $plugin->transformForPersistence(11.129);
        $this->assertSame(1113, $result);
    }

    /**
     * @return void
     */
    public function testTransformFromPersistenceShouldConvertIntegerToDecimalWithoutSymbol()
    {
        $this->setLocaleForTest();
        $plugin = new FixedPlugin();

        $result = $plugin->transformFromPersistence(1113);
        $this->assertSame('11,13', $result);
    }

    /**
     * @return void
     */
    private function setLocaleForTest()
    {
        Store::getInstance()->setCurrentLocale('de_DE');
    }

}
