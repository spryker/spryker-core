<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Yves\StepEngine\Form;

use Generated\Shared\Transfer\QuoteTransfer;
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
     * @var \Generated\Shared\Transfer\QuoteTransfer
     */
    protected $quoteTransfer;

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
     * @param \Symfony\Component\Form\FormFactoryInterface $formFactory
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array $formTypes
     * @param \Spryker\Yves\StepEngine\Dependency\DataProvider\DataProviderInterface|null $dataProvider
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        QuoteTransfer $quoteTransfer,
        array $formTypes = [],
        DataProviderInterface $dataProvider = null
    ) {
        $this->formFactory = $formFactory;
        $this->quoteTransfer = clone $quoteTransfer;
        $this->formTypes = $formTypes;
        $this->dataProvider = $dataProvider;
    }

    /**
     * @return \Symfony\Component\Form\FormInterface[]
     */
    public function getForms()
    {
        if (empty($this->forms)) {
            $this->forms = $this->createForms($this->formTypes, $this->quoteTransfer);
        }

        return $this->forms;
    }

    /**
     * @param \Symfony\Component\Form\FormTypeInterface[] $formTypes
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    protected function createForms(array $formTypes, QuoteTransfer $quoteTransfer)
    {
        $forms = [];
        foreach ($formTypes as $formType) {
            $forms[] = $this->createForm($formType, $quoteTransfer);
        }

        return $forms;
    }

    /**
     * @param \Symfony\Component\Form\FormTypeInterface $formType
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function createForm(FormTypeInterface $formType, QuoteTransfer $quoteTransfer)
    {
        $formOptions = [
            'data_class' => QuoteTransfer::class
        ];

        if ($this->dataProvider !== null) {
            $formOptions = array_merge($formOptions, $this->dataProvider->getOptions($quoteTransfer));
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
        foreach ($this->getForms() as $form) {
            if ($request->request->has($form->getName())) {
                $form->setData($this->quoteTransfer);

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
    public function provideDefaultFormData(QuoteTransfer $quoteTransfer)
    {
        $formData = $this->getFormData($quoteTransfer);

        foreach ($this->getForms() as $form) {
            $form->setData($formData);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function getFormData(QuoteTransfer $quoteTransfer)
    {
        if ($this->dataProvider !== null) {
            return $this->dataProvider->getData($quoteTransfer);
        }

        return $quoteTransfer;
    }

}
