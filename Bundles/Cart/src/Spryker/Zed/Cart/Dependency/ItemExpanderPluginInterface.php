<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Cart\Dependency;

use Generated\Shared\Transfer\ChangeTransfer;

interface ItemExpanderPluginInterface
{

    /**
     * @param \Generated\Shared\Transfer\ChangeTransfer $change
     *
     * @return \Generated\Shared\Transfer\ChangeTransfer
     */
    public function expandItems(ChangeTransfer $change);

}
