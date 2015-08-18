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
    const FK_GLOSSARY_KEY = 'fk_glossary_key';
    const NAME = 'Name';
    const LOCALE = 'locale_name';

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
     * @var bool
     */
    protected $fieldsPopulated = false;

    /**
     * @var array
     */
    protected $fieldsPopulatedResult = [];

    public function __construct(SpyGlossaryTranslationQuery $glossaryTranslationQuery, SpyGlossaryKeyQuery $glossaryKeyQuery, $locales, $type)
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
        if (!$this->fieldsPopulated) {
            $result = [];

            $fkGlossaryKey = $this->request->get(self::FK_GLOSSARY_KEY);

            if (!empty($fkGlossaryKey)) {
                $key = $this->glossaryKeyQuery->findOneByIdGlossaryKey($fkGlossaryKey);

                $result['glossary_key'] = $key->getKey();

                $translations = $this->glossaryTranslationQuery->filterByFkGlossaryKey($fkGlossaryKey)
                    ->useLocaleQuery()
                        ->withColumn(SpyLocaleTableMap::COL_LOCALE_NAME, static::LOCALE)
                    ->endUse()
                    ->find()
                ;

                if (!empty($translations)) {
                    $translations = $translations->toArray(null, false, TableMap::TYPE_COLNAME);

                    foreach ($translations as $value) {
                        $result['locales'][$value[static::LOCALE]] = $value[SpyGlossaryTranslationTableMap::COL_VALUE];
                    }
                }
            }

            $this->fieldsPopulatedResult = $result;
            $this->fieldsPopulated = true;
        }

        return $this->fieldsPopulatedResult;
    }

    /**
     * @return array
     */
    public function buildFormFields()
    {

        if (self::UPDATE === $this->type) {
            $this->addText('glossary_key', [
                'label' => self::NAME,
                'attr' => [
                'readonly' => 'readonly',
                ],
            ]);
        } else {
            $this->addAutosuggest('glossary_key', [
                'label' => self::NAME,
                'url' => '/glossary/key/suggest',
            ]);
        }

        $defaultData = $this->populateFormFields();

        $localeFields = [];
        foreach ($this->locales as $value) {
            $localeFields[$value] = isset($defaultData['locales'][$value])
                ? $defaultData['locales'][$value]
                : ''
            ;
        }

        $this->add('locales', 'collection', [
            'type' => 'text',
            'label' => false,
            'data' => $localeFields,
            'constraints' => [
                new NotBlank(),
                new Required(),
            ],
        ]);

        return $this;
    }

}
