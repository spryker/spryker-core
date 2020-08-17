<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ContentProductDataImport\Dependency\Facade;

use Generated\Shared\Transfer\ContentTransfer;
use Generated\Shared\Transfer\ContentValidationResponseTransfer;

class ContentProductDataImportToContentBridge implements ContentProductDataImportToContentInterface
{
    /**
     * @var \Spryker\Zed\Content\Business\ContentFacadeInterface
     */
    protected $contentFacade;

    /**
     * @param \Spryker\Zed\Content\Business\ContentFacadeInterface $contentFacade
     */
    public function __construct($contentFacade)
    {
        $this->contentFacade = $contentFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ContentTransfer $contentTransfer
     *
     * @return \Generated\Shared\Transfer\ContentValidationResponseTransfer
     */
    public function validateContent(
        ContentTransfer $contentTransfer
    ): ContentValidationResponseTransfer {
        return $this->contentFacade->validateContent($contentTransfer);
    }
}
