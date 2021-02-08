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
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $keyName
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery
     */
    public function queryKey($keyName);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $keyName
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery
     */
    public function queryActiveKeysByName($keyName);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery
     */
    public function queryKeys();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $keyName
     * @param string $localeName
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery
     */
    public function queryTranslationByNames($keyName, $localeName);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idKey
     * @param int $idLocale
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery
     */
    public function queryTranslationByIds($idKey, $idLocale);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery
     */
    public function queryTranslations();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $keyName
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery
     */
    public function queryTranslationsByKey($keyName);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $localeName
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery
     */
    public function queryTranslationsByLocale($localeName);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idGlossaryTranslation
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery
     */
    public function queryTranslationById($idGlossaryTranslation);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery $query
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function joinTranslationQueryWithKeysAndLocales(SpyGlossaryTranslationQuery $query);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
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
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array $relevantLocales
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryAllMissingTranslations(array $relevantLocales);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idKey
     * @param array $relevantLocales
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryMissingTranslationsForKey($idKey, array $relevantLocales);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryDistinctKeysFromQuery(ModelCriteria $query);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryDistinctLocalesFromQuery(ModelCriteria $query);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $key
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery
     */
    public function queryByKey($key);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $fkGlossaryKey
     * @param array $locales
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryGlossaryKeyTranslationsByLocale($fkGlossaryKey, array $locales);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $value
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery
     */
    public function queryTranslationByValue($value);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array $idGlossaryKeys
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery
     */
    public function queryGlossaryKeyByIdGlossaryKeys(array $idGlossaryKeys);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array $idGlossaryKeys
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery
     */
    public function queryGlossaryTranslationByFkGlossaryKeys(array $idGlossaryKeys);
}
