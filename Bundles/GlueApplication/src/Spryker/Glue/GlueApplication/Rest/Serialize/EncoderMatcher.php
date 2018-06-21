<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Serialize;

use Spryker\Glue\GlueApplication\Rest\Request\Data\MetadataInterface;
use Spryker\Glue\GlueApplication\Rest\Serialize\Encoder\EncoderInterface;

class EncoderMatcher implements EncoderMatcherInterface
{
    /**
     * @var string
     */
    public const DEFAULT_FORMAT = 'json';

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\Serialize\Encoder\EncoderInterface[]
     */
    protected $encoders;

    /**
     * @param array $encoders
     */
    public function __construct(array $encoders = [])
    {
        $this->encoders = $encoders;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\MetadataInterface $metadata
     *
     * @return null|\Spryker\Glue\GlueApplication\Rest\Serialize\Encoder\EncoderInterface
     */
    public function match(MetadataInterface $metadata): ?EncoderInterface
    {
        if (!$metadata->getAcceptFormat()) {
            return $this->encoders[static::DEFAULT_FORMAT];
        }

        foreach ($this->encoders as $format => $encoder) {
            if (strcasecmp($format, $metadata->getAcceptFormat()) === 0) {
                return $encoder;
            }
        }

        return null;
    }
}
