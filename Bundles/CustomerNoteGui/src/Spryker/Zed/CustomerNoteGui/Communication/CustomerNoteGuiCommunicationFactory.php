<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerNoteGui\Communication;

use Spryker\Zed\CustomerNoteGui\Communication\Form\DataProvider\NoteFormDataProvider;
use Spryker\Zed\CustomerNoteGui\Communication\Form\NoteForm;
use Spryker\Zed\CustomerNoteGui\Communication\Handler\NoteHandler;
use Spryker\Zed\CustomerNoteGui\Communication\Handler\NoteHandlerInterface;
use Spryker\Zed\CustomerNoteGui\CustomerNoteGuiDependencyProvider;
use Spryker\Zed\CustomerNoteGui\Dependency\CustomerNoteGuiToCustomerNoteFacadeInterface;
use Spryker\Zed\CustomerNoteGui\Dependency\CustomerNoteGuiToUserFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Symfony\Component\Form\FormInterface;

class CustomerNoteGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\CustomerNoteGui\Communication\Form\DataProvider\NoteFormDataProvider
     */
    public function createNoteFormDataProvider(): NoteFormDataProvider
    {
        return new NoteFormDataProvider();
    }

    /**
     * @param array $formData
     * @param array $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getNoteForm(array $formData = [], array $formOptions = []): FormInterface
    {
        return $this->getFormFactory()->create(NoteForm::class, $formData, $formOptions);
    }

    /**
     * @return \Spryker\Zed\CustomerNoteGui\Dependency\CustomerNoteGuiToCustomerNoteFacadeInterface
     */
    public function getCustomerNoteFacade(): CustomerNoteGuiToCustomerNoteFacadeInterface
    {
        return $this->getProvidedDependency(CustomerNoteGuiDependencyProvider::FACADE_CUSTOMER_NOTE);
    }

    /**
     * @return \Spryker\Zed\CustomerNoteGui\Dependency\CustomerNoteGuiToUserFacadeInterface
     */
    protected function getUserFacade(): CustomerNoteGuiToUserFacadeInterface
    {
        return $this->getProvidedDependency(CustomerNoteGuiDependencyProvider::FACADE_USER);
    }

    /**
     * @return \Spryker\Zed\CustomerNoteGui\Communication\Handler\NoteHandlerInterface
     */
    public function createNoteHandler(): NoteHandlerInterface
    {
        return new NoteHandler(
            $this->getUserFacade(),
            $this->getCustomerNoteFacade()
        );
    }
}
