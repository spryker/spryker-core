<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\Cms\Persistence\CmsPersistenceFactory getFactory()
 */
class CmsEntityManager extends AbstractEntityManager implements CmsEntityManagerInterface
{
    /**
     * @param array $nonExistingEntityPaths
     *
     * @return void
     */
    public function deleteNonExistingCmsTemplateEntitiesByPaths(array $nonExistingEntityPaths): void
    {
        foreach ($nonExistingEntityPaths as $nonExistingEntityPath) {
            $this->deleteSingleCmsTemplateEntityByPath($nonExistingEntityPath);
        }
    }

    /**
     * @param string $cmsTemplatePath
     *
     * @return void
     */
    protected function deleteSingleCmsTemplateEntityByPath(string $cmsTemplatePath): void
    {
        $cmsTemplateEntity = $this->getFactory()
            ->createCmsTemplateQuery()
            ->filterByTemplatePath($cmsTemplatePath)
            ->findOne();

        if ($cmsTemplateEntity) {
            $cmsTemplateEntity->delete();
        }
    }
}
