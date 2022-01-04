<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\KeyBuilder;

use Spryker\Shared\Kernel\Store;

trait KeyBuilderTrait
{
    /**
     * @var string
     */
    protected $keySeparator = '.';

    /**
     * @param mixed $data
     * @param string $localeName
     * @param string|null $storeName
     *
     * @return string
     */
    public function generateKey($data, $localeName, ?string $storeName = null): string
    {
        $keyParts = $this->getKeyParts($data, $localeName, $storeName);

        return $this->escapeKey(implode($this->keySeparator, $keyParts));
    }

    /**
     * @return string
     */
    abstract public function getBundleName();

    /**
     * @param mixed $data
     * @param string $localeName
     * @param string|null $storeName
     *
     * @return array
     */
    protected function getKeyParts($data, $localeName, ?string $storeName)
    {
        return [
            $this->resolveStoreName($storeName),
            $localeName,
            $this->getBundleName(),
            $this->buildKey($data),
        ];
    }

    /**
     * @param string $data
     *
     * @return string
     */
    abstract protected function buildKey($data);

    /**
     * @param string $key
     *
     * @return string
     */
    protected function escapeKey($key)
    {
        $charsToReplace = ['"', "'", ' ', "\0", "\n", "\r"];

        return str_replace($charsToReplace, '-', mb_strtolower(trim($key)));
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param string|null $storeName
     *
     * @return string
     */
    protected function resolveStoreName(?string $storeName): string
    {
        return $storeName ?? Store::getInstance()->getStoreName();
    }
}
