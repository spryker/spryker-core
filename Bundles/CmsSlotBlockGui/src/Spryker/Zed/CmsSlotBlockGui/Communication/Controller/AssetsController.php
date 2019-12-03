<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockGui\Communication\Controller;

use Spryker\Shared\CmsSlotBlock\CmsSlotBlockConfig;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

/**
 * @method \Spryker\Zed\CmsSlotBlockGui\Communication\CmsSlotBlockGuiCommunicationFactory getFactory()
 */
class AssetsController extends AbstractController
{
    /**
     * @return array
     */
    public function indexAction(): array
    {
        return $this->viewResponse([
            'cmsSlotBlockContentProvider' => CmsSlotBlockConfig::CMS_SLOT_CONTENT_PROVIDER_TYPE,
        ]);
    }
}
