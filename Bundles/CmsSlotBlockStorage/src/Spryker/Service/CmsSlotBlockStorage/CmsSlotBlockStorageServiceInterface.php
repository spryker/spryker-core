<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\CmsSlotBlockStorage;

interface CmsSlotBlockStorageServiceInterface
{
    /**
     * Specification:
     * - Builds CMS slot block storage key by given slot template path and slot key.
     *
     * @api
     *
     * @param string $cmsSlotTemplatePath
     * @param string $cmsSlotKey
     *
     * @return string
     */
    public function generateSlotTemplateKey(string $cmsSlotTemplatePath, string $cmsSlotKey): string;
}
