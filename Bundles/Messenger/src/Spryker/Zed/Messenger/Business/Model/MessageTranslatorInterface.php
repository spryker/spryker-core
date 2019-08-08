<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Messenger\Business\Model;

interface MessageTranslatorInterface
{
    /**
     * @param string $keyName
     * @param array $data
     *
     * @return string
     */
    public function translate(string $keyName, array $data = []): string;
}
