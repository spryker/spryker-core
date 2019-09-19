<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\StepEngine\Process;

use Spryker\Yves\StepEngine\Process\StepBreadcrumbGenerator;
use SprykerTest\Yves\StepEngine\Process\Fixtures\StepMockWithBreadcrumbs;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group StepEngine
 * @group Process
 * @group StepBreadcrumbGeneratorTest
 * Add your own group annotations below this line
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
        $stepCollection->addStep($this->getStepMockWithBreadcrumbs());
        $stepCollection->addStep($this->getStepMockWithBreadcrumbs());
        $stepCollection->addStep($this->getStepMock());
        $stepCollection->addStep($this->getStepMockWithBreadcrumbs());
        $stepCollection->addStep($this->getStepMock());

        $stepBreadcrumbGenerator = new StepBreadcrumbGenerator();
        $stepBreadcrumbsTransfer = $stepBreadcrumbGenerator->generateStepBreadcrumbs($stepCollection);

        $this->assertCount(3, $stepBreadcrumbsTransfer->getBreadcrumbs(), 'Breadcrumbs should contain expected number of steps.');
    }

    /**
     * @return void
     */
    public function testGenerateStepBreadcrumbReturnExpectedNumberOfVisibleItems()
    {
        $stepCollection = $this->getStepCollection();
        $stepCollection->addStep($this->getStepMockWithBreadcrumbs(true, true, false));
        $stepCollection->addStep($this->getStepMockWithBreadcrumbs(true, true, true));
        $stepCollection->addStep($this->getStepMockWithBreadcrumbs(true, true, false));
        $stepCollection->addStep($this->getStepMockWithBreadcrumbs(true, true, true));

        $stepBreadcrumbGenerator = new StepBreadcrumbGenerator();

        $stepBreadcrumbsTransfer = $stepBreadcrumbGenerator->generateStepBreadcrumbs($stepCollection);
        $this->assertCount(4, $stepBreadcrumbsTransfer->getBreadcrumbs(), 'Breadcrumbs should contain expected number of visible steps when data transfer is not available.');

        $stepBreadcrumbsTransfer = $stepBreadcrumbGenerator->generateStepBreadcrumbs($stepCollection, $this->getDataTransferMock());
        $this->assertCount(2, $stepBreadcrumbsTransfer->getBreadcrumbs(), 'Breadcrumbs should contain expected number of visible steps when data transfer is available.');
    }

    /**
     * @return void
     */
    public function testGenerateStepBreadcrumbReturnItemsInExpectedOrder()
    {
        $stepCollection = $this->getStepCollection();
        $stepCollection->addStep($this->getStepMock());
        $stepCollection->addStep($this->getStepMockWithBreadcrumbs(false, false, true, 'foo'));
        $stepCollection->addStep($this->getStepMockWithBreadcrumbs(false, false, true, 'bar'));
        $stepCollection->addStep($this->getStepMock());
        $stepCollection->addStep($this->getStepMockWithBreadcrumbs(false, false, true, 'baz'));
        $stepCollection->addStep($this->getStepMock());

        $stepBreadcrumbGenerator = new StepBreadcrumbGenerator();
        $stepBreadcrumbsTransfer = $stepBreadcrumbGenerator->generateStepBreadcrumbs($stepCollection);
        $stepBreadcrumbItems = $stepBreadcrumbsTransfer->getBreadcrumbs();

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
        $stepCollection->addStep($this->getStepMockWithBreadcrumbs(true, true));
        $stepCollection->addStep($this->getStepMockWithBreadcrumbs(true, true));
        $stepCollection->addStep($this->getStepMock());
        $stepCollection->addStep($this->getStepMockWithBreadcrumbs(false, false));
        $stepCollection->addStep($this->getStepMock());

        $stepBreadcrumbGenerator = new StepBreadcrumbGenerator();
        $stepBreadcrumbsTransfer = $stepBreadcrumbGenerator->generateStepBreadcrumbs($stepCollection, $this->getDataTransferMock());
        $stepBreadcrumbItems = $stepBreadcrumbsTransfer->getBreadcrumbs();

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
        $stepCollection->addStep($this->getStepMockWithBreadcrumbs(true, true, true, 'foo'));
        $stepCollection->addStep($this->getStepMockWithBreadcrumbs(true, true, true, 'bar'));
        $stepCollection->addStep($this->getStepMock());
        $stepCollection->addStep($this->getStepMockWithBreadcrumbs(false, false, true, 'baz'));
        $stepCollection->addStep($this->getStepMock());

        $currentStep = $stepCollection->getCurrentStep($this->getRequest('bar'), $this->getDataTransferMock());

        $stepBreadcrumbGenerator = new StepBreadcrumbGenerator();
        $stepBreadcrumbsTransfer = $stepBreadcrumbGenerator->generateStepBreadcrumbs($stepCollection, $this->getDataTransferMock(), $currentStep);
        $stepBreadcrumbItems = $stepBreadcrumbsTransfer->getBreadcrumbs();

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
    protected function getStepMockWithBreadcrumbs($preCondition = true, $postCondition = true, $requireInput = true, $stepRoute = '', $escapeRoute = '')
    {
        return new StepMockWithBreadcrumbs($preCondition, $postCondition, $requireInput, $stepRoute, $escapeRoute);
    }
}
