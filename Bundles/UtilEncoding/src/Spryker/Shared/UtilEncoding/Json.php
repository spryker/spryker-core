<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\UtilEncoding;

class Json implements JsonInterface
{

    const DEFAULT_OPTIONS = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_ERROR_INF_OR_NAN | JSON_PARTIAL_OUTPUT_ON_ERROR;
    const DEFAULT_DEPTH = 512;

    /**
     * @param mixed $value
     * @param int $options
     * @param int $depth
     *
     * @throws \Exception
     *
     * @return string
     */
    public function encode($value, $options = self::DEFAULT_OPTIONS, $depth = self::DEFAULT_DEPTH)
    {
        if ($options === null) {
            $options = static::DEFAULT_OPTIONS;
        }

        if ($depth === null) {
            $options = static::DEFAULT_DEPTH;
        }

        return json_encode($value, $options, $depth);
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
    public function decode($jsonString, $assoc = false, $depth = self::DEFAULT_DEPTH, $options = self::DEFAULT_OPTIONS)
    {
        if ($options === null) {
            $options = static::DEFAULT_OPTIONS;
        }

        if ($depth === null) {
            $depth = static::DEFAULT_DEPTH;
        }

        return json_decode($jsonString, $assoc, $depth, $options);
    }

}
