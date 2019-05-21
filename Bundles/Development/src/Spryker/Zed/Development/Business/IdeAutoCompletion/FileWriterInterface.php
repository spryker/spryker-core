<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\IdeAutoCompletion;

interface FileWriterInterface
{
    /**
     * @param string $fileName
     * @param string $fileContent
     * @param array $options
     *
     * @return void
     */
    public function writeFile(string $fileName, string $fileContent, array $options);
}
