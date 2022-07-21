<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Dependency\External;

interface GlueApplicationToSymfonyFilesystemInterface
{
    /**
     * @param string $filename
     * @param string $content
     *
     * @throws \Spryker\Glue\GlueApplication\Exception\IOException
     *
     * @return void
     */
    public function dumpFile(string $filename, string $content): void;
}
