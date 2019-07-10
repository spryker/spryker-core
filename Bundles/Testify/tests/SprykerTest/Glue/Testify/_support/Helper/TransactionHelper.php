<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
     * @inheritdoc
     */
    public function _before(TestInterface $test): void
    {
        Module::_before($test);
    }

    /**
     * Preventing to rollback transaction
     *
     * @inheritdoc
     */
    public function _after(TestInterface $test): void
    {
        Module::_after($test);
    }
}
