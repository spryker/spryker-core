<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlock\Business\Model;

use Generated\Shared\Transfer\CmsBlockTemplateTransfer;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockTemplate;

interface CmsBlockTemplateManagerInterface
{
    /**
     * @param string $templatePath
     *
     * @return void
     */
    public function syncTemplate(string $templatePath): void;

    /**
     * @param string $name
     * @param string $path
     *
     * @return \Generated\Shared\Transfer\CmsBlockTemplateTransfer
     */
    public function createTemplate(string $name, string $path);

    /**
     * @param string $path
     *
     * @throws \Spryker\Zed\CmsBlock\Business\Exception\CmsBlockTemplateNotFoundException
     *
     * @return void
     */
    public function checkTemplateFileExists(string $path): void;

    /**
     * @param int $idCmsBlockTemplate
     *
     * @return bool
     */
    public function hasTemplateFileById(int $idCmsBlockTemplate): bool;

    /**
     * @param int $idCmsBlockTemplate
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockTemplate|null
     */
    public function getTemplateById(int $idCmsBlockTemplate): ?SpyCmsBlockTemplate;

    /**
     * @param string $path
     *
     * @return \Generated\Shared\Transfer\CmsBlockTemplateTransfer|null
     */
    public function findTemplateByPath(string $path): ?CmsBlockTemplateTransfer;
}
