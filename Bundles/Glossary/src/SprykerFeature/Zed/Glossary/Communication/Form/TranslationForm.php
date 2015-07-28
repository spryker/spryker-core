<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Glossary\Communication\Form;

use Propel\Runtime\Map\TableMap;
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
            $key = $this->glossaryKeyQuery->findOneByIdGlossaryKey($fkGlossaryKey);

            $result['glossary_key'] = $key->getKey();

            $translations = $this->glossaryTranslationQuery->filterByFkGlossaryKey($fkGlossaryKey)
                ->find()
            ;

            if (!empty($translations)) {
                $translations = $translations->toArray(null, false, TableMap::TYPE_COLNAME);

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

        foreach ($this->locales as $key => $locale) {
            $this->addText('locale_' . $key, [
                'label' => $locale,
                'constraints' => [
                    new NotBlank(),
                    new Required(),
                ]
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

}
