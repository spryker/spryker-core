<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinuedNote;

use Generated\Shared\Transfer\ProductDiscontinuedNoteResponseTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedNoteTransfer;

interface ProductDiscontinuedNoteWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedNoteTransfer $discontinuedNoteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedNoteResponseTransfer
     */
    public function saveNote(
        ProductDiscontinuedNoteTransfer $discontinuedNoteTransfer
    ): ProductDiscontinuedNoteResponseTransfer;
}
