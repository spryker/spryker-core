<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Log\Processor;

use Spryker\Shared\Kernel\Store;

class EnvironmentProcessor implements ProcessorInterface
{
    /**
     * @var string
     */
    public const EXTRA = 'environment';

    /**
     * @var string
     */
    public const APPLICATION = 'application';

    /**
     * @var string
     */
    public const ENVIRONMENT = 'environment';

    /**
     * @deprecated Will be removed in the next major without replacement.
     *
     * @var string
     */
    public const STORE = 'store';

    /**
     * @var string
     */
    protected const CODE_BUCKET = 'codeBucket';

    /**
     * @var string
     */
    public const LOCALE = 'locale';

    /**
     * @var string
     */
    public const RECORD_EXTRA = 'extra';

    /**
     * @var string|null
     */
    protected $currentLocale;

    /**
     * @param string|null $currentLocale
     */
    public function __construct(?string $currentLocale = null)
    {
        $this->currentLocale = $currentLocale;
    }

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
        return [
            static::APPLICATION => APPLICATION,
            static::ENVIRONMENT => APPLICATION_ENV,
            static::STORE => $this->getStoreName(),
            static::CODE_BUCKET => APPLICATION_CODE_BUCKET,
            static::LOCALE => $this->currentLocale ?? $this->getStore()->getCurrentLocale(),
        ];
    }

    /**
     * @deprecated Will be removed in the next major without replacement.
     *
     * @return string|null
     */
    protected function getStoreName()
    {
        if (defined('APPLICATION_CODE_BUCKET')) {
            return null;
        }

        return $this->getStore()->getStoreName();
    }

    /**
     * @deprecated Will be removed in the next major without replacement.
     *
     * @return \Spryker\Shared\Kernel\Store
     */
    protected function getStore()
    {
        return Store::getInstance();
    }
}
