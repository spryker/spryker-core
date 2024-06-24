<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\RuleEngine;

use ArrayObject;
use Codeception\Actor;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerTest\Shared\Kernel\Transfer\Fixtures\AbstractTransfer;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 * @method \Spryker\Zed\RuleEngine\Business\RuleEngineFacadeInterface getFacade(?string $moduleName = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class RuleEngineBusinessTester extends Actor
{
    use _generated\RuleEngineBusinessTesterActions;

    /**
     * @param string $testFieldValue
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function createTestItemTransfer(string $testFieldValue): TransferInterface
    {
        return new class ($testFieldValue) extends AbstractTransfer implements TransferInterface {
            /**
             * @var string
             */
            protected string $testField;

            /**
             * @param string $testFieldValue
             */
            public function __construct(string $testFieldValue)
            {
                parent::__construct();

                $this->testField = $testFieldValue;
            }

            /**
             * @return string
             */
            public function getTestField(): string
            {
                return $this->testField;
            }
        };
    }

    /**
     * @param \ArrayObject<array-key, \Spryker\Shared\Kernel\Transfer\TransferInterface> $collectableItemTransfers
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function createCollectableItemCollectionTransfer(ArrayObject $collectableItemTransfers): TransferInterface
    {
        return new class ($collectableItemTransfers) extends AbstractTransfer implements TransferInterface {
            /**
             * @var \ArrayObject<array-key, \Spryker\Shared\Kernel\Transfer\TransferInterface>
             */
            protected ArrayObject $collectableItemTransfers;

            /**
             * @param \ArrayObject<array-key, \Spryker\Shared\Kernel\Transfer\TransferInterface> $collectableItemTransfers
             */
            public function __construct(ArrayObject $collectableItemTransfers)
            {
                parent::__construct();

                $this->collectableItemTransfers = $collectableItemTransfers;
            }

            /**
             * @return \ArrayObject<array-key, \Spryker\Shared\Kernel\Transfer\TransferInterface>
             */
            public function getCollectableItemTransfers(): ArrayObject
            {
                return $this->collectableItemTransfers;
            }
        };
    }
}
