<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ContentProductDataImport\Dependency\Facade;

use Generated\Shared\Transfer\ContentProductAbstractListTermTransfer;
use Generated\Shared\Transfer\ContentValidationResponseTransfer;

class ContentProductDataImportToContentProductFacadeBridge implements ContentProductDataImportToContentProductFacadeInterface
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
     * @param \Generated\Shared\Transfer\ContentProductAbstractListTermTransfer $contentProductAbstractListTermTransfer
     *
     * @return \Generated\Shared\Transfer\ContentValidationResponseTransfer
     */
    public function validateContentProductAbstractListTerm(
        ContentProductAbstractListTermTransfer $contentProductAbstractListTermTransfer
    ): ContentValidationResponseTransfer {
        return $this->contentProductFacade->validateContentProductAbstractListTerm($contentProductAbstractListTermTransfer);
    }
}
