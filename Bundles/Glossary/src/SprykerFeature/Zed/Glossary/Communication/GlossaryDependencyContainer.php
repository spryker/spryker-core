<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Glossary\Communication;

use Generated\Zed\Ide\FactoryAutoCompletion\GlossaryCommunication;
use SprykerFeature\Zed\Glossary\Business\GlossaryFacade;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\Glossary\Communication\Form\TranslationForm;
use SprykerFeature\Zed\Glossary\Communication\Table\KeyTable;
use SprykerFeature\Zed\Glossary\Communication\Table\TranslationTable;
use SprykerFeature\Zed\Glossary\Dependency\Facade\GlossaryToLocaleInterface;
use SprykerFeature\Zed\Glossary\GlossaryDependencyProvider;
use SprykerFeature\Zed\Glossary\Persistence\GlossaryQueryContainerInterface;
use Symfony\Component\HttpFoundation\Request;

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
            ->facade()
        ;
    }

    /**
     * @return array
     */
    public function createEnabledLocales()
    {
        return $this->getLocaleFacade()
            ->getAvailableLocales()
        ;
    }

    /**
     * @return GlossaryQueryContainerInterface
     */
    public function createQueryContainer()
    {
        return $this->getLocator()
            ->glossary()
            ->queryContainer()
        ;
    }

    /**
     * @param string $type
     *
     * @return KeyForm
     */
    public function createKeyForm($type, $idGlossaryKey)
    {
        $keyQuery = $this->getQueryContainer()
            ->queryKeys()
        ;

        $subQuery = $this->getQueryContainer()
            ->queryKeys()
        ;

        return $this->getFactory()
            ->createFormKeyForm($keyQuery, $subQuery, $type, $idGlossaryKey)
        ;
    }

    /**
     * @param Request $request
     *
     * @return KeyTable
     */
    public function createKeyTable()
    {
        $keyQuery = $this->getQueryContainer()
            ->queryKeys()
        ;

        return $this->getFactory()
            ->createTableKeyTable($keyQuery)
        ;
    }

    /**
     * @param array $locales
     *
     * @return TranslationTable
     */
    public function createTranslationTable(array $locales)
    {
        $translationQuery = $this->getQueryContainer()
            ->queryTranslations()
        ;

        $subQuery = $this->getQueryContainer()
            ->queryTranslations()
        ;

        return $this->getFactory()
            ->createTableTranslationTable($translationQuery, $subQuery, $locales)
        ;
    }

    /**
     * @return TranslationForm
     */
    public function createTranslationForm(array $locales, $type)
    {
        $translationQuery = $this->getQueryContainer()
            ->queryTranslations()
        ;

        $glossaryKeyQuery = $this->getQueryContainer()
            ->queryKeys()
        ;

        return $this->getFactory()
            ->createFormTranslationForm($translationQuery, $glossaryKeyQuery, $locales, $type)
        ;
    }

}
