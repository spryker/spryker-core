<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilEncoding\FormatDecoder;

interface FormatDecoderInterface
{
    /**
     * @return string
     */
    public function getFormatName(): string;

    /**
     * @param string $data
     *
     * @return array|null
     */
    public function decode(string $data): ?array;
}
