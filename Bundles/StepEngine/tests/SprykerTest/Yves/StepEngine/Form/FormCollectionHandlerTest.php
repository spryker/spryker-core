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
    public function testGetInstance(): void
    {
        $formCollectionHandler = new FormCollectionHandler([], $this->getFormFactoryMock(), $this->getDataProviderMock());

        $this->assertInstanceOf(FormCollectionHandlerInterface::class, $formCollectionHandler);
    }

    /**
     * @return void
     */
    public function testGetForms(): void
    {
        $formCollectionHandler = new FormCollectionHandler([], $this->getFormFactoryMock(), $this->getDataProviderMock());

        $this->assertIsArray($formCollectionHandler->getForms($this->getDataTransferMock()));
    }

    /**
     * @return void
     */
    public function testGetFormsInvokesFormFactory(): void
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
    public function testGetFormsInvokesFormFactoryAndDataProvider(): void
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
    public function testHasSubmittedFormsReturnTrue(): void
    {
        $formCollectionHandlerMock = $this->getFormCollectionHandlerMock(['getForms']);
        $formCollectionHandlerMock->method('getForms')->willReturn($this->getForms());

        $request = new Request([], ['formA' => []]);
        $this->assertTrue($formCollectionHandlerMock->hasSubmittedForm($request, $this->getDataTransferMock()));
    }

    /**
     * @return void
     */
    public function testHasSubmittedFormsReturnFalse(): void
    {
        $formCollectionHandler = new FormCollectionHandler([], $this->getFormFactoryMock(), $this->getDataProviderMock());

        $this->assertFalse($formCollectionHandler->hasSubmittedForm(Request::createFromGlobals(), $this->getDataTransferMock()));
    }

    /**
     * @return void
     */
    public function testHandleRequestThrowsException(): void
    {
        $formCollectionHandler = new FormCollectionHandler([], $this->getFormFactoryMock(), $this->getDataProviderMock());

        $this->expectException(InvalidFormHandleRequest::class);

        $formCollectionHandler->handleRequest(Request::createFromGlobals(), $this->getDataTransferMock());
    }

    /**
     * @return void
     */
    public function testHandleRequest(): void
    {
        $formCollectionHandlerMock = $this->getFormCollectionHandlerMock(['getForms']);

        $formMock = $this->getMockForAbstractClass(FormInterface::class, [], '', false, false, false, ['getName', 'handleRequest']);
        $formMock->method('getName')->willReturn('formA');
        $formMock->expects($this->once())->method('handleRequest')->willReturnSelf();
        $formMocks = [
            $formMock,
        ];

        $formCollectionHandlerMock->method('getForms')->willReturn($formMocks);

        $request = new Request([], ['formA' => []]);
        $this->assertInstanceOf(FormInterface::class, $formCollectionHandlerMock->handleRequest($request, $this->getDataTransferMock()));
    }

    /**
     * @return void
     */
    public function testProvideDefaultFormData(): void
    {
        $formCollectionHandlerMock = $this->getFormCollectionHandlerMock(['getForms']);

        $formMock = $this->getMockForAbstractClass(FormInterface::class, [], '', false, false, false, ['setData']);
        $formMock->expects($this->once())->method('setData');
        $formMocks = [
            $formMock,
        ];

        $formCollectionHandlerMock->method('getForms')->willReturn($formMocks);

        $formCollectionHandlerMock->provideDefaultFormData($this->getDataTransferMock());
    }

    /**
     * @return void
     */
    public function testProvideDefaultFormDataInvokesDataProvider(): void
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
    public function testProvideDefaultFormWithoutDataProvider(): void
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
    private function getForms(): array
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
    private function getFormCollectionHandlerMock(array $formCollectionHandlerMethods = [], array $arguments = []): FormCollectionHandlerInterface
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
    private function getFormFactoryMock(array $formFactoryMethods = []): FormFactoryInterface
    {
        return $this->getMockForAbstractClass(FormFactoryInterface::class, [], '', false, false, true, $formFactoryMethods);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    private function getDataProviderMock(): StepEngineFormDataProviderInterface
    {
        $dataProviderMock = $this->getMockBuilder(StepEngineFormDataProviderInterface::class)->getMock();
        $dataProviderMock->method('getData')->willReturnArgument(0);

        return $dataProviderMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    private function getDataTransferMock(): AbstractTransfer
    {
        $dataTransferMock = $this->getMockBuilder(AbstractTransfer::class);

        return $dataTransferMock->getMock();
    }
}
