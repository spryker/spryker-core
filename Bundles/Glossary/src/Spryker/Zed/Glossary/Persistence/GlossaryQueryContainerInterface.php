<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Glossary\Persistence;

use Propel\Runtime\ActiveQuery\ModelCriteria;
use Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery;

interface GlossaryQueryContainerInterface
{

    /**
     * @param string $keyName
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery
     */
    public function queryKey($keyName);

    /**
     * @param string $keyName
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery
     */
    public function queryActiveKeysByName($keyName);

    /**
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery
     */
    public function queryKeys();

    /**
     * @param string $keyName
     * @param string $localeName
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery
     */
    public function queryTranslationByNames($keyName, $localeName);

    /**
     * @param int $idKey
     * @param int $idLocale
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery
     */
    public function queryTranslationByIds($idKey, $idLocale);

    /**
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery
     */
    public function queryTranslations();

    /**
     * @param string $keyName
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery
     */
    public function queryTranslationsByKey($keyName);

    /**
     * @param string $localeName
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery
     */
    public function queryTranslationsByLocale($localeName);

    /**
     * @param int $idGlossaryTranslation
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery
     */
    public function queryTranslationById($idGlossaryTranslation);

    /**
     * @param \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery $query
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function joinTranslationQueryWithKeysAndLocales(SpyGlossaryTranslationQuery $query);

    /**
     * @param array $relevantLocales
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryAllPossibleTranslations(array $relevantLocales);

    /**
     * @param array $relevantLocales
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryAllMissingTranslations(array $relevantLocales);

    /**
     * @param int $idKey
     * @param array $relevantLocales
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryMissingTranslationsForKey($idKey, array $relevantLocales);

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryDistinctKeysFromQuery(ModelCriteria $query);

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryDistinctLocalesFromQuery(ModelCriteria $query);

    /**
     * @param string $key
     *
     * @return array
     */
    public function queryByKey($key);

    /**
     * @param int $fkGlossaryKey
     * @param array $locales
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryGlossaryKeyTranslationsByLocale($fkGlossaryKey, array $locales);

}
