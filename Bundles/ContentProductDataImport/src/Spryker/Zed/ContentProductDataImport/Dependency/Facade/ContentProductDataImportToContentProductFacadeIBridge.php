<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductDataImport\Dependency\Facade;

use Generated\Shared\Transfer\ContentProductAbstractListTransfer;
use Generated\Shared\Transfer\ContentValidationResponseTransfer;

class ContentProductDataImportToContentProductFacadeIBridge implements ContentProductDataImportToContentProductFacadeInterface
{
    /**
     * @var \Spryker\Zed\ContentProduct\Business\ContentProductFacadeInterface
     */
    protected $contentProductFacade;

    /**
     * @param \Spryker\Zed\ContentProduct\Business\ContentProductFacadeInterface $contentProductFacade
     */
    public function __construct($contentProductFacade)
    {
        $this->contentProductFacade = $contentProductFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ContentProductAbstractListTransfer $contentProductAbstractListTransfer
     *
     * @return \Generated\Shared\Transfer\ContentValidationResponseTransfer
     */
    public function validateContentProductAbstractList(
        ContentProductAbstractListTransfer $contentProductAbstractListTransfer
    ): ContentValidationResponseTransfer {
        return $this->contentProductFacade->validateContentProductAbstractList($contentProductAbstractListTransfer);
    }
}
