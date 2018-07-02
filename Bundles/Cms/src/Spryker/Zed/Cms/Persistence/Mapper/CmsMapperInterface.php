<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Persistence\Mapper;

use Generated\Shared\Transfer\CmsTemplateTransfer;
use Orm\Zed\Cms\Persistence\SpyCmsTemplate;

interface CmsMapperInterface
{
    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsTemplate $cmsTemplateEntity
     *
     * @return \Generated\Shared\Transfer\CmsTemplateTransfer
     */
    public function mapSpyCmsTemplateEntityToCmsTemplateTransfer(SpyCmsTemplate $cmsTemplateEntity): CmsTemplateTransfer;
}
