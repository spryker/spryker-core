<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Dependency\External;

interface OauthToFilesystemInterface
{
    /**
     * @param string $filename
     * @param string $content
     *
     * @throws \Spryker\Zed\Oauth\Business\Exception\IOException
     *
     * @return void
     */
    public function dumpFile(string $filename, string $content): void;
}
