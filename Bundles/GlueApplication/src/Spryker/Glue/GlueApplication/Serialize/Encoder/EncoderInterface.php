<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Serialize\Encoder;

/**
 * @deprecated Will be removed without replacement.
 */
interface EncoderInterface
{
    /**
     * @param array $data
     *
     * @return string
     */
    public function encode(array $data): string;
}
