<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerNote\Persistence;

use Orm\Zed\CustomerNote\Persistence\SpyCustomerNoteQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

class CustomerNotePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\CustomerNote\Persistence\SpyCustomerNoteQuery
     */
    public function createCustomerNoteQuery(): SpyCustomerNoteQuery
    {
        return SpyCustomerNoteQuery::create();
    }
}
