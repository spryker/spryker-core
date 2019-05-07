<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCartShare\ResourceShare;

use Generated\Shared\Transfer\ResourceShareRequestTransfer;

interface ResourceShareRequestBuilderInterface
{
    /**
     * @param int $idQuote
     * @param string $shareOption
     *
     * @return \Generated\Shared\Transfer\ResourceShareRequestTransfer
     */
    public function buildResourceShareRequest(int $idQuote, string $shareOption): ResourceShareRequestTransfer;
}
