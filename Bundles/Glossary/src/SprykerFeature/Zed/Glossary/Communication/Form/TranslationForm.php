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

    const ADD = 'add';
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
    protected function populateFormFields()
    {
        return $this->populateFormTranslationFields();
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
    protected function getTranslationFormFields()
    {
        $localeFields = [];
        foreach ($this->locales as $value) {
            $localeFields[$value] = '';

            if (isset($this->defaultData[self::FIELD_LOCALES][$value])) {
                $localeFields[$value] = $this->defaultData[self::FIELD_LOCALES][$value];
            }
        }

        return $localeFields;
    }

    /**
     * @return array
     */
    protected function populateFormTranslationFields()
    {
        $populatedFields = [];

        $fkGlossaryKey = $this->request->get(self::URL_PARAMETER_GLOSSARY_KEY);
        $key = $this->getExistantGlossaryKeyIfExists($fkGlossaryKey);

        if (false === is_null($fkGlossaryKey)) {
            $populatedFields[self::FIELD_GLOSSARY_KEY] = $key->getKey();
        }

        $translations = $this->getAvailableTranslations($fkGlossaryKey);

        if (!empty($translations)) {
            foreach ($translations as $value) {
                $populatedFields[self::FIELD_LOCALES][$value[static::LOCALE]] = $value[SpyGlossaryTranslationTableMap::COL_VALUE];
            }
        } else {
            foreach ($this->locales as $locale) {
                $populatedFields[self::FIELD_LOCALES][$locale] = '';
            }
        }

        return $populatedFields;
    }

    /**
     * @param int $fkGlossaryKey
     *
     * @return array
     */
    protected function getAvailableTranslations($fkGlossaryKey)
    {
        return $this->glossaryTranslationQuery
            ->filterByFkGlossaryKey($fkGlossaryKey)
            ->useLocaleQuery()
                ->withColumn(SpyLocaleTableMap::COL_LOCALE_NAME, static::LOCALE)
            ->endUse()
            ->find()
            ->toArray(null, false, TableMap::TYPE_COLNAME);
        ;
    }

    /**
     * @param int $fkGlossaryKey
     *
     * @return SpyGlossaryKey
     */
    protected function getExistantGlossaryKeyIfExists($fkGlossaryKey)
    {
        return $this->glossaryKeyQuery->findOneByIdGlossaryKey($fkGlossaryKey);
    }

}
