<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlock\Business\Model;

interface CmsBlockGlossaryKeyGeneratorInterface
{
    /**
     * @param int $idCmsBlock
     * @param string $templateName
     * @param string $placeholder
     * @param bool $autoIncrement
     *
     * @return string
     */
    public function generateGlossaryKeyName(
        int $idCmsBlock,
        string $templateName,
        string $placeholder,
        bool $autoIncrement = true
    ): string;
}
