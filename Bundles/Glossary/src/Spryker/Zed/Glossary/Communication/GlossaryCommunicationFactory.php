<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Glossary\Communication;

use Spryker\Zed\Glossary\Communication\Form\DataProvider\TranslationFormDataProvider;
use Spryker\Zed\Glossary\Communication\Form\TranslationForm;
use Spryker\Zed\Glossary\Communication\Form\UpdateTranslationForm;
use Spryker\Zed\Glossary\Communication\Table\TranslationTable;
use Spryker\Zed\Glossary\GlossaryDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\Glossary\Persistence\GlossaryQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Glossary\GlossaryConfig getConfig()
 * @method \Spryker\Zed\Glossary\Business\GlossaryFacadeInterface getFacade()
 * @method \Spryker\Zed\Glossary\Persistence\GlossaryRepositoryInterface getRepository()
 */
class GlossaryCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\Glossary\Dependency\Facade\GlossaryToLocaleInterface
     */
    protected function getLocaleFacade()
    {
        return $this->getProvidedDependency(GlossaryDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return array
     */
    public function getEnabledLocales()
    {
        return $this->getLocaleFacade()
            ->getAvailableLocales();
    }

    /**
     * @param array $locales
     *
     * @return \Spryker\Zed\Glossary\Communication\Table\TranslationTable
     */
    public function createTranslationTable(array $locales)
    {
        $glossaryKeyQuery = $this->getQueryContainer()
            ->queryKeys();

        $subQuery = $this->getQueryContainer()
            ->queryTranslations();

        return new TranslationTable($glossaryKeyQuery, $subQuery, $locales);
    }

    /**
     * @deprecated Use `getTranslationAddForm()` instead.
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createTranslationAddForm()
    {
        return $this->getFormFactory()->create($this->createTranslationForm(), null, [
            TranslationForm::OPTION_LOCALES => $this->getEnabledLocales(),
        ]);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getTranslationAddForm()
    {
        return $this->createTranslationAddForm();
    }

    /**
     * @deprecated Use `getTranslationUpdateForm()` instead.
     *
     * @param array $formData
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createTranslationUpdateForm(array $formData)
    {
        return $this->getFormFactory()->create($this->createUpdateTranslationForm(), $formData, [
            UpdateTranslationForm::OPTION_LOCALES => $this->getEnabledLocales(),
        ]);
    }

    /**
     * @param array $formData
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getTranslationUpdateForm(array $formData)
    {
        return $this->createTranslationUpdateForm($formData);
    }

    /**
     * @return \Spryker\Zed\Glossary\Communication\Form\DataProvider\TranslationFormDataProvider
     */
    public function createTranslationDataProvider()
    {
        return new TranslationFormDataProvider($this->getQueryContainer());
    }

    /**
     * @deprecated Use the FQCN directly.
     *
     * @return string
     */
    protected function createTranslationForm()
    {
        return TranslationForm::class;
    }

    /**
     * @deprecated Use the FQCN directly.
     *
     * @return string
     */
    protected function createUpdateTranslationForm()
    {
        return UpdateTranslationForm::class;
    }
}
