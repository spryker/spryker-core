<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GlossaryStorage\Persistence;

use Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\GlossaryStorage\Persistence\GlossaryStoragePersistenceFactory getFactory()
 */
class GlossaryStorageQueryContainer extends AbstractQueryContainer implements GlossaryStorageQueryContainerInterface
{
    /**
     * @api
     *
     * @param array $glossaryKeyIds
     *
     * @return \Orm\Zed\GlossaryStorage\Persistence\SpyGlossaryStorageQuery
     */
    public function queryGlossaryStorageByGlossaryIds(array $glossaryKeyIds)
    {
        return $this->getFactory()
            ->createGlossaryStorageQuery()
            ->filterByFkGlossaryKey_In($glossaryKeyIds);
    }

    /**
     * @api
     *
     * @param array $glossaryKeyIds
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery
     */
    public function queryGlossaryTranslation(array $glossaryKeyIds)
    {
        return $this->getFactory()
            ->getGlossaryQueryContainer()
            ->queryTranslations()
            ->leftJoinWithGlossaryKey()
            ->joinWithLocale()
            ->addAnd('fk_glossary_key', $glossaryKeyIds, Criteria::IN)
            ->setFormatter(ModelCriteria::FORMAT_ARRAY);
    }

    /**
     * @api
     *
     * @param int[] $glossaryKeysIds
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery
     */
    public function queryGlossaryKeysByIds(array $glossaryKeysIds): SpyGlossaryKeyQuery
    {
        return $this->getFactory()
            ->getGlossaryQueryContainer()
            ->queryKeys()
            ->filterByIdGlossaryKey_In($glossaryKeysIds);
    }
}
