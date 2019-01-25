<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilEncoding\FormatEncoder;

interface FormatEncoderInterface
{
    /**
     * @return string
     */
    public function getFormatName(): string;

    /**
     * @param array $data
     *
     * @return string|null
     */
    public function encode(array $data): ?string;
}
