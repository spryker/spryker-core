<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Serialize;

use Spryker\Glue\GlueApplication\Rest\Request\Data\MetadataInterface;
use Spryker\Glue\GlueApplication\Rest\Serialize\Decoder\DecoderInterface;

interface DecoderMatcherInterface
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\MetadataInterface $metadata
     *
     * @return null|\Spryker\Glue\GlueApplication\Rest\Serialize\Decoder\DecoderInterface
     */
    public function match(MetadataInterface $metadata): ?DecoderInterface;
}
