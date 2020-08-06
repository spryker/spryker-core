<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GlossaryStorage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\SpyGlossaryStorageEntityTransfer;
use Orm\Zed\GlossaryStorage\Persistence\SpyGlossaryStorage;

interface GlossaryStorageMapperInterface
{
    /**
     * @param \Orm\Zed\GlossaryStorage\Persistence\SpyGlossaryStorage $glossaryStorage
     * @param \Generated\Shared\Transfer\SpyGlossaryStorageEntityTransfer $glossaryStorageEntityTransfer
     *
     * @return \Orm\Zed\GlossaryStorage\Persistence\SpyGlossaryStorage
     */
    public function hydrateSpyGlossaryStorageEntity(
        SpyGlossaryStorage $glossaryStorage,
        SpyGlossaryStorageEntityTransfer $glossaryStorageEntityTransfer
    ): SpyGlossaryStorage;

    /**
     * @param \Orm\Zed\Glossary\Persistence\SpyGlossaryKey[] $glossaryKeyEntities
     *
     * @return \Generated\Shared\Transfer\GlossaryKeyTransfer[]
     */
    public function hydrateGlossaryKeyTransfer(
        array $glossaryKeyEntities
    ): array;
}
