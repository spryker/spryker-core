<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\StepEngine\Process;

use Spryker\Yves\StepEngine\Process\StepCollection;
use Spryker\Yves\StepEngine\Process\StepCollectionInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group StepEngine
 * @group Process
 * @group StepCollectionTest
 * Add your own group annotations below this line
 */
class StepCollectionTest extends AbstractStepEngineTest
{
    /**
     * @return void
     */
    public function testInstantiation()
    {
        $stepCollection = new StepCollection($this->getUrlGeneratorMock(), static::ERROR_ROUTE);
        $this->assertInstanceOf(StepCollectionInterface::class, $stepCollection);
    }

    /**
     * @return void
     */
    public function testAddStep()
    {
        $stepCollection = new StepCollection($this->getUrlGeneratorMock(), static::ERROR_ROUTE);
        $stepCollection = $stepCollection->addStep($this->getStepMock());
        $this->assertInstanceOf(StepCollectionInterface::class, $stepCollection);
    }

    /**
     * @return void
     */
    public function testCanAccessStepReturnTrue()
    {
        $stepCollection = new StepCollection($this->getUrlGeneratorMock(), static::ERROR_ROUTE);
        $stepMock = $this->getStepMock(false, false, false, static::STEP_ROUTE_A);
        $stepCollection = $stepCollection->addStep($stepMock);

        $this->assertTrue($stepCollection->canAccessStep($stepMock, $this->getRequest(static::STEP_ROUTE_A), $this->getDataTransferMock()));
    }

    /**
     * @return void
     */
    public function testCanAccessStepReturnTrueForFulfilledStep()
    {
        $stepCollection = new StepCollection($this->getUrlGeneratorMock(), static::ERROR_ROUTE);
        $stepMockA = $this->getStepMock(false, true, false, static::STEP_ROUTE_A);
        $stepCollection->addStep($stepMockA);

        $stepMockB = $this->getStepMock(false, true, false, static::STEP_ROUTE_B);
        $stepCollection->addStep($stepMockB);

        $this->assertTrue($stepCollection->canAccessStep($stepMockB, $this->getRequest(static::STEP_ROUTE_A), $this->getDataTransferMock()));
    }

    /**
     * @return void
     */
    public function testCanAccessStepReturnFalse()
    {
        $stepCollection = new StepCollection($this->getUrlGeneratorMock(), static::ERROR_ROUTE);
        $stepMockA = $this->getStepMock(false, false, false, static::STEP_ROUTE_A);
        $stepCollection->addStep($stepMockA);

        $stepMockB = $this->getStepMock(false, true, false, static::STEP_ROUTE_B);
        $stepCollection->addStep($stepMockB);

        $this->assertFalse($stepCollection->canAccessStep($stepMockB, $this->getRequest(static::STEP_ROUTE_A), $this->getDataTransferMock()));
    }

    /**
     * @dataProvider currentStepDataProvider
     *
     * @param array $steps
     * @param int $expectedCurrentPosition
     * @param string $route
     *
     * @return void
     */
    public function testGetCurrentStep(array $steps, $expectedCurrentPosition, $route = '')
    {
        $stepCollection = new StepCollection($this->getUrlGeneratorMock(), static::ERROR_ROUTE);
        foreach ($steps as $step) {
            $stepCollection->addStep($step);
        }

        $currentStep = $stepCollection->getCurrentStep($this->getRequest($route), $this->getDataTransferMock());
        $this->assertSame($steps[$expectedCurrentPosition], $currentStep);
    }

    /**
     * @return array
     */
    public function currentStepDataProvider()
    {
        $stepAPostTrue = $this->getStepMock(false, true, true, static::STEP_ROUTE_A);
        $stepAPostFalse = $this->getStepMock(false, false, true, static::STEP_ROUTE_A);

        $stepBPostTrue = $this->getStepMock(false, true, true, static::STEP_ROUTE_B);
        $stepBPostFalse = $this->getStepMock(false, false, true, static::STEP_ROUTE_B);

        $stepCPostTrue = $this->getStepMock(false, true, true, static::STEP_ROUTE_C);
        $stepCPostFalse = $this->getStepMock(false, false, true, static::STEP_ROUTE_C);

        return [
            // match by postCondition not fulfilled
            [[$stepAPostFalse, $stepBPostFalse, $stepCPostFalse], 0],
            [[$stepAPostTrue, $stepBPostFalse, $stepCPostFalse], 1],
            [[$stepAPostTrue, $stepBPostTrue, $stepCPostFalse], 2],
            // if all steps fulfilled and no route matches return last
            [[$stepAPostTrue, $stepBPostTrue, $stepCPostTrue], 2],
            // Match by route, all steps fulfill postCondition
            [[$stepAPostTrue, $stepBPostTrue, $stepCPostTrue], 0, static::STEP_ROUTE_A],
            [[$stepAPostTrue, $stepBPostTrue, $stepCPostTrue], 1, static::STEP_ROUTE_B],
            [[$stepAPostTrue, $stepBPostTrue, $stepCPostTrue], 2, static::STEP_ROUTE_C],
        ];
    }

    /**
     * @dataProvider nextStepDataProvider
     *
     * @param array $steps
     * @param \Spryker\Yves\StepEngine\Dependency\Step\StepInterface $currentStep
     * @param \Spryker\Yves\StepEngine\Dependency\Step\StepInterface $expectedStep
     *
     * @return void
     */
    public function testGetNextStep(array $steps, $currentStep, $expectedStep)
    {
        $stepCollection = new StepCollection($this->getUrlGeneratorMock(), static::ERROR_ROUTE);
        foreach ($steps as $step) {
            $stepCollection->addStep($step);
        }

        $nextStep = $stepCollection->getNextStep($currentStep);
        $this->assertSame(get_class($expectedStep), get_class($nextStep));
    }

    /**
     * @return array
     */
    public function nextStepDataProvider()
    {
        $stepA = $this->getStepMock(false, true, true, static::STEP_ROUTE_A);
        $stepB = $this->getStepMock(false, true, true, static::STEP_ROUTE_B);
        $stepC = $this->getStepMock(false, true, true, static::STEP_ROUTE_C);

        return [
            [[$stepA, $stepB, $stepC], $stepA, $stepB],
            [[$stepA, $stepB, $stepC], $stepB, $stepC],
            [[$stepA, $stepB, $stepC], $stepC, $stepC],
        ];
    }

    /**
     * @dataProvider previousStepDataProvider
     *
     * @param array $steps
     * @param \Spryker\Yves\StepEngine\Dependency\Step\StepInterface $currentStep
     * @param \Spryker\Yves\StepEngine\Dependency\Step\StepInterface $expectedStep
     *
     * @return void
     */
    public function testGetPreviousStep(array $steps, $currentStep, $expectedStep)
    {
        $stepCollection = new StepCollection($this->getUrlGeneratorMock(), static::ERROR_ROUTE);
        foreach ($steps as $step) {
            $stepCollection->addStep($step);
        }

        $previous = $stepCollection->getPreviousStep($currentStep);
        $this->assertSame($expectedStep, $previous);
    }

    /**
     * @return array
     */
    public function previousStepDataProvider()
    {
        $stepA = $this->getStepMock(false, true, true, static::STEP_ROUTE_A);
        $stepB = $this->getStepMock(false, true, true, static::STEP_ROUTE_B);
        $stepC = $this->getStepMock(false, true, true, static::STEP_ROUTE_C);

        return [
            [[$stepA, $stepB, $stepC], $stepA, $stepA],
            [[$stepA, $stepB, $stepC], $stepB, $stepA],
            [[$stepA, $stepB, $stepC], $stepC, $stepB],
        ];
    }

    /**
     * @return void
     */
    public function testGetPreviousStepReturnsStepBeforeVirtualStep()
    {
        $stepCollection = new StepCollection($this->getUrlGeneratorMock(), static::ERROR_ROUTE);
        $entryStep = $this->getStepMock(true, true, false, static::STEP_ROUTE_A);
        $stepCollection->addStep($entryStep);

        $stepBeforeVirtualStep = $this->getStepMock(true, true, true, static::STEP_ROUTE_B);
        $stepCollection->addStep($stepBeforeVirtualStep);

        $virtualStep = $this->getStepMock(true, true, false, static::STEP_ROUTE_C);
        $stepCollection->addStep($virtualStep);

        $currentStep = $this->getStepMock(true, true, true, static::STEP_ROUTE_D);
        $stepCollection->addStep($currentStep);

        $previous = $stepCollection->getPreviousStep($currentStep, $this->getDataTransferMock());
        $this->assertSame($stepBeforeVirtualStep, $previous, sprintf('Expected step "%s" got "%s"', $stepBeforeVirtualStep->getStepRoute(), $previous->getStepRoute()));
    }

    /**
     * @return void
     */
    public function testGetPreviousStepReturnsFirstStepIfStepBeforeVirtualStepIsFirstStep()
    {
        $stepCollection = new StepCollection($this->getUrlGeneratorMock(), static::ERROR_ROUTE);
        $entryStep = $this->getStepMock(true, true, false, static::STEP_ROUTE_A);
        $stepCollection->addStep($entryStep);

        $virtualStep = $this->getStepMock(true, true, false, static::STEP_ROUTE_B);
        $stepCollection->addStep($virtualStep);

        $currentStep = $this->getStepMock(true, true, true, static::STEP_ROUTE_C);
        $stepCollection->addStep($currentStep);

        $previous = $stepCollection->getPreviousStep($currentStep, $this->getDataTransferMock());
        $this->assertSame($entryStep, $previous, sprintf('Expected step "%s" got "%s"', $entryStep->getStepRoute(), $previous->getStepRoute()));
    }

    /**
     * @return void
     */
    public function testGetCurrentUrl()
    {
        $stepCollection = new StepCollection($this->getUrlGeneratorMock(), static::ERROR_ROUTE);
        $stepMock = $this->getStepMock(false, false, false, static::STEP_ROUTE_A);

        $this->assertSame(static::STEP_URL_A, $stepCollection->getCurrentUrl($stepMock));
    }

    /**
     * @return void
     */
    public function testGetNextUrlShouldReturnExternalRedirectUrl()
    {
        $stepCollection = new StepCollection($this->getUrlGeneratorMock(), static::ERROR_ROUTE);
        $stepMock = $this->getStepWithExternalRedirectUrl();

        $this->assertSame(static::EXTERNAL_URL, $stepCollection->getNextUrl($stepMock, $this->getDataTransferMock()));
    }

    /**
     * @return void
     */
    public function testGetNextUrlShouldReturnErrorUrl()
    {
        $stepCollection = new StepCollection($this->getUrlGeneratorMock(), static::ERROR_ROUTE);
        $stepMock = $this->getStepMock(true, false, false);

        $this->assertSame(static::ERROR_URL, $stepCollection->getNextUrl($stepMock, $this->getDataTransferMock()));
    }

    /**
     * @return void
     */
    public function testGetNextUrlShouldReturnCurrentStepUrlIfPostConditionNotFulfilledAndInputRequired()
    {
        $stepCollection = new StepCollection($this->getUrlGeneratorMock(), static::ERROR_ROUTE);
        $stepMock = $this->getStepMock(true, false, true, static::STEP_ROUTE_A);

        $this->assertSame(static::STEP_URL_A, $stepCollection->getNextUrl($stepMock, $this->getDataTransferMock()));
    }

    /**
     * @return void
     */
    public function testGetNextUrlShouldReturnNextStepUrl()
    {
        $stepCollection = new StepCollection($this->getUrlGeneratorMock(), static::ERROR_ROUTE);
        $stepMockA = $this->getStepMock(true, true, false, static::STEP_ROUTE_A);
        $stepCollection->addStep($stepMockA);

        $stepMockB = $this->getStepMock(true, true, false, static::STEP_ROUTE_B);
        $stepCollection->addStep($stepMockB);

        $this->assertSame(static::STEP_URL_B, $stepCollection->getNextUrl($stepMockA, $this->getDataTransferMock()));
    }

    /**
     * @return void
     */
    public function testGetNextUrlShouldReturnCurrentStepUrlIfCurrentStepIsLastStep()
    {
        $stepCollection = new StepCollection($this->getUrlGeneratorMock(), static::ERROR_ROUTE);
        $stepMockA = $this->getStepMock(true, true, false, static::STEP_ROUTE_A);
        $stepCollection->addStep($stepMockA);

        $stepMockB = $this->getStepMock(true, true, false, static::STEP_ROUTE_B);
        $stepCollection->addStep($stepMockB);

        $this->assertSame(static::STEP_URL_B, $stepCollection->getNextUrl($stepMockB, $this->getDataTransferMock()));
    }

    /**
     * @return void
     */
    public function testPreviousUrl()
    {
        $stepCollection = new StepCollection($this->getUrlGeneratorMock(), static::ERROR_ROUTE);
        $stepMockA = $this->getStepMock(true, true, false, static::STEP_ROUTE_A);
        $stepCollection->addStep($stepMockA);

        $stepMockB = $this->getStepMock(true, true, false, static::STEP_ROUTE_B);
        $stepCollection->addStep($stepMockB);

        $this->assertSame(static::STEP_URL_A, $stepCollection->getPreviousUrl($stepMockB));
    }

    /**
     * @return void
     */
    public function testPreviousUrlShouldReturnCurrentStepUrlIfCurrentStepIsFirstStep()
    {
        $stepCollection = new StepCollection($this->getUrlGeneratorMock(), static::ERROR_ROUTE);
        $stepMockA = $this->getStepMock(true, true, false, static::STEP_ROUTE_A);
        $stepCollection->addStep($stepMockA);

        $this->assertSame(static::STEP_URL_A, $stepCollection->getPreviousUrl($stepMockA));
    }

    /**
     * @return void
     */
    public function testEscapeUrl()
    {
        $stepCollection = new StepCollection($this->getUrlGeneratorMock(), static::ERROR_ROUTE);
        $stepMockA = $this->getStepMock(true, true, false, static::STEP_ROUTE_A, static::ESCAPE_ROUTE);
        $stepCollection->addStep($stepMockA);

        $this->assertSame(static::ESCAPE_URL, $stepCollection->getEscapeUrl($stepMockA));
    }

    /**
     * @return void
     */
    public function testEscapeUrlShouldReturnStepRouteOfPreviousStepIfCurrentStepEscapeRouteIsNull()
    {
        $stepCollection = new StepCollection($this->getUrlGeneratorMock(), static::ERROR_ROUTE);
        $stepMockA = $this->getStepMock(true, true, false, static::STEP_ROUTE_A);
        $stepCollection->addStep($stepMockA);

        $stepMockB = $this->getStepMock(true, true, false, static::STEP_ROUTE_B, null);
        $stepCollection->addStep($stepMockB);

        $this->assertSame(static::STEP_URL_A, $stepCollection->getEscapeUrl($stepMockB));
    }
}
