<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Glossary\Communication\Form;

use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Map\TableMap;
use SprykerEngine\Zed\Locale\Persistence\Propel\Map\SpyLocaleTableMap;
use SprykerFeature\Zed\Glossary\Persistence\Propel\Map\SpyGlossaryTranslationTableMap;
use SprykerFeature\Zed\Glossary\Persistence\Propel\SpyGlossaryKeyQuery;
use SprykerFeature\Zed\Glossary\Persistence\Propel\SpyGlossaryTranslationQuery;
use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;

class TranslationForm extends AbstractForm
{

    const UPDATE = 'update';
    const URL_PARAMETER_GLOSSARY_KEY = 'fk-glossary-key';
    const NAME = 'Name';
    const LOCALE = 'translation_locale_name';
    const FIELD_GLOSSARY_KEY = 'glossary_key';
    const FIELD_LOCALES = 'locales';
    const FIELD_VALUE = 'value';
    const TYPE_DATA = 'data';
    const TYPE_DATA_EMPTY = 'empty_data';

    /**
     * @var SpyGlossaryTranslationQuery
     */
    protected $glossaryTranslationQuery;

    /**
     * @var SpyGlossaryKeyQuery
     */
    protected $glossaryKeyQuery;

    /**
     * @var array
     */
    protected $locales;

    /**
     * @var string
     */
    protected $type;

    /**
     * @param SpyGlossaryTranslationQuery $glossaryTranslationQuery
     * @param SpyGlossaryKeyQuery $glossaryKeyQuery
     * @param array $locales
     * @param string $type
     */
    public function __construct(SpyGlossaryTranslationQuery $glossaryTranslationQuery, SpyGlossaryKeyQuery $glossaryKeyQuery, array $locales, $type)
    {
        $this->glossaryTranslationQuery = $glossaryTranslationQuery;
        $this->glossaryKeyQuery = $glossaryKeyQuery;
        $this->locales = $locales;
        $this->type = $type;
    }

    /**
     * @return array
     */
    public function buildFormFields()
    {
        if (self::UPDATE === $this->type) {
            $this->addText(self::FIELD_GLOSSARY_KEY, [
                'label' => self::NAME,
                'attr' => [
                    'readonly' => 'readonly',
                ],
            ]);
        } else {
            $this->addAutosuggest(self::FIELD_GLOSSARY_KEY, [
                'label' => self::NAME,
                'url' => '/glossary/key/suggest',
                'constraints' => $this->getFieldDefaultConstraints(),
            ]);
        }

        $this->add(self::FIELD_LOCALES, 'collection', $this->buildLocaleFieldConfiguration());

        return $this;
    }

    /**
     * @return array
     */
    protected function buildLocaleFieldConfiguration()
    {
        $translationFields = array_fill_keys($this->locales, '');

        $dataTypeField = self::TYPE_DATA_EMPTY;
        if (empty($this->request->get(self::URL_PARAMETER_GLOSSARY_KEY))) {
            $dataTypeField = self::TYPE_DATA;
        }

        return [
            'type' => 'text',
            'label' => false,
            $dataTypeField => $translationFields,
            'constraints' => $this->getFieldDefaultConstraints(),
        ];
    }

    /**
     * @return array
     */
    protected function getFieldDefaultConstraints()
    {
        return [
            new NotBlank(),
            new Required(),
        ];
    }

    /**
     * @return array
     */
    protected function populateFormFields()
    {
        $defaultData = [];

        $fkGlossaryKey = $this->request->get(self::URL_PARAMETER_GLOSSARY_KEY, 0);

        if ($fkGlossaryKey > 0) {
            $glossaryKeyEntity = $this->getGlossaryKey($fkGlossaryKey);
            $defaultData[self::FIELD_GLOSSARY_KEY] = $glossaryKeyEntity->getKey();
        }

        $translationCollection = $this->getGlossaryKeyTranslations($fkGlossaryKey);

        if (!empty($translationCollection)) {
            foreach ($translationCollection as $translation) {
                $defaultData[self::FIELD_LOCALES][$translation[static::LOCALE]] = $translation[self::FIELD_VALUE];
            }
        }

        return $defaultData;
    }

    /**
     * @param int $fkGlossaryKey
     *
     * @return array
     */
    protected function getGlossaryKeyTranslations($fkGlossaryKey)
    {
        return $this->glossaryTranslationQuery
            ->useLocaleQuery(null, Criteria::LEFT_JOIN)
            ->leftJoinSpyGlossaryTranslation(SpyGlossaryTranslationTableMap::TABLE_NAME)
            ->addJoinCondition(SpyGlossaryTranslationTableMap::TABLE_NAME, SpyGlossaryTranslationTableMap::COL_FK_GLOSSARY_KEY . ' = ?', $fkGlossaryKey)
            ->where(SpyLocaleTableMap::COL_LOCALE_NAME . ' IN ?', $this->locales)
            ->groupBy(SpyLocaleTableMap::COL_ID_LOCALE)
            ->withColumn(SpyLocaleTableMap::COL_LOCALE_NAME, self::LOCALE)
            ->withColumn(SpyGlossaryTranslationTableMap::COL_VALUE, self::FIELD_VALUE)
            ->find()
            ->toArray(null, false, TableMap::TYPE_COLNAME)
        ;
    }

    /**
     * @param int $fkGlossaryKey
     *
     * @return SpyGlossaryKey
     */
    protected function getGlossaryKey($fkGlossaryKey)
    {
        return $this->glossaryKeyQuery->findOneByIdGlossaryKey($fkGlossaryKey);
    }

}
