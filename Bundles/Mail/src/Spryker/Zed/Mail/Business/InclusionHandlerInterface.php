<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Mail\Business;

interface InclusionHandlerInterface
{

    /**
     * @param string $path
     *
     * @return string
     */
    public function guessType($path);

    /**
     * @param string $path
     *
     * @return string
     */
    public function getFilename($path);

    /**
     * @param string $path
     *
     * @return string
     */
    public function encodeBase64($path);

}
