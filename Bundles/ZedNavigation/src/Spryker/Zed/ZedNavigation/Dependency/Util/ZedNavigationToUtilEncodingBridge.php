<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedNavigation\Dependency\Util;

class ZedNavigationToUtilEncodingBridge implements ZedNavigationToUtilEncodingInterface
{
    /**
     * @var \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface $utilEncoding
     */
    public function __construct($utilEncoding)
    {
        $this->utilEncodingService = $utilEncoding;
    }

    /**
     * @param array<mixed> $value
     * @param int|null $options
     * @param int|null $depth
     *
     * @return string|null
     */
    public function encodeJson($value, $options = null, $depth = null)
    {
        return $this->utilEncodingService->encodeJson($value, $options, $depth);
    }

    /**
     * @param string $jsonValue
     * @param bool $assoc Deprecated: `false` is deprecated, always use `true` for array return.
     * @param int|null $depth
     * @param int|null $options
     *
     * @return array<mixed>|null
     */
    public function decodeJson($jsonValue, $assoc = false, $depth = null, $options = null)
    {
        if ($assoc === false) {
            trigger_error('Param #2 `$assoc` must be `true` as return of type `object` is not accepted.', E_USER_DEPRECATED);
        }

        /** @phpstan-var array<mixed>|null */
        return $this->utilEncodingService->decodeJson($jsonValue, $assoc, $depth, $options);
    }
}
