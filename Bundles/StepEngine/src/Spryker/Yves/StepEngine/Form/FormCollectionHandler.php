<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\StepEngine\Form;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;
use Spryker\Yves\StepEngine\Exception\InvalidFormHandleRequest;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class FormCollectionHandler implements FormCollectionHandlerInterface
{
    /**
     * @var \Symfony\Component\Form\FormFactoryInterface
     */
    protected $formFactory;

    /**
     * @var \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    protected $dataProvider;

    /**
     * @var \Symfony\Component\Form\FormInterface[]
     */
    protected $forms = [];

    /**
     * @var mixed[]
     */
    protected $formTypes;

    /**
     * @param \Symfony\Component\Form\FormTypeInterface[] $formTypes
     * @param \Symfony\Component\Form\FormFactoryInterface $formFactory
     * @param \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface|null $dataProvider
     */
    public function __construct(
        array $formTypes,
        FormFactoryInterface $formFactory,
        ?StepEngineFormDataProviderInterface $dataProvider = null
    ) {
        $this->formTypes = $formTypes;
        $this->formFactory = $formFactory;
        $this->dataProvider = $dataProvider;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $dataTransfer
     *
     * @return \Symfony\Component\Form\FormInterface[]
     */
    public function getForms(AbstractTransfer $dataTransfer)
    {
        if (!$this->forms) {
            $this->forms = $this->createForms($dataTransfer);
        }

        return $this->forms;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $dataTransfer
     *
     * @return bool
     */
    public function hasSubmittedForm(Request $request, AbstractTransfer $dataTransfer)
    {
        foreach ($this->getForms($dataTransfer) as $form) {
            if ($request->request->has($form->getName())) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $dataTransfer
     *
     * @throws \Spryker\Yves\StepEngine\Exception\InvalidFormHandleRequest
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function handleRequest(Request $request, AbstractTransfer $dataTransfer)
    {
        foreach ($this->getForms($dataTransfer) as $form) {
            if ($request->request->has($form->getName())) {
                $form->setData($dataTransfer);

                return $form->handleRequest($request);
            }
        }

        throw new InvalidFormHandleRequest('Form to handle not found in Request.');
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $dataTransfer
     *
     * @return void
     */
    public function provideDefaultFormData(AbstractTransfer $dataTransfer)
    {
        $dataTransfer = $this->getFormData($dataTransfer);

        foreach ($this->getForms($dataTransfer) as $form) {
            $form->setData($dataTransfer);
        }
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $dataTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function getFormData(AbstractTransfer $dataTransfer)
    {
        if ($this->dataProvider !== null) {
            return $this->dataProvider->getData($dataTransfer);
        }

        return $dataTransfer;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $dataTransfer
     *
     * @return array
     */
    protected function createForms(AbstractTransfer $dataTransfer)
    {
        $forms = [];
        foreach ($this->formTypes as $formType) {
            $forms[] = $this->createForm($formType, $dataTransfer);
        }

        return $forms;
    }

    /**
     * @param \Symfony\Component\Form\FormTypeInterface|string $formType
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $dataTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function createForm($formType, AbstractTransfer $dataTransfer)
    {
        $formOptions = [
            'data_class' => get_class($dataTransfer),
        ];

        if ($this->dataProvider) {
            $formOptions = array_merge($formOptions, $this->dataProvider->getOptions($dataTransfer));
        }

        if ($formType instanceof FormInterface) {
            return $formType;
        }

        return $this->formFactory->create($formType, null, $formOptions);
    }
}
