<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Glossary\Persistence;

use Generated\Zed\Ide\FactoryAutoCompletion\GlossaryPersistence;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Join;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerEngine\Zed\Locale\Persistence\Propel\Map\SpyLocaleTableMap;
use SprykerFeature\Zed\Glossary\Persistence\Propel\Map\SpyGlossaryKeyTableMap;
use SprykerFeature\Zed\Glossary\Persistence\Propel\Map\SpyGlossaryTranslationTableMap;
use SprykerFeature\Zed\Glossary\Persistence\Propel\SpyGlossaryKeyQuery;
use SprykerFeature\Zed\Glossary\Persistence\Propel\SpyGlossaryTranslationQuery;

/**
 * @method GlossaryPersistence getFactory()
 */
class GlossaryQueryContainer extends AbstractQueryContainer implements GlossaryQueryContainerInterface
{
    const TRANSLATION = 'translation';
    const TRANSLATION_IS_ACTIVE = 'translation_is_active';
    const KEY_IS_ACTIVE = 'key_is_active';
    const GLOSSARY_KEY = 'glossary_key';
    const GLOSSARY_KEY_IS_ACTIVE = 'glossary_key_is_active';
    const LOCALE = 'locale';

    /**
     * @param string $keyName
     *
     * @return SpyGlossaryKeyQuery
     */
    public function queryKey($keyName)
    {
        $query = $this->queryKeys()->doInsert();
        $query->filterByKey($keyName);

        return $query;
    }

    /**
     * @param string $keyName
     *
     * @return SpyGlossaryKeyQuery
     */
    public function queryActiveKeysByName($keyName)
    {
        $query = $this->queryKeys();
        $query->filterByIsActive(true)->filterByKey($keyName);

        return $query;
    }
    /**
     * @param string $keyName
     *
     * @return SpyGlossaryKeyQuery
     */
    public function queryActiveKeysByNameForAjax($keyName)
    {
        $query = $this->queryActiveKeysByName($keyName);
        $query
            ->withColumn(SpyGlossaryKeyTableMap::COL_KEY, 'label')
            ->withColumn(SpyGlossaryKeyTableMap::COL_ID_GLOSSARY_KEY, 'value')
            ->select(['label', 'value']);

        return $query;
    }

    /**
     * @return SpyGlossaryKeyQuery
     */
    public function queryKeys()
    {
        return $this->getFactory()->createPropelSpyGlossaryKeyQuery();
    }

    /**
     * @param string $keyName
     * @param string $localeName
     *
     * @return SpyGlossaryTranslationQuery
     */
    public function queryTranslationByNames($keyName, $localeName)
    {
        $query = $this->queryTranslations();
        $query
            ->useGlossaryKeyQuery()
            ->filterByKey($keyName)
            ->endUse()

            ->useLocaleQuery()
            ->filterByLocaleName($localeName)
            ->endUse()
        ;

        return $query;
    }

    /**
     * @return SpyGlossaryTranslationQuery
     */
    public function queryTranslations()
    {
        return $this->getFactory()->createPropelSpyGlossaryTranslationQuery();
    }

    /**
     * @param int $idKey
     * @param int $idLocale
     *
     * @return SpyGlossaryTranslationQuery
     */
    public function queryTranslationByIds($idKey, $idLocale)
    {
        $query = $this->queryTranslations();
        $query
            ->filterByFkGlossaryKey($idKey)
            ->filterByFkLocale($idLocale)
        ;

        return $query;
    }

    /**
     * @param int $idSpyGlossaryTranslation
     *
     * @return SpyGlossaryTranslationQuery
     */
    public function queryTranslationById($idSpyGlossaryTranslation)
    {
        $query = $this->queryTranslations();
        $query->filterByIdGlossaryTranslation($idSpyGlossaryTranslation);

        return $query;
    }

    /**
     * @param string $localeName
     *
     * @return SpyGlossaryTranslationQuery
     */
    public function queryTranslationsByLocale($localeName)
    {
        $query = $this->queryTranslations();
        $query
            ->useLocaleQuery()
            ->filterByLocaleName($localeName)
            ->endUse()
        ;

        return $query;
    }

    /**
     * @param int $localeId
     * @param int $glossaryKeyId
     *
     * @return SpyGlossaryTranslationQuery
     */
    public function queryTranslationByIdLocaleAndKeyId($localeId, $glossaryKeyId)
    {
        $query = $this->queryTranslations();
        $query->filterByFkLocale($localeId);
        $query->filterByFkGlossaryKey($glossaryKeyId);

        return $query;
    }

    /**
     * @param int $fkGlossaryKeyId
     *
     * @return SpyGlossaryTranslationQuery
     */
    public function queryTranslationsByKeyId($fkGlossaryKeyId)
    {
        $query = $this->queryTranslations()
            ->filterByFkGlossaryKey($fkGlossaryKeyId)
        ;

        return $query;
    }

    /**
     * @param string $keyName
     *
     * @return SpyGlossaryTranslationQuery
     */
    public function queryTranslationsByKey($keyName)
    {
        $query = $this->queryTranslations();
        $query
            ->useGlossaryKeyQuery()
            ->filterByKey($keyName)
            ->endUse()
        ;

        return $query;
    }

    /**
     * @param SpyGlossaryTranslationQuery $query
     *
     * @return ModelCriteria
     */
    public function joinTranslationQueryWithKeysAndLocales(SpyGlossaryTranslationQuery $query)
    {
        $query
            ->joinLocale()
            ->withColumn(SpyLocaleTableMap::COL_LOCALE_NAME, self::LOCALE)
            ->joinGlossaryKey()
            ->withColumn(SpyGlossaryTranslationTableMap::COL_VALUE, self::TRANSLATION)
            ->withColumn(SpyGlossaryTranslationTableMap::COL_IS_ACTIVE, self::TRANSLATION_IS_ACTIVE)
            ->withColumn(SpyGlossaryKeyTableMap::COL_KEY, self::GLOSSARY_KEY)
            ->withColumn(SpyGlossaryKeyTableMap::COL_IS_ACTIVE, self::GLOSSARY_KEY_IS_ACTIVE)
        ;

        return $query;
    }

    /**
     * @param array $localeIds
     *
     * @return SpyGlossaryKeyQuery
     * @throws PropelException
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
                        Criteria::LEFT_JOIN
                    ))->setRightTableAlias('translation_' . $idLocale . '_'),
                    'translation_' . $idLocale . 'join'
                )
                ->addJoinCondition(
                    'translation_' . $idLocale . 'join',
                    'translation_' . $idLocale . '_.fk_locale = ' . $idLocale
                )
            ;

            $translationQuery->withColumn(
                'translation_' . $idLocale . '_.value'
            );
        }
        $translationQuery->groupByIdGlossaryKey();

        return $translationQuery;
    }

    /**
     * @param array $relevantLocales
     *
     * @return ModelCriteria
     */
    public function queryAllMissingTranslations(array $relevantLocales)
    {
        $keyQuery = $this->queryAllPossibleTranslations($relevantLocales);
        $keyQuery
            ->where(SpyGlossaryTranslationTableMap::COL_VALUE . '' . ModelCriteria::ISNULL)
        ;

        return $keyQuery;
    }

    /**
     * @param array $relevantLocales
     *
     * @return ModelCriteria
     * @throws PropelException
     */
    public function queryAllPossibleTranslations(array $relevantLocales)
    {
        $keyQuery = $this->queryKeys();

        return $this->joinKeyQueryWithRelevantLocalesAndTranslations($keyQuery, $relevantLocales);
    }

    /**
     * @param SpyGlossaryKeyQuery $keyQuery
     * @param array $relevantLocales
     *
     * @return ModelCriteria
     * @throws PropelException
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
            ->addCondition('id_glossary_key', 'id_locale', ModelCriteria::NOT_EQUAL)
        ;

        $translationLeftJoin = new ModelJoin();
        $translationLeftJoin->setJoinType(Criteria::LEFT_JOIN);
        $translationLeftJoin
            ->setTableMap(new TableMap())
            ->setLeftTableName('spy_glossary_key')
            ->setRightTableName('spy_glossary_translation')
            ->addCondition('id_glossary_key', 'fk_glossary_key')
        ;

        return $keyQuery
            ->addJoinObject($keyLocaleCrossJoin, 'spy_locale')
            ->addJoinObject($translationLeftJoin, 'spy_glossary_translation')
            ->addJoinCondition('spy_glossary_translation', 'spy_locale.id_locale = spy_glossary_translation.fk_locale')
            ->addJoinCondition('spy_locale', 'spy_locale.locale_name  IN ('  . implode($quotedLocales, ', ') . ')')
        ;
    }

    /**
     * @param ModelCriteria $query
     *
     * @return ModelCriteria
     */
    public function queryDistinctKeysFromQuery(ModelCriteria $query)
    {
        $query
            ->distinct('key')
            ->withColumn(SpyGlossaryKeyTableMap::COL_ID_GLOSSARY_KEY, 'value')
            ->withColumn(SpyGlossaryKeyTableMap::COL_KEY, 'label')
        ;

        return $query;
    }

    /**
     * @param ModelCriteria $query
     *
     * @return ModelCriteria
     */
    public function queryDistinctLocalesFromQuery(ModelCriteria $query)
    {
        $query
            ->distinct('locale_name')
            ->withColumn(SpyLocaleTableMap::COL_ID_LOCALE, 'value')
            ->withColumn(SpyLocaleTableMap::COL_LOCALE_NAME, 'label')
        ;

        return $query;
    }

    /**
     * @param int $idKey
     * @param array $relevantLocales
     *
     * @return ModelCriteria
     */
    public function queryMissingTranslationsForKey($idKey, array $relevantLocales)
    {
        $keyQuery = $this->queryKeyById($idKey);
        $keyQuery = $this->joinKeyQueryWithRelevantLocalesAndTranslations($keyQuery, $relevantLocales);
        $keyQuery
            ->where(SpyGlossaryTranslationTableMap::COL_VALUE . '' . ModelCriteria::ISNULL)
        ;

        return $keyQuery;
    }

    /**
     * @param int $idKey
     *
     * @return SpyGlossaryKeyQuery
     */
    protected function queryKeyById($idKey)
    {
        return $this->queryKeys()->filterByIdGlossaryKey($idKey);
    }

    /**
     * @param string $key
     */
    public function queryByKey($key)
    {
        $keyQuery = $this->queryKeys();
        $keyQuery->filterByKey('%' . mb_strtolower($key) . '%', Criteria::LIKE);

        return $keyQuery;
    }
}
