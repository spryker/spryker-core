<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Yves\StepEngine\Process;

use Spryker\Shared\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\Step\StepWithExternalRedirectInterface;
use Spryker\Yves\StepEngine\Process\StepCollection;
use Spryker\Yves\StepEngine\Process\StepCollectionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Unit\Spryker\Yves\StepEngine\Process\Fixtures\StepMock;

class StepCollectionTest extends \PHPUnit_Framework_TestCase
{

    const ERROR_ROUTE = 'error-route';
    const ERROR_URL = '/error/url';

    const ESCAPE_ROUTE = 'escape-route';
    const ESCAPE_URL = '/escape/url';

    const STEP_ROUTE_A = 'step-route-a';
    const STEP_URL_A = '/step/url/a';

    const STEP_ROUTE_B = 'step-route-b';
    const STEP_URL_B = '/step/url/b';

    const STEP_ROUTE_C = 'step-route-c';
    const STEP_URL_C = '/step/url/c';

    const EXTERNAL_URL = 'http://external.de';

    /**
     * @return void
     */
    public function testInstantiation()
    {
        $stepCollection = new StepCollection($this->getUrlGeneratorMock(), self::ERROR_ROUTE);
        $this->assertInstanceOf(StepCollectionInterface::class, $stepCollection);
    }

    /**
     * @return void
     */
    public function testAddStep()
    {
        $stepCollection = new StepCollection($this->getUrlGeneratorMock(), self::ERROR_ROUTE);
        $stepCollection = $stepCollection->addStep($this->getStepMock());
        $this->assertInstanceOf(StepCollectionInterface::class, $stepCollection);
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
        $stepCollection = new StepCollection($this->getUrlGeneratorMock(), self::ERROR_ROUTE);
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
        $stepAPostTrue = $this->getStepMock(false, true, true, self::STEP_ROUTE_A);
        $stepAPostFalse = $this->getStepMock(false, false, true, self::STEP_ROUTE_A);

        $stepBPostTrue = $this->getStepMock(false, true, true, self::STEP_ROUTE_B);
        $stepBPostFalse = $this->getStepMock(false, false, true, self::STEP_ROUTE_B);

        $stepCPostTrue = $this->getStepMock(false, true, true, self::STEP_ROUTE_C);
        $stepCPostFalse = $this->getStepMock(false, false, true, self::STEP_ROUTE_C);

        return [
            // match by postCondition not fulfilled
            [[$stepAPostFalse, $stepBPostFalse, $stepCPostFalse], 0],
            [[$stepAPostTrue, $stepBPostFalse, $stepCPostFalse], 1],
            [[$stepAPostTrue, $stepBPostTrue, $stepCPostFalse], 2],
            // if all steps fulfilled and no route matches return last
            [[$stepAPostTrue, $stepBPostTrue, $stepCPostTrue], 2],
            // Match by route, all steps fulfill postCondition
            [[$stepAPostTrue, $stepBPostTrue, $stepCPostTrue], 0, self::STEP_ROUTE_A],
            [[$stepAPostTrue, $stepBPostTrue, $stepCPostTrue], 1, self::STEP_ROUTE_B],
            [[$stepAPostTrue, $stepBPostTrue, $stepCPostTrue], 2, self::STEP_ROUTE_C],
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
        $stepCollection = new StepCollection($this->getUrlGeneratorMock(), self::ERROR_ROUTE);
        foreach ($steps as $step) {
            $stepCollection->addStep($step);
        }

        $nextStep = $stepCollection->getNextStep($currentStep);
        $this->assertSame($expectedStep, $nextStep);
    }

    /**
     * @return array
     */
    public function nextStepDataProvider()
    {
        $stepA = $this->getStepMock(false, true, true, self::STEP_ROUTE_A);
        $stepB = $this->getStepMock(false, true, true, self::STEP_ROUTE_B);
        $stepC = $this->getStepMock(false, true, true, self::STEP_ROUTE_C);

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
        $stepCollection = new StepCollection($this->getUrlGeneratorMock(), self::ERROR_ROUTE);
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
        $stepA = $this->getStepMock(false, true, true, self::STEP_ROUTE_A);
        $stepB = $this->getStepMock(false, true, true, self::STEP_ROUTE_B);
        $stepC = $this->getStepMock(false, true, true, self::STEP_ROUTE_C);

        return [
            [[$stepA, $stepB, $stepC], $stepA, $stepA],
            [[$stepA, $stepB, $stepC], $stepB, $stepA],
            [[$stepA, $stepB, $stepC], $stepC, $stepB],
        ];
    }

    /**
     * @return void
     */
    public function testGetNextUrlShouldReturnExternalRedirectUrl()
    {
        $stepCollection = new StepCollection($this->getUrlGeneratorMock(), self::ERROR_ROUTE);
        $stepMock = $this->getStepWithExternalRedirectUrl();

        $this->assertSame(self::EXTERNAL_URL, $stepCollection->getNextUrl($stepMock, $this->getDataTransferMock()));
    }

    /**
     * @return void
     */
    public function testGetNextUrlShouldReturnErrorUrl()
    {
        $stepCollection = new StepCollection($this->getUrlGeneratorMock(), self::ERROR_ROUTE);
        $stepMock = $this->getStepMock(true, false, false);

        $this->assertSame(self::ERROR_URL, $stepCollection->getNextUrl($stepMock, $this->getDataTransferMock()));
    }

    /**
     * @return void
     */
    public function testGetNextUrlShouldReturnCurrentStepUrlIfPostConditionNotFulfilledAndInputRequired()
    {
        $stepCollection = new StepCollection($this->getUrlGeneratorMock(), self::ERROR_ROUTE);
        $stepMock = $this->getStepMock(true, false, true, self::STEP_ROUTE_A);

        $this->assertSame(self::STEP_URL_A, $stepCollection->getNextUrl($stepMock, $this->getDataTransferMock()));
    }

    /**
     * @return void
     */
    public function testGetNextUrlShouldReturnNextStepUrl()
    {
        $stepCollection = new StepCollection($this->getUrlGeneratorMock(), self::ERROR_ROUTE);
        $stepMockA = $this->getStepMock(true, true, false, self::STEP_ROUTE_A);
        $stepCollection->addStep($stepMockA);

        $stepMockB = $this->getStepMock(true, true, false, self::STEP_ROUTE_B);
        $stepCollection->addStep($stepMockB);

        $this->assertSame(self::STEP_URL_B, $stepCollection->getNextUrl($stepMockA, $this->getDataTransferMock()));
    }

    /**
     * @return void
     */
    public function testGetNextUrlShouldReturnCurrentStepUrlIfCurrentStepIsLasStep()
    {
        $stepCollection = new StepCollection($this->getUrlGeneratorMock(), self::ERROR_ROUTE);
        $stepMockA = $this->getStepMock(true, true, false, self::STEP_ROUTE_A);
        $stepCollection->addStep($stepMockA);

        $stepMockB = $this->getStepMock(true, true, false, self::STEP_ROUTE_B);
        $stepCollection->addStep($stepMockB);

        $this->assertSame(self::STEP_URL_B, $stepCollection->getNextUrl($stepMockB, $this->getDataTransferMock()));
    }

    /**
     * @return void
     */
    public function testPreviousUrl()
    {
        $stepCollection = new StepCollection($this->getUrlGeneratorMock(), self::ERROR_ROUTE);
        $stepMockA = $this->getStepMock(true, true, false, self::STEP_ROUTE_A);
        $stepCollection->addStep($stepMockA);

        $stepMockB = $this->getStepMock(true, true, false, self::STEP_ROUTE_B);
        $stepCollection->addStep($stepMockB);

        $this->assertSame(self::STEP_URL_A, $stepCollection->getPreviousUrl($stepMockB));
    }

    /**
     * @return void
     */
    public function testPreviousUrlShouldReturnCurrentStepUrlIfCurrentStepIsFirstStep()
    {
        $stepCollection = new StepCollection($this->getUrlGeneratorMock(), self::ERROR_ROUTE);
        $stepMockA = $this->getStepMock(true, true, false, self::STEP_ROUTE_A);
        $stepCollection->addStep($stepMockA);

        $this->assertSame(self::STEP_URL_A, $stepCollection->getPreviousUrl($stepMockA));
    }

    /**
     * @return void
     */
    public function testEscapeUrl()
    {
        $stepCollection = new StepCollection($this->getUrlGeneratorMock(), self::ERROR_ROUTE);
        $stepMockA = $this->getStepMock(true, true, false, self::STEP_ROUTE_A, self::ESCAPE_ROUTE);
        $stepCollection->addStep($stepMockA);

        $this->assertSame(self::ESCAPE_URL, $stepCollection->getEscapeUrl($stepMockA));
    }

    /**
     * @return void
     */
    public function testEscapeUrlShouldReturnStepRouteOfPreviousStepIfCurrentStepEscapeRouteIsNull()
    {
        $stepCollection = new StepCollection($this->getUrlGeneratorMock(), self::ERROR_ROUTE);
        $stepMockA = $this->getStepMock(true, true, false, self::STEP_ROUTE_A);
        $stepCollection->addStep($stepMockA);

        $stepMockB = $this->getStepMock(true, true, false, self::STEP_ROUTE_B, null);
        $stepCollection->addStep($stepMockB);

        $this->assertSame(self::STEP_URL_A, $stepCollection->getEscapeUrl($stepMockB));
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Symfony\Component\Routing\Generator\UrlGeneratorInterface
     */
    private function getUrlGeneratorMock()
    {
        $urlGeneratorMock = $this->getMockBuilder(UrlGeneratorInterface::class)->getMock();
        $urlGeneratorMock->method('generate')->will($this->returnCallback([$this, 'urlGeneratorCallBack']));

        return $urlGeneratorMock;
    }

    /**
     * @param string $input
     *
     * @return string
     */
    public function urlGeneratorCallBack($input)
    {
        $map = [
            self::ERROR_ROUTE => self::ERROR_URL,
            self::ESCAPE_ROUTE => self::ESCAPE_URL,
            self::STEP_ROUTE_A => self::STEP_URL_A,
            self::STEP_ROUTE_B => self::STEP_URL_B,
            self::STEP_ROUTE_C => self::STEP_URL_C,
        ];

        return $map[$input];
    }

    /**
     * @param bool $preCondition
     * @param bool $postCondition
     * @param bool $requireInput
     * @param string $stepRoute
     * @param string $escapeRoute
     *
     * @return \Spryker\Yves\StepEngine\Dependency\Step\StepInterface
     */
    private function getStepMock($preCondition = true, $postCondition = true, $requireInput = true, $stepRoute = '', $escapeRoute = '')
    {
        return new StepMock($preCondition, $postCondition, $requireInput, $stepRoute, $escapeRoute);
    }

    /**
     * @param string $route
     *
     * @return \Symfony\Component\HttpFoundation\Request
     */
    private function getRequest($route = '')
    {
        $request = Request::createFromGlobals();
        $request->request->set('_route', $route);

        return $request;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Shared\Transfer\AbstractTransfer
     */
    private function getDataTransferMock()
    {
        return $this->getMockBuilder(AbstractTransfer::class)->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Yves\StepEngine\Dependency\Step\StepWithExternalRedirectInterface
     */
    protected function getStepWithExternalRedirectUrl()
    {
        $stepMock = $this->getMock(StepWithExternalRedirectInterface::class);
        $stepMock->method('getExternalRedirectUrl')->willReturn(self::EXTERNAL_URL);

        return $stepMock;
    }

}
