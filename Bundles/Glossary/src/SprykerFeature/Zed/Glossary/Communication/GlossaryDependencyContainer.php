<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Glossary\Communication;

use Generated\Zed\Ide\FactoryAutoCompletion\GlossaryCommunication;
use SprykerFeature\Zed\Glossary\Business\GlossaryFacade;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\Glossary\Communication\Form\TranslationForm;
use SprykerFeature\Zed\Glossary\Communication\Table\TranslationTable;
use SprykerFeature\Zed\Glossary\Dependency\Facade\GlossaryToLocaleInterface;
use SprykerFeature\Zed\Glossary\GlossaryDependencyProvider;
use SprykerFeature\Zed\Glossary\Persistence\GlossaryQueryContainerInterface;

/**
 * @method GlossaryCommunication getFactory()
 * @method GlossaryQueryContainerInterface getQueryContainer()
 */
class GlossaryDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return GlossaryToLocaleInterface
     */
    protected function getLocaleFacade()
    {
        return $this->getProvidedDependency(GlossaryDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return GlossaryFacade
     */
    public function createGlossaryFacade()
    {
        return $this->getLocator()
            ->glossary()
            ->facade();
    }

    /**
     * @return array
     */
    public function createEnabledLocales()
    {
        return $this->getLocaleFacade()
            ->getAvailableLocales();
    }

    /**
     * @return GlossaryQueryContainerInterface
     */
    public function createQueryContainer()
    {
        return $this->getLocator()
            ->glossary()
            ->queryContainer();
    }

    /**
     * @param array $locales
     *
     * @return TranslationTable
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
     * @param array $locales
     * @param string $type
     *
     * @return TranslationForm
     */
    public function createTranslationForm(array $locales, $type)
    {
        $translationQuery = $this->getQueryContainer()
            ->queryTranslations();

        $glossaryKeyQuery = $this->getQueryContainer()
            ->queryKeys();

        return new TranslationForm($translationQuery, $glossaryKeyQuery, $locales, $type);
    }

}
