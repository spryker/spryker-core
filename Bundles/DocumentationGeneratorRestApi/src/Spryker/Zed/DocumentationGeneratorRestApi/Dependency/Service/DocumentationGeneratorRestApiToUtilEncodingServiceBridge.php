<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Dependency\Service;

class DocumentationGeneratorRestApiToUtilEncodingServiceBridge implements DocumentationGeneratorRestApiToUtilEncodingServiceInterface
{
    /**
     * @var \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
     */
    protected $utilEncoding;

    /**
     * @param \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface $utilEncoding
     */
    public function __construct($utilEncoding)
    {
        $this->utilEncoding = $utilEncoding;
    }

    /**
     * @param string $jsonValue
     * @param bool $assoc Deprecated: `false` is deprecated, always use `true` for array return.
     * @param int|null $depth
     * @param int|null $options
     *
     * @return mixed|null
     */
    public function decodeJson(string $jsonValue, bool $assoc = false, ?int $depth = null, ?int $options = null)
    {
        if ($assoc === false) {
            trigger_error('Param #2 `$assoc` must be `true` as return of type `object` is not accepted.', E_USER_DEPRECATED);
        }

        /** @phpstan-var array<mixed>|null */
        return $this->utilEncoding->decodeJson($jsonValue, $assoc, $depth, $options);
    }
}
