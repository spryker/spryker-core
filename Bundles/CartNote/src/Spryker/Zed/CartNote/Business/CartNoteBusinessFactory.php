<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartNote\Business;

use Spryker\Zed\CartNote\Business\Model\CartNoteSaver;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CartNote\Persistence\CartNoteEntityManagerInterface getEntityManager()
 */
class CartNoteBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CartNote\Business\Model\CartNoteSaverInterface
     */
    public function createCartNotesSaver()
    {
        return new CartNoteSaver($this->getEntityManager());
    }
}
