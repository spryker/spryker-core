<?php

/*
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerFeature\Zed\Glossary\Communication\Form;

use SprykerEngine\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;
use SprykerFeature\Zed\Glossary\Dependency\Facade\GlossaryToLocaleInterface;
use Propel\Runtime\Exception\PropelException;
use SprykerEngine\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;
use SprykerFeature\Zed\Glossary\Dependency\Facade\GlossaryToLocaleInterface;
use SprykerFeature\Zed\Glossary\Persistence\GlossaryQueryContainerInterface;
use SprykerFeature\Zed\Library\Propel\Formatter\PropelArraySetFormatter;
use SprykerFeature\Zed\Ui\Communication\Plugin\Form\Field;
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
        Request $request = null,
        QueryContainerInterface $queryContainer,
        GlossaryToLocaleInterface $localeFacade
    ) {
        parent::__construct($request, $queryContainer);
        $this->localeFacade = $localeFacade;
    }

    /**
     * @return Field[]
     */
    public function addFormFields()
    {
        $keyChoices = $this->getNotFullyTranslatedGlossaryKeys();
        $localeChoices = $this->getLocales();

        $fields[] = $this->addField('id_glossary_translation')
            ->setConstraints([
                new Assert\Optional([
                    new Assert\Type([
                        'type' => 'integer'
                    ])
                ])
            ]);

        $fields[] = $this->addField('fk_glossary_key')
            ->setAccepts($keyChoices)
            ->setRefresh(true)
            ->setConstraints([
                new Assert\Type([
                    'type' => 'integer'
                ]),
                new Assert\Choice([
                    'choices' => array_column($keyChoices, 'value'),
                    'message' => 'Please choose one of the given Glossary Keys'
                ])
            ])
            ->setValueHook(function ($value) {
                return $value ? (int)$value : null;
            });

        $fields[] = $this->addField('fk_locale')
            ->setAccepts($localeChoices)
            ->setConstraints([
                new Assert\Type([
                    'type' => 'integer'
                ]),
                new Assert\Choice([
                    'choices' => array_column($localeChoices, 'value'),
                    'message' => 'Please choose one of the given Locales Keys'
                ])
            ])
            ->setValueHook(function ($value) {
                return $value ? (int)$value : null;
            });

        $fields[] = $this->addField('value')
            ->setName('value');

        $fields[] = $this->addField('is_active')
            ->setConstraints([
                new Assert\Type([
                    'type' => 'bool'
                ]),
            ]);

        return $fields;
    }

    /**
     * @return array
     * @throws PropelException
     */
    protected function getNotFullyTranslatedGlossaryKeys()
    {
        $query = $this->queryContainer->queryAllMissingTranslations($this->localeFacade->getAvailableLocales());
        $query = $this->queryContainer->queryDistinctKeysFromQuery($query);

        $query->setFormatter(new PropelArraySetFormatter());

        $glossaryKeys = $query->find();

        /**
         * @param array $element
         *
         * @return array
         */
        $convertToInt = function (array $element) {
            $element['value'] = (int)$element['value'];
            return $element;
        };

        $glossaryKeys = array_map($convertToInt, $glossaryKeys);

        return $glossaryKeys;
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
    protected function getLocalesWithoutKeyChange(array $requestData)
    {
        $idTranslation = $requestData['id_glossary_translation'];
        $translationEntity = $this->queryContainer->queryTranslationById($idTranslation)->findOne();
        $fkGlossaryKey = $translationEntity->getFkGlossaryKey();
        $locales = $this->getLocalesForKey($fkGlossaryKey);
        $currentLocale = $translationEntity->getLocale();
        $locales[] = [
            'label' => $currentLocale->getLocaleName(),
            'value' => $currentLocale->getIdLocale()
        ];

        return $locales;
    }

    /**
     * @param int $idKey
     *
     * @return array
     */
    protected function getLocalesForKey($idKey)
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

    /**
     * @param array $requestData
     *
     * @return array
     */
    protected function getLocalesWithKeyChange(array $requestData)
    {
        $fkGlossaryKey = $requestData['fk_glossary_key'];

        return $this->getLocalesForKey($fkGlossaryKey);
    }

    /**
     * @param array $requestData
     *
     * @return array
     */
    protected function getLocalesForCreate(array $requestData)
    {
        if (!$this->hasFkGlossaryKey($requestData)) {
            return [];
        }

        return $this->getLocalesWithKeyChange($requestData);
    }

    /**
     * @return array
     */
    public function getDefaultData()
    {
        $idGlossaryTranslation = $this->stateContainer->getRequestValue('id_glossary_translation');
        $translationQuery = $this->queryContainer->queryTranslationById($idGlossaryTranslation);
        $translationEntity = $translationQuery->findOne();

        if ($translationEntity) {
            return $translationEntity->toArray();
        }

        return [];
    }
}
