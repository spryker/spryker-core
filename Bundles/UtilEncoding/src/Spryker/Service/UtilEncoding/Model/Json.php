<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilEncoding\Model;

class Json implements JsonInterface
{
    public const DEFAULT_OPTIONS = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_PARTIAL_OUTPUT_ON_ERROR;
    public const DEFAULT_DEPTH = 512;

    /**
     * @param mixed $value
     * @param int|null $options
     * @param int|null $depth
     *
     * @return string|null
     */
    public function encode($value, $options = null, $depth = null)
    {
        if ($options === null) {
            $options = static::DEFAULT_OPTIONS;
        }

        if ($depth === null) {
            $depth = static::DEFAULT_DEPTH;
        }

        $value = json_encode($value, $options, $depth);

        return $value !== false ? $value : null;
    }

    /**
     * @param string $jsonString
     * @param bool $assoc
     * @param int|null $depth
     * @param int|null $options
     *
     * @return mixed|null
     */
    public function decode($jsonString, $assoc = false, $depth = null, $options = null)
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
