<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Yves\StepEngine\Process;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\StepEngine\Form\FormCollectionHandlerInterface;
use Spryker\Client\Cart\CartClientInterface;
use Spryker\Yves\StepEngine\Process\StepProcess;
use Spryker\Yves\StepEngine\Process\Steps\StepInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class StepProcessTest extends \PHPUnit_Framework_TestCase
{
    const ROUTE_TO_SOMEWHERE = '/route/to/somewhere';

    /**
     * @return void
     */
    public function testProcessPreCheckShouldReturnRedirectResponseWhenPreConditionReturnsFalse()
    {
        $escapeRoute = 'escape_route';
        $stepMock = $this->createStepMock();
        $stepMock->expects($this->any())->method('preCondition')->willReturn(false);
        $stepMock->expects($this->any())->method('getEscapeRoute')->willReturn($escapeRoute);

        $stepProcess = $this->createStepProcess([$stepMock], new QuoteTransfer());
        $response = $stepProcess->process($this->createRequest());

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals($escapeRoute, $response->getTargetUrl());
    }

    /**
     * @return void
     */
    public function testProcessShouldReturnRedirectResponseWhenCanAccessStepReturnFalse()
    {
        $stepMock = $this->createStepMock();
        $stepMock->expects($this->any())->method('preCondition')->willReturn(true);
        $stepMock->method('getStepRoute')->willReturn(self::ROUTE_TO_SOMEWHERE);

        $stepProcess = $this->createStepProcess([$stepMock], new QuoteTransfer());
        $response = $stepProcess->process(Request::createFromGlobals());

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(self::ROUTE_TO_SOMEWHERE, $response->getTargetUrl());
    }

    /**
     * @return void
     */
    public function testProcessExecuteWithoutInputAndReturnTemplateVariables()
    {
        $stepMock = $this->createStepMock();
        $stepMock->expects($this->any())->method('preCondition')->willReturn(true);
        $stepMock->expects($this->any())->method('requireInput')->willReturn(true);
        $stepMock->expects($this->any())->method('execute')->willReturnCallback(
            function (Request $request, QuoteTransfer $quoteTransfer) {
                $quoteTransfer->addItem(new ItemTransfer()); //Modify quote transfer

                return $quoteTransfer;
            }
        );

        $stepProcess = $this->createStepProcess([$stepMock], new QuoteTransfer());
        $response = $stepProcess->process($this->createRequest());

        $this->assertInternalType('array', $response);
    }

    /**
     * @return void
     */
    public function testCurrentStepReturnFirstStep()
    {
        $stepMock1 = $this->createStepMock();
        $stepMock1->expects($this->any())->method('postCondition')->willReturn(true);
        $stepMock1->expects($this->any())->method('getStepRoute')->willReturn(self::ROUTE_TO_SOMEWHERE);
        $stepMock1->expects($this->any())->method('execute')->willReturnCallback(
            function (Request $request, QuoteTransfer $quoteTransfer) {
                $quoteTransfer->addItem(new ItemTransfer()); //Modify quote transfer

                return $quoteTransfer;
            }
        );

        $stepMock2 = $this->createStepMock();
        $stepMock2->expects($this->any())->method('postCondition')->willReturn(true);
        $stepMock2->expects($this->any())->method('getStepRoute')->willReturn(self::ROUTE_TO_SOMEWHERE);

        $stepMock3 = $this->createStepMock();
        $stepMock3->expects($this->any())->method('postCondition')->willReturn(true);
        $stepMock3->expects($this->any())->method('getStepRoute')->willReturn(self::ROUTE_TO_SOMEWHERE);

        $stepProcess = $this->createStepProcess([$stepMock1, $stepMock2, $stepMock3], new QuoteTransfer());
        $response = $stepProcess->process($this->createRequest());

        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    /**
     * @return void
     */
    public function testGoBackToPreviousCompletedStep()
    {

    }

    /**
     * @return void
     */
    public function testProcessWhenRequireInputIsFalseShouldCallExecuteWithoutTriggeringFormHandling()
    {
        $stepRoute = 'test';
        $nextStepRoute = 'next_step_route';
        $quoteTransfer = new QuoteTransfer();

        $stepMock = $this->createStepMock();
        $stepMock->expects($this->any())->method('preCondition')->willReturn(true);
        $stepMock->expects($this->exactly(2))->method('postCondition')->willReturn(true);
        $stepMock->expects($this->exactly(4))->method('getStepRoute')->willReturn($stepRoute);
        $stepMock->expects($this->any())->method('requireInput')->willReturn(false);
        $stepMock->expects($this->any())->method('execute')->willReturnCallback(
            function (Request $request, QuoteTransfer $quoteTransfer) {
                $quoteTransfer->addItem(new ItemTransfer()); //Modify quote transfer

                return $quoteTransfer;
            }
        );

        $nextStepMock = $this->createStepMock();
        $nextStepMock->expects($this->exactly(1))->method('getStepRoute')->willReturn($nextStepRoute);

        $stepProcess = $this->createStepProcess([$stepMock, $nextStepMock], $quoteTransfer);

        $request = $this->createRequest();
        $request->attributes->set('_route', $stepRoute);
        $response = $stepProcess->process($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals($nextStepRoute, $response->getTargetUrl());
        $this->assertCount(1, $quoteTransfer->getItems());
    }

    /**
     * @return void
     */
    public function testProcessWhenFormUsedAndValidFormSubmittedShouldCallExecuteWithInput()
    {
        $stepRoute = 'test';
        $nextStepRoute = 'next_step_route';
        $quoteTransfer = new QuoteTransfer();

        $stepMock = $this->createStepMock();
        $stepMock->expects($this->any())->method('preCondition')->willReturn(true);
        $stepMock->expects($this->exactly(2))->method('postCondition')->willReturn(true);
        $stepMock->expects($this->exactly(4))->method('getStepRoute')->willReturn($stepRoute);
        $stepMock->expects($this->any())->method('requireInput')->willReturn(true);
        $stepMock->expects($this->any())->method('execute')->willReturn($quoteTransfer);

        $nextStepMock = $this->createStepMock();
        $nextStepMock->expects($this->exactly(1))->method('getStepRoute')->willReturn($nextStepRoute);

        $formMock = $this->createFormMock();
        $formMock->expects($this->any())->method('isValid')->willReturn(true);
        $formMock->expects($this->any())->method('getData')->willReturn($quoteTransfer);

        $formCollectionHandlerMock = $this->getFormCollectionHandlerMock();
        $formCollectionHandlerMock->expects($this->any())->method('hasSubmittedForm')->willReturn(true);
        $formCollectionHandlerMock->expects($this->any())->method('handleRequest')->willReturn($formMock);

        $stepProcess = $this->createStepProcess([$stepMock, $nextStepMock], $quoteTransfer);

        $request = $this->createRequest();
        $request->attributes->set('_route', $stepRoute);
        $response = $stepProcess->process($request, $formCollectionHandlerMock);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals($nextStepRoute, $response->getTargetUrl());
    }

    /**
     * @return void
     */
    public function testProcessWhenFormUsedButNotSubmittedShouldSetDefaultDataToForm()
    {
        $stepRoute = 'test';
        $quoteTransfer = new QuoteTransfer();

        $stepMock = $this->createStepMock();
        $stepMock->expects($this->any())->method('preCondition')->willReturn(true);
        $stepMock->expects($this->exactly(1))->method('postCondition')->willReturn(true);
        $stepMock->expects($this->exactly(2))->method('getStepRoute')->willReturn($stepRoute);
        $stepMock->expects($this->any())->method('requireInput')->willReturn(true);

        $formCollectionHandlerMock = $this->getFormCollectionHandlerMock();
        $formCollectionHandlerMock->expects($this->any())->method('hasSubmittedForm')->willReturn(false);
        $formCollectionHandlerMock->expects($this->any())->method('provideDefaultFormData');
        $formCollectionHandlerMock->expects($this->any())->method('getForms')->willReturn([]);

        $stepProcess = $this->createStepProcess([$stepMock], $quoteTransfer);

        $request = $this->createRequest();
        $request->attributes->set('_route', $stepRoute);
        $response = $stepProcess->process($request, $formCollectionHandlerMock);

        $this->assertArrayHasKey('previousStepUrl', $response);
        $this->assertArrayHasKey('quoteTransfer', $response);
    }

    /**
     * @return void
     */
    public function testProcessWhenFormUsedAndSubmittedAndInvalidShouldRenderView()
    {
        $stepRoute = 'test';
        $quoteTransfer = new QuoteTransfer();

        $stepMock = $this->createStepMock();
        $stepMock->expects($this->any())->method('preCondition')->willReturn(true);
        $stepMock->expects($this->exactly(1))->method('postCondition')->willReturn(true);
        $stepMock->expects($this->exactly(2))->method('getStepRoute')->willReturn($stepRoute);
        $stepMock->expects($this->any())->method('requireInput')->willReturn(true);

        $formMock = $this->createFormMock();
        $formMock->expects($this->any())->method('isValid')->willReturn(false);

        $formCollectionHandlerMock = $this->getFormCollectionHandlerMock();
        $formCollectionHandlerMock->expects($this->any())->method('hasSubmittedForm')->willReturn(true);
        $formCollectionHandlerMock->expects($this->any())->method('getForms')->willReturn([]);

        $formCollectionHandlerMock->expects($this->any())->method('handleRequest')->willReturn($formMock);

        $stepProcess = $this->createStepProcess([$stepMock], $quoteTransfer);

        $request = $this->createRequest();
        $request->attributes->set('_route', $stepRoute);
        $response = $stepProcess->process($request, $formCollectionHandlerMock);

        $this->assertArrayHasKey('previousStepUrl', $response);
        $this->assertArrayHasKey('quoteTransfer', $response);
    }

    /**
     * @param array $steps
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Yves\StepEngine\Process\StepProcess
     */
    protected function createStepProcess(array $steps, QuoteTransfer $quoteTransfer)
    {
        return new StepProcess(
            $steps,
            $this->createUrlGeneratorMock(),
            $this->createCartClientMock($quoteTransfer),
            'error_route'
        );
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Yves\StepEngine\Process\Steps\StepInterface
     */
    protected function createStepMock()
    {
        $stepMock = $this->getMock(StepInterface::class);
        $stepMock->method('getTemplateVariables')->willReturn([]);

        return $stepMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Symfony\Component\Routing\Generator\UrlGeneratorInterface
     */
    protected function createUrlGeneratorMock()
    {
        $urlGeneratorMock = $this->getMock(UrlGeneratorInterface::class);

        $urlGeneratorMock->method('generate')->willReturnCallback(
            function ($escapeRoute) {
                return $escapeRoute;
            }
        );

        return $urlGeneratorMock;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Client\Cart\CartClientInterface
     */
    protected function createCartClientMock(QuoteTransfer $quoteTransfer)
    {
        $cartClientMock = $this->getMock(CartClientInterface::class);
        $cartClientMock->method('getQuote')->willReturn($quoteTransfer);

        return $cartClientMock;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function createRequest()
    {
        return Request::createFromGlobals();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Symfony\Component\Form\FormInterface
     */
    protected function createFormMock()
    {
        return $this->getMock(FormInterface::class);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Yves\StepEngine\Form\FormCollectionHandlerInterface
     */
    protected function getFormCollectionHandlerMock()
    {
        return $this->getMock(FormCollectionHandlerInterface::class);
    }

}
