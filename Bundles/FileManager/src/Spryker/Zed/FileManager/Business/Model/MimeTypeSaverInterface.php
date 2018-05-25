<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\Model;

use Generated\Shared\Transfer\MimeTypeCollectionTransfer;

interface MimeTypeSaverInterface
{
    /**
     * @param \Generated\Shared\Transfer\MimeTypeCollectionTransfer $mimeTypeCollectionTransfer
     *
     * @return void
     */
    public function updateIsAllowed(MimeTypeCollectionTransfer $mimeTypeCollectionTransfer);
}
