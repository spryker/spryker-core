<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Yves\StepEngine\Process;

use Spryker\Yves\StepEngine\Process\StepBreadcrumbGenerator;
use Unit\Spryker\Yves\StepEngine\Process\Fixtures\StepMockWithBreadcrumb;

/**
 * @group Unit
 * @group Spryker
 * @group Yves
 * @group StepEngine
 * @group Process
 * @group StepBreadcrumbGeneratorTest
 */
class StepBreadcrumbGeneratorTest extends AbstractStepEngineTest
{

    /**
     * @return void
     */
    public function testGenerateStepBreadcrumbReturnExpectedNumberOfItems()
    {
        $stepCollection = $this->getStepCollection();
        $stepCollection->addStep($this->getStepMock());
        $stepCollection->addStep($this->getStepMockWithBreadcrumb());
        $stepCollection->addStep($this->getStepMockWithBreadcrumb());
        $stepCollection->addStep($this->getStepMock());
        $stepCollection->addStep($this->getStepMockWithBreadcrumb());
        $stepCollection->addStep($this->getStepMock());

        $stepBreadcrumbGenerator = new StepBreadcrumbGenerator();
        $stepBreadcrumbTransfer = $stepBreadcrumbGenerator->generateStepBreadcrumb($stepCollection);

        $this->assertCount(3, $stepBreadcrumbTransfer->getItems(), 'Breadcrumb should return expected number of steps.');
    }

    /**
     * @return void
     */
    public function testGenerateStepBreadcrumbReturnExpectedNumberOfVisibleItems()
    {
        $stepCollection = $this->getStepCollection();
        $stepCollection->addStep($this->getStepMockWithBreadcrumb(true, true, false));
        $stepCollection->addStep($this->getStepMockWithBreadcrumb(true, true, true));
        $stepCollection->addStep($this->getStepMockWithBreadcrumb(true, true, false));
        $stepCollection->addStep($this->getStepMockWithBreadcrumb(true, true, true));

        $stepBreadcrumbGenerator = new StepBreadcrumbGenerator();

        $stepBreadcrumbTransfer = $stepBreadcrumbGenerator->generateStepBreadcrumb($stepCollection);
        $this->assertCount(4, $stepBreadcrumbTransfer->getItems(), 'Breadcrumb should return expected number of visible steps when data transfer is not available.');

        $stepBreadcrumbTransfer = $stepBreadcrumbGenerator->generateStepBreadcrumb($stepCollection, $this->getDataTransferMock());
        $this->assertCount(2, $stepBreadcrumbTransfer->getItems(), 'Breadcrumb should return expected number of visible steps when data transfer is available.');
    }

    /**
     * @return void
     */
    public function testGenerateStepBreadcrumbReturnItemsInExpectedOrder()
    {
        $stepCollection = $this->getStepCollection();
        $stepCollection->addStep($this->getStepMock());
        $stepCollection->addStep($this->getStepMockWithBreadcrumb(false, false, true, 'foo'));
        $stepCollection->addStep($this->getStepMockWithBreadcrumb(false, false, true, 'bar'));
        $stepCollection->addStep($this->getStepMock());
        $stepCollection->addStep($this->getStepMockWithBreadcrumb(false, false, true, 'baz'));
        $stepCollection->addStep($this->getStepMock());

        $stepBreadcrumbGenerator = new StepBreadcrumbGenerator();
        $stepBreadcrumbTransfer = $stepBreadcrumbGenerator->generateStepBreadcrumb($stepCollection);
        $stepBreadcrumbItems = $stepBreadcrumbTransfer->getItems();

        $this->assertSame('foo', $stepBreadcrumbItems[0]->getTitle(), 'Item 1/3 should have expected route name.');
        $this->assertSame('bar', $stepBreadcrumbItems[1]->getTitle(), 'Item 2/3 should have expected route name.');
        $this->assertSame('baz', $stepBreadcrumbItems[2]->getTitle(), 'Item 3/3 should have expected route name.');
    }

    /**
     * @return void
     */
    public function testGenerateStepBreadcrumbReturnItemsWithExpectedEnabledStatus()
    {
        $stepCollection = $this->getStepCollection();
        $stepCollection->addStep($this->getStepMock());
        $stepCollection->addStep($this->getStepMockWithBreadcrumb(true, true));
        $stepCollection->addStep($this->getStepMockWithBreadcrumb(true, true));
        $stepCollection->addStep($this->getStepMock());
        $stepCollection->addStep($this->getStepMockWithBreadcrumb(false, false));
        $stepCollection->addStep($this->getStepMock());

        $stepBreadcrumbGenerator = new StepBreadcrumbGenerator();
        $stepBreadcrumbTransfer = $stepBreadcrumbGenerator->generateStepBreadcrumb($stepCollection, $this->getDataTransferMock());
        $stepBreadcrumbItems = $stepBreadcrumbTransfer->getItems();

        $this->assertTrue($stepBreadcrumbItems[0]->getIsEnabled(), 'Item 1/3 should be enabled.');
        $this->assertTrue($stepBreadcrumbItems[1]->getIsEnabled(), 'Item 2/3 should be enabled.');
        $this->assertFalse($stepBreadcrumbItems[2]->getIsEnabled(), 'Item 3/3 should not be enabled.');
    }

    /**
     * @return void
     */
    public function testGenerateStepBreadcrumbReturnItemsWithExpectedActiveStatus()
    {
        $stepCollection = $this->getStepCollection();
        $stepCollection->addStep($this->getStepMock());
        $stepCollection->addStep($this->getStepMockWithBreadcrumb(true, true, true, 'foo'));
        $stepCollection->addStep($this->getStepMockWithBreadcrumb(true, true, true, 'bar'));
        $stepCollection->addStep($this->getStepMock());
        $stepCollection->addStep($this->getStepMockWithBreadcrumb(false, false, true, 'baz'));
        $stepCollection->addStep($this->getStepMock());

        $currentStep = $stepCollection->getCurrentStep($this->getRequest('bar'), $this->getDataTransferMock());

        $stepBreadcrumbGenerator = new StepBreadcrumbGenerator();
        $stepBreadcrumbTransfer = $stepBreadcrumbGenerator->generateStepBreadcrumb($stepCollection, $this->getDataTransferMock(), $currentStep);
        $stepBreadcrumbItems = $stepBreadcrumbTransfer->getItems();

        $this->assertFalse($stepBreadcrumbItems[0]->getIsActive(), 'Item 1/3 should not be inactive.');
        $this->assertTrue($stepBreadcrumbItems[1]->getIsActive(), 'Item 2/3 should be active.');
        $this->assertFalse($stepBreadcrumbItems[2]->getIsActive(), 'Item 3/3 should not be active.');
    }

    /**
     * @param bool $preCondition
     * @param bool $postCondition
     * @param bool $requireInput
     * @param string $stepRoute
     * @param string $escapeRoute
     *
     * @return \Spryker\Yves\StepEngine\Dependency\Step\StepWithBreadcrumbInterface
     */
    protected function getStepMockWithBreadcrumb($preCondition = true, $postCondition = true, $requireInput = true, $stepRoute = '', $escapeRoute = '')
    {
        return new StepMockWithBreadcrumb($preCondition, $postCondition, $requireInput, $stepRoute, $escapeRoute);
    }

}
