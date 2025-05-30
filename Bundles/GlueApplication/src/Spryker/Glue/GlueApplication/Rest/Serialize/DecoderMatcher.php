<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Serialize;

use Spryker\Glue\GlueApplication\Rest\Request\Data\MetadataInterface;
use Spryker\Glue\GlueApplication\Serialize\Decoder\DecoderInterface;

class DecoderMatcher implements DecoderMatcherInterface
{
    /**
     * @var string
     */
    public const DEFAULT_FORMAT = 'json';

    /**
     * @var array<\Spryker\Glue\GlueApplication\Serialize\Decoder\DecoderInterface>
     */
    protected $decoders;

    /**
     * @param array $decoders
     */
    public function __construct(array $decoders = [])
    {
        $this->decoders = $decoders;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\MetadataInterface $metadata
     *
     * @return \Spryker\Glue\GlueApplication\Serialize\Decoder\DecoderInterface|null
     */
    public function match(MetadataInterface $metadata): ?DecoderInterface
    {
        if (!$metadata->getContentTypeFormat()) {
            return $this->decoders[static::DEFAULT_FORMAT];
        }

        foreach ($this->decoders as $format => $decoder) {
            if (strcasecmp($format, $metadata->getContentTypeFormat()) === 0) {
                return $decoder;
            }
        }

        return null;
    }
}
