<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueApplication;

use Codeception\Actor;
use Generated\Shared\Transfer\ApiControllerConfigurationTransfer;

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
 * @SuppressWarnings(PHPMD)
 */
class GlueApplicationTester extends Actor
{
    use _generated\GlueApplicationTesterActions;

    /**
     * @var string
     */
    protected const FAKE_APPLICATION = 'FAKE_APPLICATION';

    /**
     * @var string
     */
    protected const FAKE_CONTROLLER = 'FAKE_CONTROLLER';

    /**
     * @var string
     */
    protected const FALE_METHOD = 'FAKE_METHOD';

    /**
     * @var string
     */
    protected const FAKE_PATH = 'FAKE_PATH';

    /**
     * @return array<string, array<string, \Generated\Shared\Transfer\ApiControllerConfigurationTransfer>>
     */
    public function haveApiControllerConfigurationTransfers(): array
    {
        return [
            static::FAKE_APPLICATION => [
                sprintf('%s:%s:%s', static::FAKE_CONTROLLER, static::FAKE_PATH, static::FALE_METHOD) => [
                    (new ApiControllerConfigurationTransfer())
                        ->setApiApplication(static::FAKE_APPLICATION)
                        ->setController(static::FAKE_CONTROLLER)
                        ->setMethod(static::FALE_METHOD)
                        ->setPath(static::FAKE_PATH)
                        ->setParameters([]),
                ],
            ],
        ];
    }
}
