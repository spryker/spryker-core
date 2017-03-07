<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Service\Kernel;

use PHPUnit_Framework_TestCase;
use Spryker\Service\Kernel\AbstractService;
use Spryker\Service\Kernel\AbstractServiceFactory;
use Unit\Spryker\Service\Kernel\Fixtures\Service;

/**
 * @group Unit
 * @group Spryker
 * @group Service
 * @group Kernel
 * @group AbstractServiceTest
 */
class AbstractServiceTest extends PHPUnit_Framework_TestCase
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
