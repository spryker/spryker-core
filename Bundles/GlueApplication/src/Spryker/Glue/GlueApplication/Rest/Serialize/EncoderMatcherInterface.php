<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Serialize;

use Spryker\Glue\GlueApplication\Rest\Request\Data\MetadataInterface;
use Spryker\Glue\GlueApplication\Serialize\Encoder\EncoderInterface;

interface EncoderMatcherInterface
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\MetadataInterface $metadata
     *
     * @return \Spryker\Glue\GlueApplication\Serialize\Encoder\EncoderInterface|null
     */
    public function match(MetadataInterface $metadata): ?EncoderInterface;
}
