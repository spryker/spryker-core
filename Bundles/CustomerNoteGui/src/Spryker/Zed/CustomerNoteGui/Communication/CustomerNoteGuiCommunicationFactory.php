<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerNoteGui\Communication;

use Spryker\Zed\CustomerNoteGui\Communication\Form\DataProvider\NoteFormDataProvider;
use Spryker\Zed\CustomerNoteGui\Communication\Form\NoteForm;
use Spryker\Zed\CustomerNoteGui\Communication\Model\NoteWriter;
use Spryker\Zed\CustomerNoteGui\Communication\Model\NoteWriterInterface;
use Spryker\Zed\CustomerNoteGui\CustomerNoteGuiDependencyProvider;
use Spryker\Zed\CustomerNoteGui\Dependency\Facade\CustomerNoteGuiToCustomerNoteFacadeInterface;
use Spryker\Zed\CustomerNoteGui\Dependency\Facade\CustomerNoteGuiToUserFacadeInterface;
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
     * @param array|null $formData
     * @param array $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getNoteForm($formData = null, array $formOptions = []): FormInterface
    {
        return $this->getFormFactory()->create(NoteForm::class, $formData, $formOptions);
    }

    /**
     * @return \Spryker\Zed\CustomerNoteGui\Dependency\Facade\CustomerNoteGuiToCustomerNoteFacadeInterface
     */
    public function getCustomerNoteFacade(): CustomerNoteGuiToCustomerNoteFacadeInterface
    {
        return $this->getProvidedDependency(CustomerNoteGuiDependencyProvider::FACADE_CUSTOMER_NOTE);
    }

    /**
     * @return \Spryker\Zed\CustomerNoteGui\Dependency\Facade\CustomerNoteGuiToUserFacadeInterface
     */
    protected function getUserFacade(): CustomerNoteGuiToUserFacadeInterface
    {
        return $this->getProvidedDependency(CustomerNoteGuiDependencyProvider::FACADE_USER);
    }

    /**
     * @return \Spryker\Zed\CustomerNoteGui\Communication\Model\NoteWriterInterface
     */
    public function createNoteWriter(): NoteWriterInterface
    {
        return new NoteWriter(
            $this->getUserFacade(),
            $this->getCustomerNoteFacade()
        );
    }
}
