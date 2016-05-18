<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Yves\StepEngine\Form;

use Spryker\Shared\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Exception\InvalidFormHandleRequest;
use Spryker\Yves\StepEngine\Dependency\DataProvider\DataProviderInterface;
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
     * @var \Spryker\Yves\StepEngine\Dependency\DataProvider\DataProviderInterface
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
     * @param \Spryker\Yves\StepEngine\Dependency\DataProvider\DataProviderInterface|null $dataProvider
     */
    public function __construct(
        array $formTypes = [],
        FormFactoryInterface $formFactory,
        DataProviderInterface $dataProvider = null
    ) {
        $this->formFactory = $formFactory;
        $this->formTypes = $formTypes;
        $this->dataProvider = $dataProvider;
    }

    /**
     * @return \Symfony\Component\Form\FormInterface[]
     */
    public function getForms()
    {
        if (empty($this->forms)) {
            $this->forms = $this->createForms();
        }

        return $this->forms;
    }

    /**
     * @return array
     */
    protected function createForms()
    {
        $forms = [];
        foreach ($this->formTypes as $formType) {
            $forms[] = $this->createForm($formType);
        }

        return $forms;
    }

    /**
     * @param \Symfony\Component\Form\FormTypeInterface $formType
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function createForm(FormTypeInterface $formType)
    {
        $transfer = $this->getTransfer();
        $formOptions = [
            'data_class' => get_class($transfer)
        ];

        if ($this->dataProvider) {
            $formOptions = array_merge($formOptions, $this->dataProvider->getOptions($transfer));
        }

        return $this->formFactory->create($formType, null, $formOptions);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    public function hasSubmittedForm(Request $request)
    {
        foreach ($this->getForms() as $form) {
            if ($request->request->has($form->getName())) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Spryker\Yves\StepEngine\Exception\InvalidFormHandleRequest
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function handleRequest(Request $request)
    {
        $transfer = $this->getTransfer();

        foreach ($this->getForms() as $form) {
            if ($request->request->has($form->getName())) {
                $form->setData($transfer);

                return $form->handleRequest($request);
            }
        }

        throw new InvalidFormHandleRequest('Form to handle not found in Request.');
    }

    /**
     * @return void
     */
    public function provideDefaultFormData()
    {
        $transfer = $this->getTransfer();

        foreach ($this->getForms() as $form) {
            $form->setData($transfer);
        }
    }

    /**
     * @return \Spryker\Shared\Transfer\AbstractTransfer
     */
    protected function getTransfer()
    {
        return $this->dataProvider->getData();
    }
}
