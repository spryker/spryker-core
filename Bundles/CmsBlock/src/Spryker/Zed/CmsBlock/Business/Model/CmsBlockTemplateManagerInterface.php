<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlock\Business\Model;

interface CmsBlockTemplateManagerInterface
{
    /**
     * @param string $templatePath
     *
     * @return void
     */
    public function syncTemplate($templatePath);

    /**
     * @param string $name
     * @param string $path
     *
     * @return \Generated\Shared\Transfer\CmsBlockTemplateTransfer
     */
    public function createTemplate($name, $path);

    /**
     * @param string $path
     *
     * @throws \Spryker\Zed\CmsBlock\Business\Exception\CmsBlockTemplateNotFoundException
     *
     * @return void
     */
    public function checkTemplateFileExists($path);

    /**
     * @param int $idCmsBlockTemplate
     *
     * @return bool
     */
    public function hasTemplateFileById($idCmsBlockTemplate);

    /**
     * @param int $idCmsBlockTemplate
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockTemplate|null
     */
    public function getTemplateById($idCmsBlockTemplate);

    /**
     * @param string $path
     *
     * @return \Generated\Shared\Transfer\CmsBlockTemplateTransfer|null
     */
    public function findTemplateByPath($path);
}
