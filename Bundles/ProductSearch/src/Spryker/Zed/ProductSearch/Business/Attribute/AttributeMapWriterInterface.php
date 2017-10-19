<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Business\Attribute;

use Generated\Shared\Transfer\ProductSearchPreferencesTransfer;

interface AttributeMapWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductSearchPreferencesTransfer $productSearchPreferencesTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSearchPreferencesTransfer
     */
    public function create(ProductSearchPreferencesTransfer $productSearchPreferencesTransfer);

    /**
     * @param \Generated\Shared\Transfer\ProductSearchPreferencesTransfer $productSearchPreferencesTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSearchPreferencesTransfer
     */
    public function update(ProductSearchPreferencesTransfer $productSearchPreferencesTransfer);

    /**
     * @param \Generated\Shared\Transfer\ProductSearchPreferencesTransfer $productSearchPreferencesTransfer
     *
     * @return void
     */
    public function clean(ProductSearchPreferencesTransfer $productSearchPreferencesTransfer);
}
