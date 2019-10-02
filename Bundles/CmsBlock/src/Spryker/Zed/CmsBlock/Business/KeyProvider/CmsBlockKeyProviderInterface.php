<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlock\Business\KeyProvider;

interface CmsBlockKeyProviderInterface
{
    /**
     * @param int|null $idCmsBlock
     *
     * @return string
     */
    public function generateKey(?int $idCmsBlock = null): string;

    /**
     * @param int $idCmsBlock
     *
     * @return string
     */
    public function getKeyByIdCmsBlock(int $idCmsBlock): string;
}
