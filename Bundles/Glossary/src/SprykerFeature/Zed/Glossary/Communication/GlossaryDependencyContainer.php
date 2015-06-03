<?php

/*
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerFeature\Zed\Glossary\Communication;

use Generated\Zed\Ide\FactoryAutoCompletion\GlossaryCommunication;
use SprykerEngine\Zed\Kernel\Communication\AbstractDependencyContainer;
use SprykerFeature\Zed\Glossary\Communication\Form\TranslationForm;
use SprykerFeature\Zed\Glossary\Communication\Grid\TranslationGrid;
use SprykerFeature\Zed\Glossary\Dependency\Facade\GlossaryToLocaleInterface;
use SprykerFeature\Zed\Glossary\GlossaryDependencyProvider;
use SprykerFeature\Zed\Glossary\Persistence\GlossaryQueryContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator;

/**
 * @method GlossaryCommunication getFactory()
 * @method GlossaryQueryContainerInterface getQueryContainer()
 */
class GlossaryDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @return GlossaryFacade
     */
    public function createGlossaryFacade()
    {
        return $this->getLocator()->glossary()->facade();
    }

    /**
     * @param Request $request
     *
     * @return TranslationForm
     */
    public function createTranslationForm(Request $request)
    {
        return $this->getFactory()->createFormTranslationForm(
            $request,
            $this->createQueryContainer(),
            $this->createLocaleFacade()
        );
    }

    /**
     * @param Request $request
     *
     * @return TranslationForm
     */
    public function createKeyForm(Request $request)
    {
        return $this->getFactory()->createFormKeyForm(
            $request,
            $this->createQueryContainer(),
            $this->createLocaleFacade()
        );
    }

    /**
     * @param Request $request
     *
     * @return TranslationGrid
     */
    public function createGlossaryKeyTranslationGrid(Request $request)
    {
        $glossaryQueryContainer = $this->getQueryContainer();

        $availableLocales = $this->createEnabledLocales();

        $translationQuery = $glossaryQueryContainer->queryKeysAndTranslationsForEachLanguage(
            array_keys($availableLocales)
        );

        return $this->getFactory()->createGridTranslationGrid(
            $translationQuery,
            $request,
            $availableLocales
        );
    }

    /**
     * @return array
     */
    public function createEnabledLocales()
    {
        return $this->getLocaleFacade()->getAvailableLocales();
    }

    /**
     * @return Validator
     */
    public function createValidator()
    {
        return $this->getExternalDependency(GlossaryDependencyProvider::PLUGIN_VALIDATOR);
    }

    /**
     * @return GlossaryToLocaleInterface
     */
    protected function getLocaleFacade()
    {
        return $this->getExternalDependency(GlossaryDependencyProvider::LOCALE_FACADE);
    }
}
