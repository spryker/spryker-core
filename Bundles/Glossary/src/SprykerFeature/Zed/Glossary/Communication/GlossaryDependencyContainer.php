<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Glossary\Communication;

use Generated\Zed\Ide\FactoryAutoCompletion\GlossaryCommunication;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
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
class GlossaryDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return GlossaryFacade
     */
    public function createGlossaryFacade()
    {
        return $this->getLocator()->glossary()->facade();
    }

    /**
     * @return TranslationForm
     */
    public function createTranslationForm(Request $request)
    {
        return $this->getFactory()->createFormKeyForm(
            $request,
            $this->createQueryContainer(),
            $this->createLocaleFacade()
        );
    }

    /**
     * @return GlossaryQueryContainerInterface
     */
    public function createQueryContainer()
    {
        return $this->getQueryContainer();
    }

    /**
     * @return LocaleFacade
     */
    public function createLocaleFacade()
    {
        return $this->getProvidedDependency(GlossaryDependencyProvider::FACADE_LOCALE);
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
            $this->createLocaleFacade(),
            null, // TODO remove from all form instantiations
            $this->getQueryContainer(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @return TranslationGrid
     */
    public function createGlossaryKeyTranslationGrid()
    {
        $glossaryQueryContainer = $this->getQueryContainer();

        $availableLocales = $this->createEnabledLocales();

        $translationQuery = $glossaryQueryContainer->queryKeysAndTranslationsForEachLanguage(
            array_keys($availableLocales)
        );

        return $this->getFactory()->createGridTranslationGrid(
            $translationQuery,
            null,
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
        return $this->getProvidedDependency(GlossaryDependencyProvider::PLUGIN_VALIDATOR);
    }

    /**
     * @return GlossaryToLocaleInterface
     */
    protected function getLocaleFacade()
    {
        return $this->getProvidedDependency(GlossaryDependencyProvider::FACADE_LOCALE);
    }

}
