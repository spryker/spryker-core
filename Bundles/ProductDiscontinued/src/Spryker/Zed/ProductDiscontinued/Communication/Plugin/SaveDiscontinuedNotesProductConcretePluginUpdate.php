<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinued\Communication\Plugin;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginUpdateInterface;

/**
 * @method \Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinuedFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductDiscontinued\Communication\ProductDiscontinuedCommunicationFactory getFactory()
 */
class SaveDiscontinuedNotesProductConcretePluginUpdate extends AbstractPlugin implements ProductConcretePluginUpdateInterface
{
    /**
     * Specification:
     * - Saves discontinued notes on product concrete save.
     *
     * @api
     *
     * @see \Spryker\Zed\Product\ProductDependencyProvider
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function update(ProductConcreteTransfer $productConcreteTransfer)
    {
        if (!$productConcreteTransfer->getDiscontinuedNotes()) {
            return $productConcreteTransfer;
        }
        foreach ($productConcreteTransfer->getDiscontinuedNotes() as $discontinuedNoteTransfer) {
            if ($discontinuedNoteTransfer->getNote()) {
                $this->getFacade()->saveDiscontinuedNote($discontinuedNoteTransfer);
            }
        }

        return $productConcreteTransfer;
    }
}
