<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Glossary\Persistence;

use Orm\Zed\Glossary\Persistence\Map\SpyGlossaryKeyTableMap;
use Orm\Zed\Glossary\Persistence\Map\SpyGlossaryTranslationTableMap;
use Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery;
use Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery;
use Orm\Zed\Locale\Persistence\Map\SpyLocaleTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Join;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Map\TableMap;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\Glossary\Persistence\GlossaryPersistenceFactory getFactory()
 */
class GlossaryQueryContainer extends AbstractQueryContainer implements GlossaryQueryContainerInterface
{
    /**
     * @var string
     */
    public const TRANSLATION = 'translation';

    /**
     * @var string
     */
    public const TRANSLATION_IS_ACTIVE = 'translation_is_active';

    /**
     * @var string
     */
    public const KEY_IS_ACTIVE = 'key_is_active';

    /**
     * @var string
     */
    public const GLOSSARY_KEY = 'glossary_key';

    /**
     * @var string
     */
    public const GLOSSARY_KEY_IS_ACTIVE = 'glossary_key_is_active';

    /**
     * @var string
     */
    public const LOCALE = 'locale';

    /**
     * @var string
     */
    public const VALUE = 'value';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $keyName
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery
     */
    public function queryKey($keyName)
    {
        $query = $this->queryKeys();
        $query->filterByKey($keyName);

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $keyName
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery
     */
    public function queryActiveKeysByName($keyName)
    {
        $query = $this->queryKeys();
        $query->filterByIsActive(true)->filterByKey_Like($keyName);

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery
     */
    public function queryKeys()
    {
        return $this->getFactory()->createGlossaryKeyQuery();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $keyName
     * @param string $localeName
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery
     */
    public function queryTranslationByNames($keyName, $localeName)
    {
        /** @var \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery $query */
        $query = $this->queryTranslations()
            ->useGlossaryKeyQuery()
                ->filterByKey($keyName)
            ->endUse();

        /** @var \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery $query */
        $query = $query
            ->useLocaleQuery()
                ->filterByLocaleName($localeName)
            ->endUse();

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery
     */
    public function queryTranslations()
    {
        return $this->getFactory()->createGlossaryTranslationQuery();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idKey
     * @param int $idLocale
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery
     */
    public function queryTranslationByIds($idKey, $idLocale)
    {
        $query = $this->queryTranslations();
        $query
            ->filterByFkGlossaryKey($idKey)
            ->filterByFkLocale($idLocale);

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idGlossaryTranslation
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery
     */
    public function queryTranslationById($idGlossaryTranslation)
    {
        $query = $this->queryTranslations();
        $query->filterByIdGlossaryTranslation($idGlossaryTranslation);

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $localeName
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery
     */
    public function queryTranslationsByLocale($localeName)
    {
        $query = $this->queryTranslations();
        $query
            ->useLocaleQuery()
            ->filterByLocaleName($localeName)
            ->endUse();

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $localeId
     * @param int $glossaryKeyId
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery
     */
    public function queryTranslationByIdLocaleAndKeyId($localeId, $glossaryKeyId)
    {
        $query = $this->queryTranslations();
        $query->filterByFkLocale($localeId);
        $query->filterByFkGlossaryKey($glossaryKeyId);

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $fkGlossaryKeyId
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery
     */
    public function queryTranslationsByKeyId($fkGlossaryKeyId)
    {
        $query = $this->queryTranslations()
            ->filterByFkGlossaryKey($fkGlossaryKeyId);

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $keyName
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery
     */
    public function queryTranslationsByKey($keyName)
    {
        $query = $this->queryTranslations();
        $query
            ->useGlossaryKeyQuery()
            ->filterByKey($keyName)
            ->endUse();

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery $query
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function joinTranslationQueryWithKeysAndLocales(SpyGlossaryTranslationQuery $query)
    {
        $query
            ->joinLocale()
            ->withColumn(SpyLocaleTableMap::COL_LOCALE_NAME, static::LOCALE)
            ->joinGlossaryKey()
            ->withColumn(SpyGlossaryTranslationTableMap::COL_VALUE, static::TRANSLATION)
            ->withColumn(SpyGlossaryTranslationTableMap::COL_IS_ACTIVE, static::TRANSLATION_IS_ACTIVE)
            ->withColumn(SpyGlossaryKeyTableMap::COL_KEY, static::GLOSSARY_KEY)
            ->withColumn(SpyGlossaryKeyTableMap::COL_IS_ACTIVE, static::GLOSSARY_KEY_IS_ACTIVE);

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $localeIds
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery
     */
    public function queryKeysAndTranslationsForEachLanguage(array $localeIds)
    {
        $translationQuery = $this->queryKeys();
        foreach ($localeIds as $idLocale) {
            $translationQuery
                ->addJoinObject(
                    (new Join(
                        SpyGlossaryKeyTableMap::COL_ID_GLOSSARY_KEY,
                        SpyGlossaryTranslationTableMap::COL_FK_GLOSSARY_KEY,
                        Criteria::LEFT_JOIN,
                    ))->setRightTableAlias('translation_' . $idLocale . '_'),
                    'translation_' . $idLocale . 'join',
                )
                ->addJoinCondition(
                    'translation_' . $idLocale . 'join',
                    'translation_' . $idLocale . '_.fk_locale = ' . $idLocale,
                );

            $translationQuery->withColumn(
                'translation_' . $idLocale . '_.value',
            );
        }
        $translationQuery->groupByIdGlossaryKey();

        return $translationQuery;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $relevantLocales
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryAllMissingTranslations(array $relevantLocales)
    {
        $keyQuery = $this->queryAllPossibleTranslations($relevantLocales);
        $keyQuery
            ->where(SpyGlossaryTranslationTableMap::COL_VALUE . '' . ModelCriteria::ISNULL);

        return $keyQuery;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $relevantLocales
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryAllPossibleTranslations(array $relevantLocales)
    {
        $keyQuery = $this->queryKeys();

        return $this->joinKeyQueryWithRelevantLocalesAndTranslations($keyQuery, $relevantLocales);
    }

    /**
     * @param \Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery $keyQuery
     * @param array $relevantLocales
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function joinKeyQueryWithRelevantLocalesAndTranslations(
        SpyGlossaryKeyQuery $keyQuery,
        array $relevantLocales
    ) {
        $keyLocaleCrossJoin = new ModelJoin();
        $keyLocaleCrossJoin->setJoinType(Criteria::JOIN);

        /**
         * @param string $value
         *
         * @return string
         */
        $quoteFunction = function ($value) {
            return "'$value'";
        };

        $quotedLocales = array_map($quoteFunction, $relevantLocales);

        $keyLocaleCrossJoin
            ->setTableMap(new TableMap())
            ->setLeftTableName('spy_glossary_key')
            ->setRightTableName('spy_locale')
            ->addCondition('id_glossary_key', 'id_locale', ModelCriteria::NOT_EQUAL);

        $translationLeftJoin = new ModelJoin();
        $translationLeftJoin->setJoinType(Criteria::LEFT_JOIN);
        $translationLeftJoin
            ->setTableMap(new TableMap())
            ->setLeftTableName('spy_glossary_key')
            ->setRightTableName('spy_glossary_translation')
            ->addCondition('id_glossary_key', 'fk_glossary_key');

        return $keyQuery
            ->addJoinObject($keyLocaleCrossJoin, 'spy_locale')
            ->addJoinObject($translationLeftJoin, 'spy_glossary_translation')
            ->addJoinCondition('spy_glossary_translation', 'spy_locale.id_locale = spy_glossary_translation.fk_locale')
            ->addJoinCondition('spy_locale', 'spy_locale.locale_name  IN (' . implode(', ', $quotedLocales) . ')');
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryDistinctKeysFromQuery(ModelCriteria $query)
    {
        $query
            ->distinct()
            ->withColumn(SpyGlossaryKeyTableMap::COL_ID_GLOSSARY_KEY, 'value')
            ->withColumn(SpyGlossaryKeyTableMap::COL_KEY, 'label');

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryDistinctLocalesFromQuery(ModelCriteria $query)
    {
        $query
            ->distinct()
            ->withColumn(SpyLocaleTableMap::COL_ID_LOCALE, 'value')
            ->withColumn(SpyLocaleTableMap::COL_LOCALE_NAME, 'label');

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idKey
     * @param array $relevantLocales
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryMissingTranslationsForKey($idKey, array $relevantLocales)
    {
        $keyQuery = $this->queryKeyById($idKey);
        $keyQuery = $this->joinKeyQueryWithRelevantLocalesAndTranslations($keyQuery, $relevantLocales);
        $keyQuery
            ->where(SpyGlossaryTranslationTableMap::COL_VALUE . '' . ModelCriteria::ISNULL);

        return $keyQuery;
    }

    /**
     * @param int $idKey
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery
     */
    protected function queryKeyById($idKey)
    {
        return $this->queryKeys()->filterByIdGlossaryKey($idKey);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $key
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery
     */
    public function queryByKey($key)
    {
        $query = $this->queryKeys();
        $query->where('lower(' . SpyGlossaryKeyTableMap::COL_KEY . ') like ?', '%' . mb_strtolower($key) . '%');

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $value
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery
     */
    public function queryTranslationByValue($value)
    {
        $query = $this->queryTranslations();
        $query->where('lower(' . SpyGlossaryTranslationTableMap::COL_VALUE . ') like ?', '%' . mb_strtolower($value) . '%');

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $fkGlossaryKey
     * @param array $locales
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryGlossaryKeyTranslationsByLocale($fkGlossaryKey, array $locales)
    {
        $query = $this->queryTranslations()
            ->useLocaleQuery(null, Criteria::LEFT_JOIN)
            ->leftJoinSpyGlossaryTranslation(SpyGlossaryTranslationTableMap::TABLE_NAME)
            ->addJoinCondition(SpyGlossaryTranslationTableMap::TABLE_NAME, SpyGlossaryTranslationTableMap::COL_FK_GLOSSARY_KEY . ' = ?', (int)$fkGlossaryKey)
            ->where(SpyLocaleTableMap::COL_LOCALE_NAME . ' IN ?', $locales)
            ->groupBy(SpyLocaleTableMap::COL_ID_LOCALE)
            ->withColumn(SpyLocaleTableMap::COL_LOCALE_NAME, static::LOCALE)
            ->withColumn(SpyGlossaryTranslationTableMap::COL_VALUE, static::VALUE);

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $idGlossaryKeys
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery
     */
    public function queryGlossaryKeyByIdGlossaryKeys(array $idGlossaryKeys)
    {
        return $this->queryKeys()
            ->filterByIdGlossaryKey($idGlossaryKeys, Criteria::IN);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $idGlossaryKeys
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery
     */
    public function queryGlossaryTranslationByFkGlossaryKeys(array $idGlossaryKeys)
    {
        return $this->queryTranslations()
            ->filterByFkGlossaryKey($idGlossaryKeys, Criteria::IN);
    }
}
