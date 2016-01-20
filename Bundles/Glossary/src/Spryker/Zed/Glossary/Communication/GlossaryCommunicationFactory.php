<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Glossary\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Glossary\Communication\Form\TranslationForm;
use Spryker\Zed\Glossary\Communication\Table\TranslationTable;
use Spryker\Zed\Glossary\Dependency\Facade\GlossaryToLocaleInterface;
use Spryker\Zed\Glossary\GlossaryDependencyProvider;
use Spryker\Zed\Glossary\Persistence\GlossaryQueryContainerInterface;
use Spryker\Zed\Glossary\GlossaryConfig;

/**
 * @method GlossaryQueryContainerInterface getQueryContainer()
 * @method GlossaryConfig getConfig()
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
     * @deprecated, Use getEnabledLocales() instead.
     *
     * @return array
     */
    public function createEnabledLocales()
    {
        trigger_error('Deprecated, use getEnabledLocales() instead.', E_USER_DEPRECATED);

        return $this->getEnabledLocales();
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
     * @deprecated, Use getQueryContainer() instead.
     *
     * @return GlossaryQueryContainerInterface
     */
    public function createQueryContainer()
    {
        trigger_error('Deprecated, use getQueryContainer() instead.', E_USER_DEPRECATED);

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

        $form = new TranslationForm($translationQuery, $glossaryKeyQuery, $locales, $type);

        return $this->createForm($form);
    }

}
