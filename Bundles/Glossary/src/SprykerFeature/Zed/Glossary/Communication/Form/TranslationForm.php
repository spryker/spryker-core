<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Glossary\Communication\Form;

use Propel\Runtime\Map\TableMap;
use SprykerEngine\Zed\Locale\Persistence\Propel\Map\SpyLocaleTableMap;
use SprykerFeature\Zed\Glossary\Persistence\Propel\Base\SpyGlossaryKeyQuery;
use SprykerFeature\Zed\Glossary\Persistence\Propel\Map\SpyGlossaryTranslationTableMap;
use SprykerFeature\Zed\Glossary\Persistence\Propel\SpyGlossaryTranslationQuery;
use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;

class TranslationForm extends AbstractForm
{

    const FK_GLOSSARY_KEY = 'fk_glossary_key';

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
     * @var
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
            $result = $this->glossaryTranslationQuery->leftJoinLocale('')
                ->withColumn(SpyLocaleTableMap::COL_LOCALE_NAME)
                ->filterByFkGlossaryKey($fkGlossaryKey)
                ->find()
            ;

            if (!empty($result)) {
                $result = $result->toArray(null, false, TableMap::TYPE_COLNAME);
            }

            die(dump($result));
        }

        return $result;
    }

    /**
     * @return array
     */
    public function buildFormFields()
    {
        $this->addHidden('fk_glossary_key')
            ->addText('glossary_key', [
                'label' => 'Name',
                'attr' => [
                    'disabled' => 'disabled',
                ],
            ])
        ;

        foreach ($this->locales as $locale) {
            $this->addText('locale_' . $locale, [
                'label' => $locale,
            ]);
        }

        return $this;
    }
}
