<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Serialize\Decoder;

interface DecoderInterface
{
    /**
     * @param string $data
     *
     * @return array
     */
    public function decode($data): array;
}
