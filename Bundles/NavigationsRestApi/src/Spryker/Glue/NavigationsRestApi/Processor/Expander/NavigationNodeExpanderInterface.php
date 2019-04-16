<?php

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
