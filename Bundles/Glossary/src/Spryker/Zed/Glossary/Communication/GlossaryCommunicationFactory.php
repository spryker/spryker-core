<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Glossary\Communication;

use Spryker\Zed\Glossary\Business\GlossaryFacade;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Glossary\Communication\Form\TranslationForm;
use Spryker\Zed\Glossary\Communication\Table\TranslationTable;
use Spryker\Zed\Glossary\Dependency\Facade\GlossaryToLocaleInterface;
use Spryker\Zed\Glossary\GlossaryDependencyProvider;
use Spryker\Zed\Glossary\Persistence\GlossaryQueryContainerInterface;

/**
 * @method GlossaryQueryContainerInterface getQueryContainer()
 */
class GlossaryCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return GlossaryToLocaleInterface
     */
    protected function getLocaleFacade()
    {
        return $this->getProvidedDependency(GlossaryDependencyProvider::FACADE_LOCALE);
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
        return $this->getQueryContainer();
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
