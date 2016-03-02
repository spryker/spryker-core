<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cart\Business\Operator;

use Generated\Shared\Transfer\ChangeTransfer;
use Spryker\Zed\Cart\Dependency\ItemExpanderPluginInterface;

interface OperatorInterface
{

    /**
     * @param \Generated\Shared\Transfer\ChangeTransfer $cartChange
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function executeOperation(ChangeTransfer $cartChange);

    /**
     * @param \Spryker\Zed\Cart\Dependency\ItemExpanderPluginInterface $itemExpander
     *
     * @return void
     */
    public function addItemExpanderPlugin(ItemExpanderPluginInterface $itemExpander);

}
