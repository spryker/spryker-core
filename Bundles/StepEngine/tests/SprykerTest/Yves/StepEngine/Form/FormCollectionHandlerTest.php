<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\StepEngine\Form;

use Codeception\Test\Unit;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;
use Spryker\Yves\StepEngine\Exception\InvalidFormHandleRequest;
use Spryker\Yves\StepEngine\Form\FormCollectionHandler;
use Spryker\Yves\StepEngine\Form\FormCollectionHandlerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group StepEngine
 * @group Form
 * @group FormCollectionHandlerTest
 * Add your own group annotations below this line
 */
class FormCollectionHandlerTest extends Unit
{
    /**
     * @return void
     */
    public function testGetInstance()
    {
        $formCollectionHandler = new FormCollectionHandler([], $this->getFormFactoryMock(), $this->getDataProviderMock());

        $this->assertInstanceOf(FormCollectionHandlerInterface::class, $formCollectionHandler);
    }

    /**
     * @return void
     */
    public function testGetForms()
    {
        $formCollectionHandler = new FormCollectionHandler([], $this->getFormFactoryMock(), $this->getDataProviderMock());

        $this->assertIsArray($formCollectionHandler->getForms($this->getDataTransferMock()));
    }

    /**
     * @return void
     */
    public function testGetFormsInvokesFormFactory()
    {
        $formFactoryMock = $this->getFormFactoryMock(['create']);
        $formFactoryMock->expects($this->once())->method('create');

        $formTypeMock = $this->getMockForAbstractClass(FormTypeInterface::class);

        $dataProviderMock = $this->getDataProviderMock();
        $dataProviderMock->expects($this->once())->method('getOptions')->willReturn([]);
        $formCollectionHandler = new FormCollectionHandler([$formTypeMock], $formFactoryMock, $dataProviderMock);

        $formCollectionHandler->getForms($this->getDataTransferMock());
    }

    /**
     * @return void
     */
    public function testGetFormsInvokesFormFactoryAndDataProvider()
    {
        $formFactoryMock = $this->getFormFactoryMock(['create']);
        $formFactoryMock->expects($this->once())->method('create');

        $formTypeMock = $this->getMockForAbstractClass(FormTypeInterface::class);

        $dataProviderMock = $this->getDataProviderMock();
        $dataProviderMock->expects($this->once())->method('getOptions')->willReturn([]);
        $formCollectionHandler = new FormCollectionHandler([$formTypeMock], $formFactoryMock, $dataProviderMock);

        $formCollectionHandler->getForms($this->getDataTransferMock());
    }

    /**
     * @return void
     */
    public function testHasSubmittedFormsReturnTrue()
    {
        $formCollectionHandlerMock = $this->getFormCollectionHandlerMock(['getForms']);
        $formCollectionHandlerMock->method('getForms')->willReturn($this->getForms());

        $request = new Request([], ['formA' => []]);
        $this->assertTrue($formCollectionHandlerMock->hasSubmittedForm($request, $this->getDataTransferMock()));
    }

    /**
     * @return void
     */
    public function testHasSubmittedFormsReturnFalse()
    {
        $formCollectionHandler = new FormCollectionHandler([], $this->getFormFactoryMock(), $this->getDataProviderMock());

        $this->assertFalse($formCollectionHandler->hasSubmittedForm(Request::createFromGlobals(), $this->getDataTransferMock()));
    }

    /**
     * @return void
     */
    public function testHandleRequestThrowsException()
    {
        $formCollectionHandler = new FormCollectionHandler([], $this->getFormFactoryMock(), $this->getDataProviderMock());

        $this->expectException(InvalidFormHandleRequest::class);

        $formCollectionHandler->handleRequest(Request::createFromGlobals(), $this->getDataTransferMock());
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
        $this->assertInstanceOf(FormInterface::class, $formCollectionHandlerMock->handleRequest($request, $this->getDataTransferMock()));
    }

    /**
     * @return void
     */
    public function testProvideDefaultFormData()
    {
        $formCollectionHandlerMock = $this->getFormCollectionHandlerMock(['getForms']);

        $formMock = $this->getMockForAbstractClass(FormInterface::class, [], '', false, false, false, ['setData']);
        $formMock->expects($this->once())->method('setData');
        $formMocks[] = $formMock;

        $formCollectionHandlerMock->method('getForms')->willReturn($formMocks);

        $formCollectionHandlerMock->provideDefaultFormData($this->getDataTransferMock());
    }

    /**
     * @return void
     */
    public function testProvideDefaultFormDataInvokesDataProvider()
    {
        $dataProviderMock = $this->getDataProviderMock();
        $dataProviderMock->expects($this->once())->method('getOptions')->willReturn([]);

        $formMock = $this->getMockForAbstractClass(FormInterface::class);
        $formFactoryMock = $this->getFormFactoryMock(['create']);
        $formFactoryMock->method('create')->willReturn($formMock);

        $formTypeMock = $this->getMockForAbstractClass(FormTypeInterface::class);
        $formCollectionHandler = new FormCollectionHandler([$formTypeMock], $formFactoryMock, $dataProviderMock);
        $formCollectionHandler->provideDefaultFormData($this->getDataTransferMock());
    }

    /**
     * @return void
     */
    public function testProvideDefaultFormWithoutDataProvider()
    {
        $formMock = $this->getMockForAbstractClass(FormInterface::class);
        $formFactoryMock = $this->getFormFactoryMock(['create']);
        $formFactoryMock->method('create')->willReturn($formMock);

        $formTypeMock = $this->getMockForAbstractClass(FormTypeInterface::class);
        $formCollectionHandler = new FormCollectionHandler([$formTypeMock], $formFactoryMock);
        $formCollectionHandler->provideDefaultFormData($this->getDataTransferMock());
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
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Yves\StepEngine\Form\FormCollectionHandlerInterface
     */
    private function getFormCollectionHandlerMock(array $formCollectionHandlerMethods = [], array $arguments = [])
    {
        if (empty($arguments)) {
            $arguments = [[], $this->getFormFactoryMock(), $this->getDataProviderMock()];
        }

        $formCollectionHandlerMock = $this->getMockBuilder(FormCollectionHandler::class)->setMethods($formCollectionHandlerMethods)->setConstructorArgs($arguments)->getMock();

        return $formCollectionHandlerMock;
    }

    /**
     * @param array $formFactoryMethods
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Form\FormFactoryInterface
     */
    private function getFormFactoryMock(array $formFactoryMethods = [])
    {
        return $this->getMockForAbstractClass(FormFactoryInterface::class, [], '', false, false, true, $formFactoryMethods);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    private function getDataProviderMock()
    {
        $dataProviderMock = $this->getMockBuilder(StepEngineFormDataProviderInterface::class)->getMock();
        $dataProviderMock->method('getData')->willReturnArgument(0);

        return $dataProviderMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    private function getDataTransferMock()
    {
        $dataTransferMock = $this->getMockBuilder(AbstractTransfer::class);

        return $dataTransferMock->getMock();
    }
}
