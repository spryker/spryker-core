<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Persistence;

use Generated\Shared\Transfer\CmsTemplateTransfer;
use Orm\Zed\Cms\Persistence\Map\SpyCmsTemplateTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Cms\Persistence\CmsPersistenceFactory getFactory()
 */
class CmsRepository extends AbstractRepository implements CmsRepositoryInterface
{
    /**
     * @param string $templatePath
     *
     * @return \Generated\Shared\Transfer\CmsTemplateTransfer|null
     */
    public function findCmsTemplateByPath(string $templatePath): ?CmsTemplateTransfer
    {
        $query = $this->getFactory()
            ->createCmsTemplateQuery();

        $cmsTemplateEntity = $query->filterByTemplatePath($templatePath)->findOne();

        if ($cmsTemplateEntity) {
            return $this->getFactory()
                ->createCmsMapper()
                ->mapSpyCmsTemplateEntityToCmsTemplateTransfer($cmsTemplateEntity);
        }

        return null;
    }

    /**
     * @return string[]
     */
    public function findAllCmsTemplatePaths(): array
    {
        $query = $this->getFactory()
            ->createCmsTemplateQuery();

        return $query->select(SpyCmsTemplateTableMap::COL_TEMPLATE_PATH)
            ->find()
            ->toArray();
    }
}
