<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Glossary\Communication\Table;

use Propel\Runtime\Map\TableMap;
use Orm\Zed\Locale\Persistence\Map\SpyLocaleTableMap;
use SprykerFeature\Zed\Glossary\Communication\Form\TranslationForm;
use Orm\Zed\Glossary\Persistence\Base\SpyGlossaryTranslationQuery;
use Orm\Zed\Glossary\Persistence\Map\SpyGlossaryKeyTableMap;
use Orm\Zed\Glossary\Persistence\Map\SpyGlossaryTranslationTableMap;
use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;

class TranslationTable extends AbstractTable
{

    const ACTIONS = 'Actions';

    /**
     * @var SpyGlossaryTranslationQuery
     */
    protected $glossaryQuery;

    /**
     * @var SpyGlossaryTranslationQuery
     */
    protected $subGlossaryQuery;

    /**
     * @var array
     */
    protected $locales;

    /**
     * @param SpyGlossaryTranslationQuery $glossaryQuery
     */
    public function __construct(SpyGlossaryTranslationQuery $glossaryQuery, SpyGlossaryTranslationQuery $subGlossaryKey, array $locales)
    {
        $this->glossaryQuery = $glossaryQuery;
        $this->subGlossaryQuery = $subGlossaryKey;
        $this->locales = $locales;
    }

    /**
     * @inheritDoc
     */
    protected function configure(TableConfiguration $config)
    {
        $headers = [
            SpyGlossaryTranslationTableMap::COL_FK_GLOSSARY_KEY => '#',
            $this->buildAlias(SpyGlossaryKeyTableMap::COL_KEY) => 'Name',
        ];

        foreach ($this->locales as $key => $value) {
            $headers[$value] = $value;
        }

        $config->setSearchable([
            SpyGlossaryTranslationTableMap::COL_VALUE,
            SpyGlossaryKeyTableMap::COL_KEY,
        ]);

        $headers[self::ACTIONS] = self::ACTIONS;

        $config->setHeader($headers);

        $config->setUrl('table');

        $config->setSortable([
            SpyLocaleTableMap::COL_LOCALE_NAME,
            SpyGlossaryTranslationTableMap::COL_ID_GLOSSARY_TRANSLATION,
        ]);

        return $config;
    }

    /**
     * Fetch all existent locales for GlossaryKey
     *
     * @param $fkGlossaryKey
     *
     * @return array
     */
    private function getDetails($fkGlossaryKey)
    {
        $keyName = $this->camelize($this->cutTablePrefix(SpyGlossaryTranslationTableMap::COL_FK_GLOSSARY_KEY));
        $locales = $this->subGlossaryQuery->filterBy($keyName, $fkGlossaryKey)
            ->leftJoinLocale()
            ->withColumn(SpyLocaleTableMap::COL_LOCALE_NAME)
            ->find()
        ;

        $result = [];

        if (!empty($locales)) {
            $localeName = $this->buildAlias(SpyLocaleTableMap::COL_LOCALE_NAME);
            $valueName = SpyGlossaryTranslationTableMap::COL_VALUE;

            $locales = $locales->toArray(null, false, TableMap::TYPE_COLNAME);
            foreach ($locales as $locale) {
                $result[$locale[$localeName]] = $locale[$valueName];
            }
        }

        foreach ($this->locales as $locale) {
            if (!isset($result[$locale])) {
                $result[$locale] = '';
            }
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this->glossaryQuery->leftJoinGlossaryKey()
            ->withColumn(SpyGlossaryKeyTableMap::COL_KEY)
            ->groupByFkGlossaryKey()
        ;

        $lines = $this->runQuery($query, $config);

        $result = [];
        foreach ($lines as $value) {
            $fkGlossaryKey = $value[SpyGlossaryTranslationTableMap::COL_FK_GLOSSARY_KEY];

            $details = $this->getDetails($fkGlossaryKey);
            $result[] = array_merge($value, $details);
        }

        if (!empty($result)) {
            foreach ($result as $key => $value) {
                $result[$key][self::ACTIONS] = $this->buildLinks($value);
            }
        }

        return $result;
    }

    /**
     * @param array $details
     *
     * @return string
     */
    private function buildLinks($details)
    {
        $urls = '';

        if (!empty($details[SpyGlossaryTranslationTableMap::COL_FK_GLOSSARY_KEY])) {
            $idGlossaryKey = $details[SpyGlossaryTranslationTableMap::COL_FK_GLOSSARY_KEY];
            if ($idGlossaryKey !== false) {
                $urls = '<a href="/glossary/edit/?' . TranslationForm::URL_PARAMETER_GLOSSARY_KEY . '=' . $idGlossaryKey . '" class="btn btn-xs btn-white">Edit</a>';
            }
        }

        return $urls;
    }

}
