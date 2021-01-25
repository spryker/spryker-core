<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlock\Business\Model;

use Generated\Shared\Transfer\CmsBlockTemplateTransfer;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockTemplate;

class CmsBlockTemplateMapper implements CmsBlockTemplateMapperInterface
{
    /**
     * @param \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockTemplate $spyCmsBlockTemplate
     *
     * @return \Generated\Shared\Transfer\CmsBlockTemplateTransfer
     */
    public function mapTemplateEntityToTransfer(SpyCmsBlockTemplate $spyCmsBlockTemplate): CmsBlockTemplateTransfer
    {
        $cmsBlockTemplateTransfer = new CmsBlockTemplateTransfer();
        $cmsBlockTemplateTransfer->fromArray($spyCmsBlockTemplate->toArray(), true);

        return $cmsBlockTemplateTransfer;
    }
}
