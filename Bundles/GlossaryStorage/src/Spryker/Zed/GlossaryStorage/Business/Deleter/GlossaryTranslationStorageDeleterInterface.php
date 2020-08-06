<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GlossaryStorage\Business\Deleter;

interface GlossaryTranslationStorageDeleterInterface
{
    /**
     * @deprecated Use {@link \Spryker\Zed\GlossaryStorage\Business\Writer\GlossaryTranslationStorageWriterInterface::deleteGlossaryStorageCollectionByGlossaryKeyEvents()} instead
     *
     * @param array $glossaryKeyIds
     *
     * @return void
     */
    public function unpublish(array $glossaryKeyIds);

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function deleteGlossaryStorageCollectionByGlossaryKeyEvents(array $eventTransfers): void;
}
