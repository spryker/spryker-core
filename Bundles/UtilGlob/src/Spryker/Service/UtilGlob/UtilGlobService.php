<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilGlob;

use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\UtilGlob\UtilGlobServiceFactory getFactory()
 */
class UtilGlobService extends AbstractService implements UtilGlobServiceInterface
{
    /**
     * {@inheriDoc}
     *
     * @api
     *
     * @param string $pattern
     * @param int $flags
     *
     * @return array
     */
    public function glob(string $pattern, int $flags = 0): array
    {
        return $this->getFactory()->createGlob()->glob($pattern, $flags);
    }
}
