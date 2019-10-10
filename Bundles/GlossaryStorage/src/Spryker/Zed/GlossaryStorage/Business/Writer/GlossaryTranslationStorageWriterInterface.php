<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GlossaryStorage\Business\Writer;

interface GlossaryTranslationStorageWriterInterface
{
    /**
     * @deprecated Use `\Spryker\Zed\GlossaryStorage\Business\Writer\GlossaryTranslationStorageWriterInterface::writeGlossaryStorageCollectionByGlossaryKeyEvents()` instead
     *
     * @param array $glossaryKeyIds
     *
     * @return void
     */
    public function publish(array $glossaryKeyIds);

    /**
     * @param array $eventTransfers
     *
     * @return void
     */
    public function writeGlossaryStorageCollectionByGlossaryKeyEvents(array $eventTransfers);

    /**
     * @param array $eventTransfers
     *
     * @return void
     */
    public function writeGlossaryStorageCollectionByGlossaryTranslationEvents(array $eventTransfers);
}
