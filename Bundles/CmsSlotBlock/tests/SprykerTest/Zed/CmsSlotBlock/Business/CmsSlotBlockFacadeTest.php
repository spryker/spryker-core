<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CmsSlotBlock\Business;

use Codeception\Test\Unit;
use Spryker\Zed\CmsSlotBlock\Business\CmsSlotBlockBusinessFactory;
use Spryker\Zed\CmsSlotBlock\Business\CmsSlotBlockFacade;
use Spryker\Zed\CmsSlotBlock\Business\CmsSlotBlockFacadeInterface;
use SprykerTest\Zed\CmsSlotBlock\CmsSlotBlockConfigTest;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CmsSlotBlock
 * @group Business
 * @group Facade
 * @group CmsSlotBlockFacadeTest
 * Add your own group annotations below this line
 */
class CmsSlotBlockFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CmsSlotBlock\CmsSlotBlockBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetTemplateConditionsByPathSuccess(): void
    {
        //Act
        $templateConditionsAssignment = $this->createCmsSlotBlockFacade()
            ->getTemplateConditionsByPath('@Test/test/test/test.twig');

        //Assert
        $this->assertIsArray($templateConditionsAssignment);
        $this->assertEquals(['test'], $templateConditionsAssignment);
    }

    /**
     * @return void
     */
    public function testGetTemplateConditionsByPathFailed(): void
    {
        //Act
        $templateConditionsAssignment = $this->createCmsSlotBlockFacade()
            ->getTemplateConditionsByPath('@Test/test/test/test2.twig');

        //Assert
        $this->assertIsArray($templateConditionsAssignment);
        $this->assertEmpty($templateConditionsAssignment);
    }

    /**
     * @return \Spryker\Zed\CmsSlotBlock\Business\CmsSlotBlockFacadeInterface
     */
    protected function createCmsSlotBlockFacade(): CmsSlotBlockFacadeInterface
    {
        $factory = new CmsSlotBlockBusinessFactory();
        $config = new CmsSlotBlockConfigTest();
        $factory->setConfig($config);

        $facade = new CmsSlotBlockFacade();
        $facade->setFactory($factory);

        return $facade;
    }
}
