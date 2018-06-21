<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Serialize;

use Spryker\Glue\GlueApplication\Rest\Request\Data\MetadataInterface;
use Spryker\Glue\GlueApplication\Rest\Serialize\Encoder\EncoderInterface;

interface EncoderMatcherInterface
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\MetadataInterface $metadata
     *
     * @return null|\Spryker\Glue\GlueApplication\Rest\Serialize\Encoder\EncoderInterface
     */
    public function match(MetadataInterface $metadata): ?EncoderInterface;
}
