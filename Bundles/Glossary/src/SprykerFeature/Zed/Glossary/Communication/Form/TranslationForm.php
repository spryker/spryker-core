<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Glossary\Communication\Form;

use SprykerEngine\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;
use SprykerFeature\Zed\Glossary\Dependency\Facade\GlossaryToLocaleInterface;
use SprykerFeature\Zed\Ui\Dependency\Form\AbstractForm;
use Symfony\Component\HttpFoundation\Request;

class TranslationForm extends AbstractForm
{

    protected $localeFacade;

    /**
     * @param Request $request
     * @param QueryContainerInterface $queryContainer
     * @param GlossaryToLocaleInterface $localeFacade
     */
    public function __construct(
        Request $request,
        QueryContainerInterface $queryContainer,
        GlossaryToLocaleInterface $localeFacade
    ) {
        parent::__construct($request, $queryContainer);
        $this->localeFacade = $localeFacade;
    }

    /**
     * @return array
     */
    protected function getDefaultData()
    {
        $idGlossaryKey = $this->stateContainer
            ->getRequestValue('id_glossary_key')
        ;

        $translations = [];
        $translatedValues = $this->queryContainer
            ->queryTranslationsByKeyId($idGlossaryKey)
        ;

        foreach ($translatedValues as $translation) {
            $translations['locale_' . $translation->getFkLocale()] = $translation->getValue();
        }

        return $translations;
    }

    /**
     * @return array
     */
    public function addFormFields()
    {
        $fields = [];

        $locales = $this->localeFacade->getAvailableLocales();

        foreach ($locales as $localeId => $locale) {
            $fields[] = $this->addField('locale_' . $localeId)
                ->setLabel($locale)
            ;
        }

        return $fields;
    }

}
