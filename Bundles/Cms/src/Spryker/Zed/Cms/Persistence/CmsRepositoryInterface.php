<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Persistence;

use Generated\Shared\Transfer\CmsTemplateTransfer;

interface CmsRepositoryInterface
{
    /**
     * @param string $templatePath
     *
     * @return \Generated\Shared\Transfer\CmsTemplateTransfer|null
     */
    public function findCmsTemplateByPath(string $templatePath): ?CmsTemplateTransfer;

    /**
     * @return string[]
     */
    public function findAllCmsTemplatePaths(): array;
}
