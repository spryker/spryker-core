<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Glossary\Communication\Form;

use Propel\Runtime\Map\TableMap;
use SprykerEngine\Zed\Locale\Persistence\Propel\Map\SpyLocaleTableMap;
use SprykerFeature\Zed\Glossary\Persistence\Propel\Map\SpyGlossaryKeyTableMap;
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
        $result = [];

        $fkGlossaryKey = $this->request->get(self::FK_GLOSSARY_KEY);

        if (!empty($fkGlossaryKey)) {
            $translations = $this->glossaryTranslationQuery->filterByFkGlossaryKey($fkGlossaryKey)
                ->find()
            ;

            if (!empty($translations)) {
                $translations = $translations->toArray(null, false, TableMap::TYPE_COLNAME);

                $position = mb_strpos(SpyGlossaryTranslationTableMap::COL_FK_GLOSSARY_KEY, '.');
                if (false !== $position) {
                    $key = mb_substr(SpyGlossaryTranslationTableMap::COL_FK_GLOSSARY_KEY,  $position + 1);
                }

                if (!empty($key)) {
                    $result[$key] = $translations[0][SpyGlossaryTranslationTableMap::COL_FK_GLOSSARY_KEY];
                }

                foreach ($translations as $value) {
                    $result['locale_' . $value[SpyGlossaryTranslationTableMap::COL_FK_LOCALE]] = $value[SpyGlossaryTranslationTableMap::COL_VALUE];
                }
            }
        }

        return $result;
    }

    /**
     * @return array
     */
    public function buildFormFields()
    {
        $glossaryKeyOptions = [
            'label' => self::NAME,
            'placeholder' => 'Select one',
            'choices' => $this->getKeyOptions(),
        ];

        if (self::UPDATE === $this->type) {
            $glossaryKeyOptions['attr'] = [
                'readonly' => 'readonly',
            ];

            $this->addChoice('fk_glossary_key', $glossaryKeyOptions);
        } else {
            $this->addChoice('fk_glossary_key', $glossaryKeyOptions);
        }

        foreach ($this->locales as $key => $locale) {
            $this->addText('locale_' . $key, [
                'label' => $locale,
            ]);
        }

        $this->addSubmit('submit', [
            'label' => (self::UPDATE === $this->type ? 'Update' : 'Add'),
            'attr' => [
                'class' => 'btn btn-primary',
            ],
        ]);

        return $this;
    }

    private function getKeyOptions()
    {
        $result = [];

        $keys = $this->glossaryKeyQuery->findByIsActive(true);
        if (!empty($keys)) {
            $keys = $keys->toArray(null, false, TableMap::TYPE_COLNAME);

            foreach ($keys as $value) {
                $result[$value[SpyGlossaryKeyTableMap::COL_ID_GLOSSARY_KEY]] = $value[SpyGlossaryKeyTableMap::COL_KEY];
            }
        }

        return $result;
    }
}
