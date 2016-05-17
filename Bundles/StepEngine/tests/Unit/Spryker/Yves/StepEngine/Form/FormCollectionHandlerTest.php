<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Yves\StepEngine\Form;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Cart\CartClientInterface;
use Spryker\Yves\StepEngine\Dependency\DataProvider\DataProviderInterface;
use Spryker\Yves\StepEngine\Exception\InvalidFormHandleRequest;
use Spryker\Yves\StepEngine\Form\FormCollectionHandler;
use Spryker\Yves\StepEngine\Form\FormCollectionHandlerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @group Spryker
 * @group Yves
 * @group StepEngine
 * @group FormCollectionHandler
 */
class FormCollectionHandlerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testGetInstance()
    {
        $formCollectionHandler = new FormCollectionHandler([], $this->getFormFactoryMock(), $this->getCartClientMock());

        $this->assertInstanceOf(FormCollectionHandlerInterface::class, $formCollectionHandler);
    }

    /**
     * @return void
     */
    public function testGetForms()
    {
        $formCollectionHandler = new FormCollectionHandler([], $this->getFormFactoryMock(), $this->getCartClientMock());

        $this->assertInternalType('array', $formCollectionHandler->getForms());
    }

    /**
     * @return void
     */
    public function testGetFormsInvokesFormFactory()
    {
        $formFactoryMock = $this->getFormFactoryMock(['create']);
        $formFactoryMock->expects($this->once())->method('create');

        $formTypeMock = $this->getMockForAbstractClass(FormTypeInterface::class);
        $formCollectionHandler = new FormCollectionHandler([$formTypeMock], $formFactoryMock, $this->getCartClientMock());

        $formCollectionHandler->getForms();
    }

    /**
     * @return void
     */
    public function testGetFormsInvokesFormFactoryAndDataProvider()
    {
        $formFactoryMock = $this->getFormFactoryMock(['create']);
        $formFactoryMock->expects($this->once())->method('create');

        $formTypeMock = $this->getMockForAbstractClass(FormTypeInterface::class);

        $dataProviderMock = $this->getMock(DataProviderInterface::class, ['getData', 'getOptions']);
        $dataProviderMock->expects($this->once())->method('getOptions')->willReturn([]);
        $formCollectionHandler = new FormCollectionHandler([$formTypeMock], $formFactoryMock, $this->getCartClientMock(), $dataProviderMock);

        $formCollectionHandler->getForms();
    }

    /**
     * @return void
     */
    public function testHasSubmittedFormsReturnTrue()
    {
        $formCollectionHandlerMock = $this->getFormCollectionHandlerMock(['getForms']);
        $formCollectionHandlerMock->method('getForms')->willReturn($this->getForms());

        $request = new Request([], ['formA' => []]);
        $this->assertTrue($formCollectionHandlerMock->hasSubmittedForm($request));
    }

    /**
     * @return void
     */
    public function testHasSubmittedFormsReturnFalse()
    {
        $formCollectionHandler = new FormCollectionHandler([], $this->getFormFactoryMock(), $this->getCartClientMock());

        $this->assertFalse($formCollectionHandler->hasSubmittedForm(Request::createFromGlobals()));
    }

    /**
     * @return void
     */
    public function testHandleRequestThrowsException()
    {
        $formCollectionHandler = new FormCollectionHandler([], $this->getFormFactoryMock(), $this->getCartClientMock());

        $this->setExpectedException(InvalidFormHandleRequest::class);

        $formCollectionHandler->handleRequest(Request::createFromGlobals());
    }

    /**
     * @return void
     */
    public function testHandleRequest()
    {
        $formCollectionHandlerMock = $this->getFormCollectionHandlerMock(['getForms']);

        $formMock = $this->getMockForAbstractClass(FormInterface::class, [], '', false, false, false, ['getName', 'handleRequest']);
        $formMock->method('getName')->willReturn('formA');
        $formMock->expects($this->once())->method('handleRequest')->willReturnSelf();
        $formMocks[] = $formMock;

        $formCollectionHandlerMock->method('getForms')->willReturn($formMocks);

        $request = new Request([], ['formA' => []]);
        $this->assertInstanceOf(FormInterface::class, $formCollectionHandlerMock->handleRequest($request));
    }

    public function testProvideDefaultFormData()
    {
        $formCollectionHandlerMock = $this->getFormCollectionHandlerMock(['getForms']);

        $formMock = $this->getMockForAbstractClass(FormInterface::class, [], '', false, false, false, ['setData']);
        $formMock->expects($this->once())->method('setData');
        $formMocks[] = $formMock;

        $formCollectionHandlerMock->method('getForms')->willReturn($formMocks);

        $formCollectionHandlerMock->provideDefaultFormData();
    }

    /**
     * @return void
     */
    public function testProvideDefaultFormDataInvokesDataProvider()
    {
        $dataProviderMock = $this->getMock(DataProviderInterface::class, ['getData', 'getOptions']);
        $dataProviderMock->expects($this->once())->method('getData')->willReturn([]);
        $formCollectionHandler = new FormCollectionHandler([], $this->getFormFactoryMock(['create']), $this->getCartClientMock(), $dataProviderMock);

        $formCollectionHandler->provideDefaultFormData();
    }

    /**
     * @return array
     */
    private function getForms()
    {
        $formMocks = [];
        $formMockA = $this->getMockForAbstractClass(FormInterface::class, [], '', false, false, false, ['getName']);
        $formMockA->method('getName')->willReturn('formA');
        $formMocks[] = $formMockA;

        $formMockB = $this->getMockForAbstractClass(FormInterface::class, [], '', false, false, false, ['getName']);
        $formMockB->method('getName')->willReturn('formB');
        $formMocks[] = $formMockB;

        return $formMocks;
    }

    /**
     * @param array $formCollectionHandlerMethods
     * @param array $arguments
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Yves\StepEngine\Form\FormCollectionHandlerInterface
     */
    private function getFormCollectionHandlerMock(array $formCollectionHandlerMethods = [], array $arguments = [])
    {
        if (empty($arguments)) {
            $arguments = [[], $this->getFormFactoryMock(), $this->getCartClientMock()];
        }

        $formCollectionHandlerMock = $this->getMock(FormCollectionHandler::class, $formCollectionHandlerMethods, $arguments);

        return $formCollectionHandlerMock;
    }

    /**
     * @param array $formFactoryMethods
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Symfony\Component\Form\FormFactoryInterface
     */
    private function getFormFactoryMock(array $formFactoryMethods = [])
    {
        return $this->getMockForAbstractClass(FormFactoryInterface::class, [], '', false, false, true, $formFactoryMethods);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Client\Cart\CartClientInterface
     */
    private function getCartClientMock()
    {
        $cartClientMock = $this->getMock(CartClientInterface::class);
        $cartClientMock->method('getQuote')->willReturn(new QuoteTransfer());

        return $cartClientMock;
    }

}
