<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\StepEngine\Process;

use Codeception\Test\Unit;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\Step\StepWithExternalRedirectInterface;
use Spryker\Yves\StepEngine\Process\StepCollection;
use SprykerTest\Yves\StepEngine\Process\Fixtures\StepMock;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Yves
 * @group StepEngine
 * @group Process
 * @group AbstractStepEngineTest
 * Add your own group annotations below this line
 */
abstract class AbstractStepEngineTest extends Unit
{
    public const ERROR_ROUTE = 'error-route';
    public const ERROR_URL = '/error/url';

    public const ESCAPE_ROUTE = 'escape-route';
    public const ESCAPE_URL = '/escape/url';

    public const STEP_ROUTE_A = 'step-route-a';
    public const STEP_URL_A = '/step/url/a';

    public const STEP_ROUTE_B = 'step-route-b';
    public const STEP_URL_B = '/step/url/b';

    public const STEP_ROUTE_C = 'step-route-c';
    public const STEP_URL_C = '/step/url/c';

    public const STEP_ROUTE_D = 'step-route-d';
    public const STEP_URL_D = '/step/url/d';

    public const EXTERNAL_URL = 'http://external.de';

    /**
     * @return \Spryker\Yves\StepEngine\Process\StepCollection
     */
    protected function getStepCollection()
    {
        return new StepCollection($this->getUrlGeneratorMock(), self::ERROR_ROUTE);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Routing\Generator\UrlGeneratorInterface
     */
    protected function getUrlGeneratorMock()
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
    protected function getStepMock($preCondition = true, $postCondition = true, $requireInput = true, $stepRoute = '', $escapeRoute = '')
    {
        return new StepMock($preCondition, $postCondition, $requireInput, $stepRoute, $escapeRoute);
    }

    /**
     * @param string $route
     *
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function getRequest($route = '')
    {
        $request = Request::createFromGlobals();
        $request->request->set('_route', $route);

        return $request;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function getDataTransferMock()
    {
        return $this->getMockBuilder(AbstractTransfer::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Yves\StepEngine\Dependency\Step\StepWithExternalRedirectInterface
     */
    protected function getStepWithExternalRedirectUrl()
    {
        $stepMock = $this->getMockBuilder(StepWithExternalRedirectInterface::class)->getMock();
        $stepMock->method('getExternalRedirectUrl')->willReturn(self::EXTERNAL_URL);

        return $stepMock;
    }
}
