<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Glossary\Communication\Form;

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
                'constraints' => [
                    new NotBlank(),
                    new Required(),
                ],
            ]);
        }

        $this->add(self::FIELD_LOCALES, 'collection', [
            'type' => 'text',
            'label' => false,
            'constraints' => [
                new NotBlank(),
                new Required(),
            ],
        ]);

        return $this;
    }

    /**
     * @return array
     */
    protected function populateFormFields()
    {
        $defaultData = [];

        $fkGlossaryKey = $this->request->get(self::URL_PARAMETER_GLOSSARY_KEY);

        if (null !== $fkGlossaryKey) {
            $glossaryKeyEntity = $this->getGlossaryKey($fkGlossaryKey);
            $defaultData[self::FIELD_GLOSSARY_KEY] = $glossaryKeyEntity->getKey();
        }

        foreach ($this->locales as $locale) {
            $defaultData[self::FIELD_LOCALES][$locale] = '';
        }

        $translationCollection = $this->getGlossaryKeyTranslations($fkGlossaryKey);

        if (!empty($translationCollection)) {
            foreach ($translationCollection as $translation) {
                $defaultData[self::FIELD_LOCALES][$translation[static::LOCALE]] = $translation[SpyGlossaryTranslationTableMap::COL_VALUE];
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
            ->filterByFkGlossaryKey($fkGlossaryKey)
            ->useLocaleQuery()
                ->withColumn(SpyLocaleTableMap::COL_LOCALE_NAME, static::LOCALE)
            ->endUse()
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
