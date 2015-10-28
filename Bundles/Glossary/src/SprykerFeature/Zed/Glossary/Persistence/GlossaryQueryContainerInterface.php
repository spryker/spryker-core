<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Glossary\Persistence;

use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Exception\PropelException;
use Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery;
use Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery;

interface GlossaryQueryContainerInterface
{
    /**
     * @param string $keyName
     *
     * @return SpyGlossaryKeyQuery
     */
    public function queryKey($keyName);

    /**
     * @param string $keyName
     *
     * @return SpyGlossaryKeyQuery
     */
    public function queryActiveKeysByName($keyName);

    /**
     * @return SpyGlossaryKeyQuery
     */
    public function queryKeys();

    /**
     * @param string $keyName
     * @param string $localeName
     *
     * @return SpyGlossaryTranslationQuery
     */
    public function queryTranslationByNames($keyName, $localeName);

    /**
     * @param int $idKey
     * @param int $idLocale
     *
     * @return SpyGlossaryTranslationQuery
     */
    public function queryTranslationByIds($idKey, $idLocale);

    /**
     * @return SpyGlossaryTranslationQuery
     */
    public function queryTranslations();

    /**
     * @param string $keyName
     *
     * @return SpyGlossaryTranslationQuery
     */
    public function queryTranslationsByKey($keyName);

    /**
     * @param string $localeName
     *
     * @return SpyGlossaryTranslationQuery
     */
    public function queryTranslationsByLocale($localeName);

    /**
     * @param int $idGlossaryTranslation
     *
     * @return SpyGlossaryTranslationQuery
     */
    public function queryTranslationById($idGlossaryTranslation);

    /**
     * @param SpyGlossaryTranslationQuery $query
     *
     * @return ModelCriteria
     */
    public function joinTranslationQueryWithKeysAndLocales(SpyGlossaryTranslationQuery $query);

    /**
     * @param array $relevantLocales
     *
     * @return ModelCriteria
     * @throws PropelException
     */
    public function queryAllPossibleTranslations(array $relevantLocales);

    /**
     * @param array $relevantLocales
     *
     * @return ModelCriteria
     */
    public function queryAllMissingTranslations(array $relevantLocales);

    /**
     * @param int $idKey
     * @param array $relevantLocales
     *
     * @return ModelCriteria
     */
    public function queryMissingTranslationsForKey($idKey, array $relevantLocales);

    /**
     * @param ModelCriteria $query
     *
     * @return ModelCriteria
     */
    public function queryDistinctKeysFromQuery(ModelCriteria $query);

    /**
     * @param ModelCriteria $query
     *
     * @return ModelCriteria
     */
    public function queryDistinctLocalesFromQuery(ModelCriteria $query);

    /**
     * @param string $key
     *
     * @return array
     */
    public function queryByKey($key);
}
