<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsStorage\Dependency\Facade;

use Generated\Shared\Transfer\CmsPageDataTransfer;

class CmsStorageToCmsBridge implements CmsStorageToCmsInterface
{

    /**
     * @var \Spryker\Zed\Cms\Business\CmsFacadeInterface
     */
    protected $cmsFacade;

    /**
     * @param \Spryker\Zed\Cms\Business\CmsFacadeInterface $cmsFacade
     */
    public function __construct($cmsFacade)
    {
        $this->cmsFacade = $cmsFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsPageDataTransfer $cmsPageDataTransfer
     * @param string $data
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\CmsPageDataTransfer
     */
    public function expandCmsPageDataTransfer(CmsPageDataTransfer $cmsPageDataTransfer, $data, $localeName)
    {
        return $this->cmsFacade->expandCmsPageDataTransfer($cmsPageDataTransfer, $data, $localeName);
    }

}
