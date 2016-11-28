<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Library;

use \Spryker\Service\UtilEncoding\Model\Json AS JsonEncoder;

/**
 * @deprecated Use \Spryker\Service\UtilEncoding\Model\Json instead.
 */
class Json
{

    const DEFAULT_OPTIONS = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT;
    const DEFAULT_DEPTH = 512;

    /**
     * @var \Spryker\Service\UtilEncoding\Model\Json
     */
    protected static $utilJsonEncoder = null;

    /**
     * @param mixed $value
     * @param int $options
     * @param int $depth
     *
     * @throws \Exception
     *
     * @return string
     */
    public static function encode($value, $options = self::DEFAULT_OPTIONS, $depth = self::DEFAULT_DEPTH)
    {
        return static::createUtilJsonEncoder()->encode($value, $options, $depth);
    }

    /**
     * @param string $jsonString
     * @param bool $assoc
     * @param int $depth
     * @param int $options
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public static function decode($jsonString, $assoc = false, $depth = self::DEFAULT_DEPTH, $options = self::DEFAULT_OPTIONS)
    {
        return static::createUtilJsonEncoder()->decode($jsonString, $assoc, $depth, $options);
    }

    /**
     * @return \Spryker\Service\UtilEncoding\Model\Json
     */
    protected static function createUtilJsonEncoder()
    {
        if (static::$utilJsonEncoder === null) {
            static::$utilJsonEncoder = new JsonEncoder();
        }

        return static::$utilJsonEncoder;
    }

}
