<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Publisher;

use Codeception\Actor;
use Codeception\Configuration;
use Spryker\Zed\Kernel\Business\AbstractFacade;

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
class PublisherBusinessTester extends Actor
{
    use _generated\PublisherBusinessTesterActions;

    protected const BUSINESS_FACADE_CLASS_NAME_PATTERN = '\%1$s\%2$s\%3$s\Business\%3$sFacade';

    /**
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade|\Spryker\Zed\Publisher\Business\PublisherFacadeInterface
     */
    public function getFacade()
    {
        $facade = $this->createFacade();
        $facade->setFactory($this->getFactory());

        return $facade;
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected function createFacade(): AbstractFacade
    {
        $className = $this->getFacadeClassName();

        return new $className();
    }

    /**
     * @return string
     */
    protected function getFacadeClassName(): string
    {
        $config = Configuration::config();
        $namespaceParts = explode('\\', $config['namespace']);

        return sprintf(static::BUSINESS_FACADE_CLASS_NAME_PATTERN, rtrim($namespaceParts[0], 'Test'), $namespaceParts[1], $namespaceParts[2]);
    }
}
