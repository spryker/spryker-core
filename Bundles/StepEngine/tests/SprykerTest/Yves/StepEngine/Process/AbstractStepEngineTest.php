<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\StepEngine\Process;

use Codeception\Test\Unit;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\Step\StepInterface;
use Spryker\Yves\StepEngine\Dependency\Step\StepWithExternalRedirectInterface;
use Spryker\Yves\StepEngine\Process\StepCollection;
use SprykerTest\Yves\StepEngine\Process\Fixtures\StepMock;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group StepEngine
 * @group Process
 * @group AbstractStepEngineTest
 * Add your own group annotations below this line
 */
abstract class AbstractStepEngineTest extends Unit
{
    /**
     * @var string
     */
    public const ERROR_ROUTE = 'error-route';

    /**
     * @var string
     */
    public const ERROR_URL = '/error/url';

    /**
     * @var string
     */
    public const ESCAPE_ROUTE = 'escape-route';

    /**
     * @var string
     */
    public const ESCAPE_URL = '/escape/url';

    /**
     * @var string
     */
    public const STEP_ROUTE_A = 'step-route-a';

    /**
     * @var string
     */
    public const STEP_URL_A = '/step/url/a';

    /**
     * @var string
     */
    public const STEP_ROUTE_B = 'step-route-b';

    /**
     * @var string
     */
    public const STEP_URL_B = '/step/url/b';

    /**
     * @var string
     */
    public const STEP_ROUTE_C = 'step-route-c';

    /**
     * @var string
     */
    public const STEP_URL_C = '/step/url/c';

    /**
     * @var string
     */
    public const STEP_ROUTE_D = 'step-route-d';

    /**
     * @var string
     */
    public const STEP_URL_D = '/step/url/d';

    /**
     * @var string
     */
    public const EXTERNAL_URL = 'http://external.de';

    /**
     * @return \Spryker\Yves\StepEngine\Process\StepCollection
     */
    protected function getStepCollection(): StepCollection
    {
        return new StepCollection($this->getUrlGeneratorMock(), static::ERROR_ROUTE);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Routing\Generator\UrlGeneratorInterface
     */
    protected function getUrlGeneratorMock(): UrlGeneratorInterface
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
    public function urlGeneratorCallBack(string $input): string
    {
        $map = [
            static::ERROR_ROUTE => static::ERROR_URL,
            static::ESCAPE_ROUTE => static::ESCAPE_URL,
            static::STEP_ROUTE_A => static::STEP_URL_A,
            static::STEP_ROUTE_B => static::STEP_URL_B,
            static::STEP_ROUTE_C => static::STEP_URL_C,
        ];

        return $map[$input];
    }

    /**
     * @param bool $preCondition
     * @param bool $postCondition
     * @param bool $requireInput
     * @param string $stepRoute
     * @param string|null $escapeRoute
     *
     * @return \Spryker\Yves\StepEngine\Dependency\Step\StepInterface
     */
    protected function getStepMock(
        bool $preCondition = true,
        bool $postCondition = true,
        bool $requireInput = true,
        string $stepRoute = '',
        ?string $escapeRoute = null
    ): StepInterface {
        return new StepMock($preCondition, $postCondition, $requireInput, $stepRoute, $escapeRoute);
    }

    /**
     * @param string $route
     *
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function getRequest(string $route = ''): Request
    {
        $request = Request::createFromGlobals();
        $request->request->set('_route', $route);

        return $request;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function getDataTransferMock(): AbstractTransfer
    {
        return $this->getMockBuilder(AbstractTransfer::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Yves\StepEngine\Dependency\Step\StepWithExternalRedirectInterface
     */
    protected function getStepWithExternalRedirectUrl(): StepWithExternalRedirectInterface
    {
        $stepMock = $this->getMockBuilder(StepWithExternalRedirectInterface::class)->getMock();
        $stepMock->method('getExternalRedirectUrl')->willReturn(static::EXTERNAL_URL);

        return $stepMock;
    }
}
