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
     * @var array<\Symfony\Component\Form\FormTypeInterface|string>
     */
    protected $formTypes;

    /**
     * @var \Symfony\Component\Form\FormFactoryInterface
     */
    protected $formFactory;

    /**
     * @var \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface|null
     */
    protected $dataProvider;

    /**
     * @var array<\Symfony\Component\Form\FormInterface>
     */
    protected $forms = [];

    /**
     * @param array<\Symfony\Component\Form\FormTypeInterface|string> $formTypes
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<\Symfony\Component\Form\FormInterface>
     */
    public function getForms(AbstractTransfer $quoteTransfer)
    {
        if (!$this->forms) {
            $this->forms = $this->createForms($quoteTransfer);
        }

        return $this->forms;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function hasSubmittedForm(Request $request, AbstractTransfer $quoteTransfer)
    {
        foreach ($this->getForms($quoteTransfer) as $form) {
            if ($request->request->has($form->getName())) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @throws \Spryker\Yves\StepEngine\Exception\InvalidFormHandleRequest
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function handleRequest(Request $request, AbstractTransfer $quoteTransfer)
    {
        foreach ($this->getForms($quoteTransfer) as $form) {
            if ($request->request->has($form->getName())) {
                $form->setData($quoteTransfer);

                return $form->handleRequest($request);
            }
        }

        throw new InvalidFormHandleRequest('Form to handle not found in Request.');
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function provideDefaultFormData(AbstractTransfer $quoteTransfer)
    {
        $quoteTransfer = $this->getFormData($quoteTransfer);

        foreach ($this->getForms($quoteTransfer) as $form) {
            $form->setData($quoteTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function getFormData(AbstractTransfer $quoteTransfer)
    {
        if ($this->dataProvider !== null) {
            return $this->dataProvider->getData($quoteTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<\Symfony\Component\Form\FormInterface>
     */
    protected function createForms(AbstractTransfer $quoteTransfer)
    {
        $forms = [];
        foreach ($this->formTypes as $formType) {
            $forms[] = $this->createForm($formType, $quoteTransfer);
        }

        return $forms;
    }

    /**
     * @param \Symfony\Component\Form\FormTypeInterface|string $formType
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function createForm($formType, AbstractTransfer $quoteTransfer)
    {
        $formOptions = [
            'data_class' => get_class($quoteTransfer),
        ];

        if ($this->dataProvider) {
            $formOptions = array_merge($formOptions, $this->dataProvider->getOptions($quoteTransfer));
        }

        if ($formType instanceof FormInterface) {
            return $formType;
        }

        return $this->formFactory->create(is_object($formType) ? get_class($formType) : $formType, null, $formOptions);
    }
}
