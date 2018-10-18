<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinuedNote;

use Generated\Shared\Transfer\ProductDiscontinuedNoteResponseTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedNoteTransfer;
use Spryker\Zed\ProductDiscontinued\Persistence\ProductDiscontinuedEntityManagerInterface;

class ProductDiscontinuedNoteWriter implements ProductDiscontinuedNoteWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductDiscontinued\Persistence\ProductDiscontinuedEntityManagerInterface
     */
    protected $productDiscontinuedEntityManager;

    /**
     * @param \Spryker\Zed\ProductDiscontinued\Persistence\ProductDiscontinuedEntityManagerInterface $productDiscontinuedEntityManager
     */
    public function __construct(
        ProductDiscontinuedEntityManagerInterface $productDiscontinuedEntityManager
    ) {
        $this->productDiscontinuedEntityManager = $productDiscontinuedEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedNoteTransfer $discontinuedNoteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedNoteResponseTransfer
     */
    public function saveNote(
        ProductDiscontinuedNoteTransfer $discontinuedNoteTransfer
    ): ProductDiscontinuedNoteResponseTransfer {
        $discontinuedNoteTransfer = $this->productDiscontinuedEntityManager->saveProductDiscontinuedNote($discontinuedNoteTransfer);

        return (new ProductDiscontinuedNoteResponseTransfer())
            ->setProductDiscontinuedNote($discontinuedNoteTransfer)
            ->setIsSuccessful((bool)$discontinuedNoteTransfer->getIdProductDiscontinuedNote());
    }
}
