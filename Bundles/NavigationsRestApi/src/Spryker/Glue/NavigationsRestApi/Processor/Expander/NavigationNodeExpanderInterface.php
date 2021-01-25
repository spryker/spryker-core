<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\NavigationsRestApi\Processor\Expander;

use Generated\Shared\Transfer\RestNavigationAttributesTransfer;

interface NavigationNodeExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestNavigationAttributesTransfer $restNavigationAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestNavigationAttributesTransfer
     */
    public function expand(RestNavigationAttributesTransfer $restNavigationAttributesTransfer): RestNavigationAttributesTransfer;
}
