<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartNotes\Business;

use Spryker\Zed\CartNotes\Business\Model\CartNoteSaver;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CartNotes\Persistence\CartNotesEntityManagerInterface getEntityManager()
 */
class CartNotesBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CartNotes\Business\Model\CartNoteSaverInterface
     */
    public function createCartNotesSaver()
    {
        return new CartNoteSaver($this->getEntityManager());
    }
}
