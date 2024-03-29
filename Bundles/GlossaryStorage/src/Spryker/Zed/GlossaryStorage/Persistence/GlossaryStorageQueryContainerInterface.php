<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GlossaryStorage\Persistence;

use Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery;

/**
 * @deprecated Use {@link \Spryker\Zed\GlossaryStorage\Persistence\GlossaryStorageRepositoryInterface} instead
 */
interface GlossaryStorageQueryContainerInterface
{
    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array $glossaryKeyIds
     *
     * @return \Orm\Zed\GlossaryStorage\Persistence\SpyGlossaryStorageQuery
     */
    public function queryGlossaryStorageByGlossaryIds(array $glossaryKeyIds);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array $glossaryKeyIds
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery
     */
    public function queryGlossaryTranslation(array $glossaryKeyIds);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param array<int> $glossaryKeysIds
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery
     */
    public function queryGlossaryKeysByIds(array $glossaryKeysIds): SpyGlossaryKeyQuery;
}
