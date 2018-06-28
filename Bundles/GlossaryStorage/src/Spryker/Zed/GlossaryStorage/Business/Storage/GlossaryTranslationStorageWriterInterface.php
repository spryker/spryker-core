<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GlossaryStorage\Business\Storage;

interface GlossaryTranslationStorageWriterInterface
{
    /**
     * @param array $glossaryKeyIds
     *
     * @return void
     */
    public function publish(array $glossaryKeyIds);

    /**
     * @param array $glossaryKeyIds
     *
     * @return void
     */
    public function unpublish(array $glossaryKeyIds);
}
