<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductRelationStorage\Relation;

use Generated\Shared\Transfer\QuoteTransfer;

interface UpSellingProductReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    public function findUpSellingProducts(QuoteTransfer $quoteTransfer, $localeName);

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int[]
     */
    public function findUpSellingAbstractProductIds(QuoteTransfer $quoteTransfer): array;
}
