<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Collector\Code\KeyBuilder;

use SprykerEngine\Shared\Kernel\Store;

trait KeyBuilderTrait
{

    /**
     * @var string
     */
    protected $keySeparator = '.';

    /**
     * @param mixed $data
     * @param string $localeName
     *
     * @return string
     */
    public function generateKey($data, $localeName)
    {
        $keyParts = [
            Store::getInstance()->getStoreName(),
            $localeName,
            $this->getBundleName(),
            $this->buildKey($data),
        ];

        return $this->escapeKey(implode($this->keySeparator, $keyParts));
    }

    /**
     * @return string
     */
    abstract public function getBundleName();

    /**
     * @param mixed $data
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

}
