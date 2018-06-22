<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinued\Persistence;

use Generated\Shared\Transfer\ProductDiscontinuedNoteTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedTransfer;

interface ProductDiscontinuedEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedTransfer $productDiscontinuedTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedTransfer
     */
    public function saveProductDiscontinued(ProductDiscontinuedTransfer $productDiscontinuedTransfer): ProductDiscontinuedTransfer;

    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedTransfer $productDiscontinuedTransfer
     *
     * @return void
     */
    public function deleteProductDiscontinued(ProductDiscontinuedTransfer $productDiscontinuedTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedNoteTransfer $discontinuedNoteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedNoteTransfer
     */
    public function saveProductDiscontinuedNote(ProductDiscontinuedNoteTransfer $discontinuedNoteTransfer): ProductDiscontinuedNoteTransfer;

    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedTransfer $productDiscontinuedTransfer
     *
     * @return void
     */
    public function deleteProductDiscontinuedNotes(ProductDiscontinuedTransfer $productDiscontinuedTransfer): void;
}
