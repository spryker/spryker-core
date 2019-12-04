<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\CmsSlotBlockStorage;

use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\CmsSlotBlockStorage\CmsSlotBlockStorageServiceFactory getFactory()
 */
class CmsSlotBlockStorageService extends AbstractService implements CmsSlotBlockStorageServiceInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $cmsSlotTemplatePath
     * @param string $cmsSlotKey
     *
     * @return string
     */
    public function generateSlotTemplateKey(string $cmsSlotTemplatePath, string $cmsSlotKey): string
    {
        return $this->getFactory()
            ->createCmsSlotBlockStorageKeyBuilder()
            ->generateSlotTemplateKey($cmsSlotTemplatePath, $cmsSlotKey);
    }
}
