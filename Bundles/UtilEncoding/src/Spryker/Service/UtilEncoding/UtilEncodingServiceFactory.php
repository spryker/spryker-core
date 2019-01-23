<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilEncoding;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\UtilEncoding\Decoder\Decoder;
use Spryker\Service\UtilEncoding\Decoder\DecoderInterface;
use Spryker\Service\UtilEncoding\Encoder\Encoder;
use Spryker\Service\UtilEncoding\Encoder\EncoderInterface;
use Spryker\Service\UtilEncoding\FormatDecoder\FormatDecoderInterface;
use Spryker\Service\UtilEncoding\FormatDecoder\JsonFormatDecoder;
use Spryker\Service\UtilEncoding\FormatDecoder\QueryStringFormatDecoder;
use Spryker\Service\UtilEncoding\FormatEncoder\FormatEncoderInterface;
use Spryker\Service\UtilEncoding\FormatEncoder\JsonFormatEncoder;
use Spryker\Service\UtilEncoding\Model\Json;

class UtilEncodingServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\UtilEncoding\Model\JsonInterface
     */
    public function createJsonEncoder()
    {
        return new Json();
    }

    /**
     * @return \Spryker\Service\UtilEncoding\Encoder\EncoderInterface
     */
    public function createEncoder(): EncoderInterface
    {
        return new Encoder(
            $this->getFormatEncoders()
        );
    }

    /**
     * @return \Spryker\Service\UtilEncoding\Decoder\DecoderInterface
     */
    public function createDecoder(): DecoderInterface
    {
        return new Decoder(
            $this->getFormatDecoders()
        );
    }

    /**
     * @return \Spryker\Service\UtilEncoding\FormatEncoder\FormatEncoderInterface[]
     */
    public function getFormatEncoders(): array
    {
        return [
            $this->createJsonFormatEncoder(),
        ];
    }

    /**
     * @return \Spryker\Service\UtilEncoding\FormatDecoder\FormatDecoderInterface[]
     */
    public function getFormatDecoders(): array
    {
        return [
            $this->createJsonFormatDecoder(),
            $this->createQueryStringFormatDecoder(),
        ];
    }

    /**
     * @return \Spryker\Service\UtilEncoding\FormatEncoder\FormatEncoderInterface
     */
    public function createJsonFormatEncoder(): FormatEncoderInterface
    {
        return new JsonFormatEncoder(
            $this->createJsonEncoder()
        );
    }

    /**
     * @return \Spryker\Service\UtilEncoding\FormatDecoder\FormatDecoderInterface
     */
    public function createJsonFormatDecoder(): FormatDecoderInterface
    {
        return new JsonFormatDecoder(
            $this->createJsonEncoder()
        );
    }

    /**
     * @return \Spryker\Service\UtilEncoding\FormatDecoder\FormatDecoderInterface
     */
    public function createQueryStringFormatDecoder(): FormatDecoderInterface
    {
        return new QueryStringFormatDecoder();
    }
}
