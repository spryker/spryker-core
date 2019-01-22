<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Transfer;

use Codeception\Actor;
use Codeception\Util\Stub;
use Spryker\Zed\Transfer\Business\TransferBusinessFactory;
use Spryker\Zed\Transfer\TransferConfig;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class TransferBusinessTester extends Actor
{
    use _generated\TransferBusinessTesterActions {
        getFacade as getTransferFacade;
    }

    protected const TRANSFER_DESTINATION_DIR = 'Transfers';

    /**
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade|\Spryker\Zed\Transfer\Business\TransferFacade
     */
    public function getFacade()
    {
        $facade = $this->getTransferFacade();
        $facade->setFactory($this->getTransferBusinessFactory());

        return $facade;
    }

    /**
     * @return string
     */
    public function getTransferDestinationDir(): string
    {
        return static::TRANSFER_DESTINATION_DIR;
    }

    /**
     * @return string
     */
    protected function getTransferDestinationUrl(): string
    {
        return $this->getVirtualDirectory() . $this->getTransferDestinationDir() . DIRECTORY_SEPARATOR;
    }

    /**
     * @return object|\Spryker\Zed\Transfer\TransferConfig
     */
    protected function getConfigMock()
    {
        $configMock = Stub::make(TransferConfig::class, [
            'getClassTargetDirectory' => function () {
                return $this->getTransferDestinationUrl();
            },
        ]);

        return $configMock;
    }

    /**
     * @return \Spryker\Zed\Transfer\Business\TransferBusinessFactory
     */
    protected function getTransferBusinessFactory(): TransferBusinessFactory
    {
        $factory = new TransferBusinessFactory();
        $factory->setConfig($this->getConfigMock());

        return $factory;
    }
}
