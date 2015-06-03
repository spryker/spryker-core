<?php

/*
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerFeature\Zed\Glossary\Communication;

use Generated\Zed\Ide\AutoCompletion;
use Generated\Zed\Ide\FactoryAutoCompletion\GlossaryCommunication;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractDependencyContainer;
use SprykerEngine\Zed\Locale\Business\LocaleFacade;
use SprykerFeature\Zed\Glossary\Business\GlossaryFacade;
use SprykerFeature\Zed\Glossary\Communication\Form\TranslationForm;
use SprykerFeature\Zed\Glossary\Communication\Grid\TranslationGrid;
use SprykerFeature\Zed\Glossary\Persistence\GlossaryQueryContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator;

/**
 * @method GlossaryCommunication getFactory()
 */
class GlossaryDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @return GlossaryFacade
     */
    public function getGlossaryFacade()
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
        $glossaryQueryContainer = $this->createQueryContainer();

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
        return $this->createLocaleFacade()->getAvailableLocales();
    }

    /**
     * @return GlossaryQueryContainerInterface
     */
    public function createQueryContainer()
    {
        return $this->getLocator()->glossary()->queryContainer();
    }

    /**
     * @return Validator
     */
    public function createValidator()
    {
        return $this->getLocator()->application()->pluginPimple()->getApplication()['validator'];
    }

    /**
     * @return LocaleFacade
     */
    protected function createLocaleFacade()
    {
        return $this->getLocator()->locale()->facade();
    }
}
