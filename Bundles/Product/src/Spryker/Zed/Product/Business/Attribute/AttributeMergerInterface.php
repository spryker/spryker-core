<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Attribute;

use Generated\Shared\Transfer\RawProductAttributesTransfer;

interface AttributeMergerInterface
{
    /**
     * @param \Generated\Shared\Transfer\RawProductAttributesTransfer $rawProductAttributesTransfer
     *
     * @return array
     */
    public function merge(RawProductAttributesTransfer $rawProductAttributesTransfer);
}
