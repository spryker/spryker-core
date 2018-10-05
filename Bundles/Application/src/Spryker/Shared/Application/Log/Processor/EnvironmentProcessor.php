<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Application\Log\Processor;

use Spryker\Shared\Kernel\Store;

/**
 * @deprecated Use `EnvironmentProcessor` of Log module instead.
 */
class EnvironmentProcessor
{
    public const EXTRA = 'environment';

    public const APPLICATION = 'application';
    public const ENVIRONMENT = 'environment';
    public const STORE = 'store';
    public const LOCALE = 'locale';
    public const RECORD_EXTRA = 'extra';

    /**
     * @param array $record
     *
     * @return array
     */
    public function __invoke(array $record)
    {
        $record[static::RECORD_EXTRA][static::EXTRA] = $this->getData();

        return $record;
    }

    /**
     * @return array
     */
    protected function getData()
    {
        $store = $this->getStore();

        return [
            static::APPLICATION => APPLICATION,
            static::ENVIRONMENT => APPLICATION_ENV,
            static::STORE => $store->getStoreName(),
            static::LOCALE => $store->getCurrentLocale(),
        ];
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    protected function getStore()
    {
        return Store::getInstance();
    }
}
