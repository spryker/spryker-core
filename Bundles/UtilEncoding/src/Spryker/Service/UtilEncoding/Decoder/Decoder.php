<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilEncoding\Decoder;

use Spryker\Service\UtilEncoding\Exception\FormatNotSupportedException;

class Decoder implements DecoderInterface
{
    /**
     * @var \Spryker\Service\UtilEncoding\FormatDecoder\FormatDecoderInterface[]
     */
    protected $formatDecoders;

    /**
     * @param \Spryker\Service\UtilEncoding\FormatDecoder\FormatDecoderInterface[] $formatDecoders
     */
    public function __construct(array $formatDecoders)
    {
        $this->formatDecoders = $formatDecoders;
    }

    /**
     * @param string $data
     * @param string $formatName
     *
     * @throws \Spryker\Service\UtilEncoding\Exception\FormatNotSupportedException
     *
     * @return array|null
     */
    public function decodeFromFormat(string $data, string $formatName): ?array
    {
        foreach ($this->formatDecoders as $formatDecoder) {
            if ($formatDecoder->getFormatName() === $formatName) {
                return $formatDecoder->decode($data);
            }
        }

        throw new FormatNotSupportedException();
    }
}
