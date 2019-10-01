<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\Kernel;

use Codeception\Test\Unit;
use Spryker\Service\Kernel\AbstractService;
use Spryker\Service\Kernel\AbstractServiceFactory;
use SprykerTest\Service\Kernel\Fixtures\Service;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group Kernel
 * @group AbstractServiceTest
 * Add your own group annotations below this line
 */
class AbstractServiceTest extends Unit
{
    /**
     * @return void
     */
    public function testSetFactoryWillReturnFluentInterface()
    {
        $abstractFactory = new AbstractServiceFactory();
        $abstractService = new AbstractService();

        $this->assertInstanceOf(AbstractService::class, $abstractService->setFactory($abstractFactory));
    }

    /**
     * @return void
     */
    public function testGetFactoryWillReturnAddedFactory()
    {
        $abstractFactory = new AbstractServiceFactory();
        $abstractService = new Service();
        $abstractService->setFactory($abstractFactory);

        $this->assertInstanceOf(AbstractServiceFactory::class, $abstractService->getFactory());
    }
}
