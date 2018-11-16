<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Glossary\Communication\Table;

use Orm\Zed\Glossary\Persistence\Map\SpyGlossaryKeyTableMap;
use Orm\Zed\Glossary\Persistence\Map\SpyGlossaryTranslationTableMap;
use Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery;
use Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery;
use Orm\Zed\Locale\Persistence\Map\SpyLocaleTableMap;
use Propel\Runtime\Map\TableMap;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Glossary\Communication\Controller\EditController;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

class TranslationTable extends AbstractTable
{
    public const ACTIONS = 'Actions';
    public const URL_GLOSSARY_EDIT = '/glossary/edit';
    public const GENERATED_KEY_MASK = 'generated.%';

    /**
     * @var \Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery
     */
    protected $glossaryKeyQuery;

    /**
     * @var \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery
     */
    protected $subGlossaryQuery;

    /**
     * @var array
     */
    protected $locales;

    /**
     * @param \Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery $glossaryKeyQuery
     * @param \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery $subGlossaryKey
     * @param array $locales
     */
    public function __construct(SpyGlossaryKeyQuery $glossaryKeyQuery, SpyGlossaryTranslationQuery $subGlossaryKey, array $locales)
    {
        $this->glossaryKeyQuery = $glossaryKeyQuery;
        $this->subGlossaryQuery = $subGlossaryKey;
        $this->locales = $locales;
    }

    /**
     * @inheritDoc
     */
    protected function configure(TableConfiguration $config)
    {
        $headers = [
            SpyGlossaryKeyTableMap::COL_ID_GLOSSARY_KEY => '#',
            SpyGlossaryKeyTableMap::COL_KEY => 'Name',
        ];

        foreach ($this->locales as $key => $value) {
            $headers[$value] = $value;
            $config->addRawColumn($value);
        }

        $config->setSearchable([
            SpyGlossaryTranslationTableMap::COL_VALUE,
            SpyGlossaryKeyTableMap::COL_KEY,
        ]);

        $headers[self::ACTIONS] = self::ACTIONS;

        $config->setHeader($headers);

        $config->addRawColumn(self::ACTIONS);

        $config->setUrl('table');

        $config->setSortable([
            SpyGlossaryKeyTableMap::COL_ID_GLOSSARY_KEY,
        ]);

        return $config;
    }

    /**
     * Fetch all existent locales for GlossaryKey
     *
     * @param int $fkGlossaryKey
     *
     * @return array
     */
    protected function getDetails($fkGlossaryKey)
    {
        $keyName = $this->camelize($this->cutTablePrefix(SpyGlossaryTranslationTableMap::COL_FK_GLOSSARY_KEY));
        $locales = $this->subGlossaryQuery->filterBy($keyName, $fkGlossaryKey)
            ->leftJoinLocale()
            ->withColumn(SpyLocaleTableMap::COL_LOCALE_NAME)
            ->find();

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
        $query = $this->glossaryKeyQuery
            ->leftJoinSpyGlossaryTranslation()
            ->filterByKey(self::GENERATED_KEY_MASK, Criteria::NOT_LIKE)
            ->groupByIdGlossaryKey();

        $lines = $this->runQuery($query, $config);

        $result = [];
        foreach ($lines as $value) {
            $details = $this->getDetails($value[SpyGlossaryKeyTableMap::COL_ID_GLOSSARY_KEY]);
            $result[] = array_merge($value, $details);
        }

        if (!empty($result)) {
            foreach ($result as $key => $value) {
                $result[$key][self::ACTIONS] = implode(' ', $this->buildActionUrls($value));
            }
        }

        return $result;
    }

    /**
     * @param array $details
     *
     * @return array
     */
    protected function buildActionUrls($details)
    {
        $urls = [];

        $idGlossaryKey = $details[SpyGlossaryKeyTableMap::COL_ID_GLOSSARY_KEY];
        $urls[] = $this->generateEditButton(
            Url::generate(self::URL_GLOSSARY_EDIT, [
                EditController::URL_PARAMETER_GLOSSARY_KEY => $idGlossaryKey,
            ]),
            'Edit'
        );

        return $urls;
    }
}
