<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DataImport;

use Codeception\Actor;
use Generated\Shared\Transfer\EventEntityTransfer;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(\SprykerTest\Zed\DataImport\PHPMD)
 */
class DataImportBusinessTester extends Actor
{
    use _generated\DataImportBusinessTesterActions;

    /**
     * @var int
     */
    public const TEST_ENTITY_ID_1 = 1;

    /**
     * @var int
     */
    public const TEST_ENTITY_ID_2 = 2;

    /**
     * @var string
     */
    public const TEST_EVENT_NAME = 'TestEvent.entity.publish';

    /**
     * @var string
     */
    protected const TEST_EVENT_ENTITY_NAME = 'foo';

    /**
     * @var int
     */
    protected const TEST_EVENT_ENTITY_FOREIGN_KEY = 2;

    /**
     * @var string
     */
    protected const TEST_EVENT_MODIFIED_COLUMN = 'bar';

    /**
     * @return \Generated\Shared\Transfer\EventEntityTransfer
     */
    public function createRegularEventEntityTransfer(): EventEntityTransfer
    {
        return (new EventEntityTransfer())->setId(static::TEST_ENTITY_ID_1);
    }

    /**
     * @return \Generated\Shared\Transfer\EventEntityTransfer
     */
    public function createExtendedEventEntityTransfer(): EventEntityTransfer
    {
        return (new EventEntityTransfer())
            ->setId(static::TEST_ENTITY_ID_2)
            ->setEvent(static::TEST_EVENT_NAME)
            ->setName(static::TEST_EVENT_ENTITY_NAME)
            ->setForeignKeys([static::TEST_EVENT_ENTITY_FOREIGN_KEY])
            ->setModifiedColumns([static::TEST_EVENT_MODIFIED_COLUMN]);
    }
}
