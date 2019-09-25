<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Twig\Business;

use Codeception\Test\Unit;
use Spryker\Zed\Twig\Business\Model\CacheWarmerInterface;
use Spryker\Zed\Twig\Business\TwigBusinessFactory;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Twig
 * @group Business
 * @group TwigBusinessFactoryTest
 * Add your own group annotations below this line
 */
class TwigBusinessFactoryTest extends Unit
{
    /**
     * @return void
     */
    public function testCreateCacheWarmerReturnsCacheWarmerInterface()
    {
        $twigBusinessFactory = new TwigBusinessFactory();
        $this->assertInstanceOf(CacheWarmerInterface::class, $twigBusinessFactory->createCacheWarmer());
    }
}
