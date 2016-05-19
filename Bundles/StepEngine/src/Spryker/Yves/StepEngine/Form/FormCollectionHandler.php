<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\StepEngine\Form;

use Spryker\Shared\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\Form\DataProviderInterface;
use Spryker\Yves\StepEngine\Exception\InvalidFormHandleRequest;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;

class FormCollectionHandler implements FormCollectionHandlerInterface
{

    /**
     * @var \Symfony\Component\Form\FormFactoryInterface
     */
    protected $formFactory;

    /**
     * @var \Spryker\Yves\StepEngine\Dependency\Form\DataProviderInterface
     */
    protected $dataProvider;

    /**
     * @var \Symfony\Component\Form\FormInterface[]
     */
    protected $forms = [];

    /**
     * @var \Symfony\Component\Form\FormTypeInterface[]
     */
    protected $formTypes;

    /**
     * @param array $formTypes
     * @param \Symfony\Component\Form\FormFactoryInterface $formFactory
     * @param \Spryker\Yves\StepEngine\Dependency\Form\DataProviderInterface|null $dataProvider
     */
    public function __construct(
        array $formTypes,
        FormFactoryInterface $formFactory,
        DataProviderInterface $dataProvider = null
    ) {
        $this->formFactory = $formFactory;
        $this->formTypes = $formTypes;
        $this->dataProvider = $dataProvider;
    }

    /**
     * @param \Spryker\Shared\Transfer\AbstractTransfer $dataTransfer
     *
     * @return array|\Symfony\Component\Form\FormInterface[]
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
     * @param \Spryker\Shared\Transfer\AbstractTransfer $dataTransfer
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
     * @param \Spryker\Shared\Transfer\AbstractTransfer $dataTransfer
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
     * @param \Spryker\Shared\Transfer\AbstractTransfer $dataTransfer
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
     * @param \Spryker\Shared\Transfer\AbstractTransfer $dataTransfer
     *
     * @return \Spryker\Shared\Transfer\AbstractTransfer
     */
    protected function getFormData(AbstractTransfer $dataTransfer)
    {
        if ($this->dataProvider !== null) {
            return $this->dataProvider->getData($dataTransfer);
        }

        return $dataTransfer;
    }

    /**
     * @param \Spryker\Shared\Transfer\AbstractTransfer $dataTransfer
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
     * @param \Symfony\Component\Form\FormTypeInterface $formType
     * @param \Spryker\Shared\Transfer\AbstractTransfer $dataTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function createForm(FormTypeInterface $formType, AbstractTransfer $dataTransfer)
    {
        $formOptions = [
            'data_class' => get_class($dataTransfer)
        ];

        if ($this->dataProvider) {
            $formOptions = array_merge($formOptions, $this->dataProvider->getOptions($dataTransfer));
        }

        return $this->formFactory->create($formType, null, $formOptions);
    }

}
