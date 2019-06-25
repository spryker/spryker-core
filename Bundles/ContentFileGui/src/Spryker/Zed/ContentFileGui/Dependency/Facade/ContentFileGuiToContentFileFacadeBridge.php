<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentFileGui\Dependency\Facade;

use Generated\Shared\Transfer\ContentFileListTermTransfer;
use Generated\Shared\Transfer\ContentValidationResponseTransfer;

class ContentFileGuiToContentFileFacadeBridge implements ContentFileGuiToContentFileFacadeInterface
{
    /**
     * @var \Spryker\Zed\ContentFile\Business\ContentFileFacadeInterface
     */
    protected $contentFileFacade;

    /**
     * @param \Spryker\Zed\ContentFile\Business\ContentFileFacadeInterface $contentFileFacade
     */
    public function __construct($contentFileFacade)
    {
        $this->contentFileFacade = $contentFileFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ContentFileListTermTransfer $contentFileListTermTransfer
     *
     * @return \Generated\Shared\Transfer\ContentValidationResponseTransfer
     */
    public function validateContentFileListTerm(
        ContentFileListTermTransfer $contentFileListTermTransfer
    ): ContentValidationResponseTransfer {
        return $this->contentFileFacade->validateContentFileListTerm($contentFileListTermTransfer);
    }
}
