<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\Testify\Helper;

use Codeception\Module;
use Codeception\TestInterface;
use SprykerTest\Shared\Propel\Helper\TransactionHelper as SharedTransactionHelper;

class TransactionHelper extends SharedTransactionHelper
{
    /**
     * Preventing opening transaction
     *
     * @inheritDoc
     */
    public function _before(TestInterface $test): void
    {
        Module::_before($test);
    }

    /**
     * Preventing to rollback transaction
     *
     * @inheritDoc
     */
    public function _after(TestInterface $test): void
    {
        Module::_after($test);
    }
}
