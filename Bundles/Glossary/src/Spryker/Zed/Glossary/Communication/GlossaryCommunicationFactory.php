<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Glossary\Communication;

use Spryker\Zed\Glossary\Communication\Form\DataProvider\TranslationFormDataProvider;
use Spryker\Zed\Glossary\Communication\Form\UpdateTranslationForm;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Glossary\Communication\Form\TranslationForm;
use Spryker\Zed\Glossary\Communication\Table\TranslationTable;
use Spryker\Zed\Glossary\GlossaryDependencyProvider;

/**
 * @method \Spryker\Zed\Glossary\Persistence\GlossaryQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Glossary\GlossaryConfig getConfig()
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
        $translationQuery = $this->getQueryContainer()
            ->queryTranslations();

        $subQuery = $this->getQueryContainer()
            ->queryTranslations();

        return new TranslationTable($translationQuery, $subQuery, $locales);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createTranslationAddForm()
    {
        return $this->getFormFactory()->create(new TranslationForm(), null, [
            TranslationForm::OPTION_LOCALES => $this->getEnabledLocales(),
        ]);
    }

    /**
     * @param array $formData
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createTranslationUpdateForm(array $formData)
    {
        return $this->getFormFactory()->create(new UpdateTranslationForm(), $formData, [
            UpdateTranslationForm::OPTION_LOCALES => $this->getEnabledLocales(),
        ]);
    }

    /**
     * @return \Spryker\Zed\Glossary\Communication\Form\DataProvider\TranslationFormDataProvider
     */
    public function createTranslationDataProvider()
    {
        return new TranslationFormDataProvider($this->getQueryContainer());
    }

}
