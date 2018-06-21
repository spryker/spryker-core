<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Serialize\Encoder;

interface EncoderInterface
{
    /**
     * @param array $data
     *
     * @return string
     */
    public function encode(array $data): string;
}
