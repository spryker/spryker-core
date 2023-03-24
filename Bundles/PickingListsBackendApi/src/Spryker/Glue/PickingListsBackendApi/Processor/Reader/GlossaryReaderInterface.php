<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsBackendApi\Processor\Reader;

use Generated\Shared\Transfer\GlueResponseTransfer;

interface GlossaryReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponseTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function translateGlueResponseTransfer(
        GlueResponseTransfer $glueResponseTransfer,
        string $localeName
    ): GlueResponseTransfer;
}
