<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ContentNavigationDataImport\Dependency\Facade;

use Generated\Shared\Transfer\ContentNavigationTermTransfer;
use Generated\Shared\Transfer\ContentValidationResponseTransfer;

interface ContentNavigationDataImportToContentNavigationFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\ContentNavigationTermTransfer $contentNavigationTermTransfer
     *
     * @return \Generated\Shared\Transfer\ContentValidationResponseTransfer
     */
    public function validateContentNavigationTerm(ContentNavigationTermTransfer $contentNavigationTermTransfer): ContentValidationResponseTransfer;
}
