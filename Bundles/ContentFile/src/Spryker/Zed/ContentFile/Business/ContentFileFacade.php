<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentFile\Business;

use Generated\Shared\Transfer\ContentFileListTermTransfer;
use Generated\Shared\Transfer\ContentValidationResponseTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ContentFile\Business\ContentFileBusinessFactory getFactory()
 */
class ContentFileFacade extends AbstractFacade implements ContentFileFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ContentFileListTermTransfer $contentFileListTermTransfer
     *
     * @return \Generated\Shared\Transfer\ContentValidationResponseTransfer
     */
    public function validateContentFileListTerm(
        ContentFileListTermTransfer $contentFileListTermTransfer
    ): ContentValidationResponseTransfer {
        return $this->getFactory()->createContentFileListValidator()->validate($contentFileListTermTransfer);
    }
}
