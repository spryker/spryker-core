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
    public function getTranslationForm(Request $request)
    {
        return $this->getFactory()->createFormTranslationForm(
            $request,
            $this->getQueryContainer(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @param Request $request
     *
     * @return TranslationGrid
     */
    public function getGlossaryKeyTranslationGrid(Request $request)
    {
        $glossaryQueryContainer = $this->getQueryContainer();
        $translationQuery = $glossaryQueryContainer->joinTranslationQueryWithKeysAndLocales($glossaryQueryContainer->queryTranslations());
        return $this->getFactory()->createGridTranslationGrid(
            $translationQuery,
            $request
        );
    }

    /**
     * @return GlossaryQueryContainerInterface
     */
    public function getQueryContainer()
    {
        return $this->getLocator()->glossary()->queryContainer();
    }

    /**
     * @return Validator
     */
    public function getValidator()
    {
        return $this->getLocator()->application()->pluginPimple()->getApplication()['validator'];
    }

    /**
     * @return LocaleFacade
     */
    protected function getLocaleFacade()
    {
        return $this->getLocator()->locale()->facade();
    }
}
