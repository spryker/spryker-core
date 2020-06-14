<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentNavigationGui\Dependency\Facade;

use Generated\Shared\Transfer\ContentNavigationTermTransfer;
use Generated\Shared\Transfer\ContentValidationResponseTransfer;

class ContentNavigationGuiToContentNavigationFacadeBridge implements ContentNavigationGuiToContentNavigationFacadeInterface
{
    /**
     * @var \Spryker\Zed\ContentNavigation\Business\ContentNavigationFacadeInterface
     */
    protected $contentNavigationFacade;

    /**
     * @param \Spryker\Zed\ContentNavigation\Business\ContentNavigationFacadeInterface $contentNavigationFacade
     */
    public function __construct($contentNavigationFacade)
    {
        $this->contentNavigationFacade = $contentNavigationFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ContentNavigationTermTransfer $contentNavigationTermTransfer
     *
     * @return \Generated\Shared\Transfer\ContentValidationResponseTransfer
     */
    public function validateContentNavigationTerm(ContentNavigationTermTransfer $contentNavigationTermTransfer): ContentValidationResponseTransfer
    {
        return $this->contentNavigationFacade->validateContentNavigationTerm($contentNavigationTermTransfer);
    }
}
