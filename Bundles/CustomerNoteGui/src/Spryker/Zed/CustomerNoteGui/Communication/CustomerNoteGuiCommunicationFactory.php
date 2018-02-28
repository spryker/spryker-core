<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerNoteGui\Communication;

use Spryker\Zed\CustomerNoteGui\Communication\Form\DataProvider\NoteFormDataProvider;
use Spryker\Zed\CustomerNoteGui\Communication\Form\NoteForm;
use Spryker\Zed\CustomerNoteGui\Communication\Handler\NoteHandler;
use Spryker\Zed\CustomerNoteGui\CustomerNoteGuiDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

class CustomerNoteGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\CustomerNoteGui\Communication\Form\DataProvider\NoteFormDataProvider
     */
    public function createNoteFormDataProvider()
    {
        return new NoteFormDataProvider(
            $this->getCustomerNoteFacade()
        );
    }

    /**
     * @param array $formData
     * @param array $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getNoteForm(array $formData = [], array $formOptions = [])
    {
        return $this->getFormFactory()->create(NoteForm::class, $formData, $formOptions);
    }

    /**
     * @return \Spryker\Zed\CustomerNoteGui\Dependency\CustomerNoteGuiToCustomerNoteFacadeInterface
     */
    protected function getCustomerNoteFacade()
    {
        return $this->getProvidedDependency(CustomerNoteGuiDependencyProvider::FACADE_CUSTOMER_NOTE);
    }

    /**
     * @return \Spryker\Zed\CustomerNoteGui\Dependency\CustomerNoteGuiToUserFacadeInterface
     */
    protected function getUserFacade()
    {
        return $this->getProvidedDependency(CustomerNoteGuiDependencyProvider::FACADE_USER);
    }

    /**
     * @return \Spryker\Zed\CustomerNoteGui\Communication\Handler\NoteHandlerInterface
     */
    public function createNoteHandler()
    {
        return new NoteHandler(
            $this->getUserFacade(),
            $this->getCustomerNoteFacade()
        );
    }
}
