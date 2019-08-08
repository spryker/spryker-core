<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilEncoding\FormatDecoder;

use Spryker\Service\UtilEncoding\Model\JsonInterface;

class JsonFormatDecoder implements FormatDecoderInterface
{
    /**
     * @var \Spryker\Service\UtilEncoding\Model\JsonInterface
     */
    protected $json;

    /**
     * @param \Spryker\Service\UtilEncoding\Model\JsonInterface $json
     */
    public function __construct(JsonInterface $json)
    {
        $this->json = $json;
    }

    /**
     * @return string
     */
    public function getFormatName(): string
    {
        return 'json';
    }

    /**
     * @param string $data
     *
     * @return array|null
     */
    public function decode(string $data): ?array
    {
        return $this->json->decode($data, true);
    }
}
