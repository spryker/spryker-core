<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Glossary\Persistence;

use Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface GlossaryQueryContainerInterface extends QueryContainerInterface
{
    /**
     * @api
     *
     * @param string $keyName
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery
     */
    public function queryKey($keyName);

    /**
     * @api
     *
     * @param string $keyName
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery
     */
    public function queryActiveKeysByName($keyName);

    /**
     * @api
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery
     */
    public function queryKeys();

    /**
     * @api
     *
     * @param string $keyName
     * @param string $localeName
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery
     */
    public function queryTranslationByNames($keyName, $localeName);

    /**
     * @api
     *
     * @param int $idKey
     * @param int $idLocale
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery
     */
    public function queryTranslationByIds($idKey, $idLocale);

    /**
     * @api
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery
     */
    public function queryTranslations();

    /**
     * @api
     *
     * @param string $keyName
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery
     */
    public function queryTranslationsByKey($keyName);

    /**
     * @api
     *
     * @param string $localeName
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery
     */
    public function queryTranslationsByLocale($localeName);

    /**
     * @api
     *
     * @param int $idGlossaryTranslation
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery
     */
    public function queryTranslationById($idGlossaryTranslation);

    /**
     * @api
     *
     * @param \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery $query
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function joinTranslationQueryWithKeysAndLocales(SpyGlossaryTranslationQuery $query);

    /**
     * @api
     *
     * @param array $relevantLocales
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryAllPossibleTranslations(array $relevantLocales);

    /**
     * @api
     *
     * @param array $relevantLocales
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryAllMissingTranslations(array $relevantLocales);

    /**
     * @api
     *
     * @param int $idKey
     * @param array $relevantLocales
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryMissingTranslationsForKey($idKey, array $relevantLocales);

    /**
     * @api
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryDistinctKeysFromQuery(ModelCriteria $query);

    /**
     * @api
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryDistinctLocalesFromQuery(ModelCriteria $query);

    /**
     * @api
     *
     * @param string $key
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery
     */
    public function queryByKey($key);

    /**
     * @api
     *
     * @param int $fkGlossaryKey
     * @param array $locales
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryGlossaryKeyTranslationsByLocale($fkGlossaryKey, array $locales);

    /**
     * @api
     *
     * @param string $value
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery
     */
    public function queryTranslationByValue($value);

    /**
     * @api
     *
     * @param array $idGlossaryKeys
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery
     */
    public function queryGlossaryKeyByIdGlossaryKeys(array $idGlossaryKeys);

    /**
     * @api
     *
     * @param array $idGlossaryKeys
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery
     */
    public function queryGlossaryTranslationByFkGlossaryKeys(array $idGlossaryKeys);
}
