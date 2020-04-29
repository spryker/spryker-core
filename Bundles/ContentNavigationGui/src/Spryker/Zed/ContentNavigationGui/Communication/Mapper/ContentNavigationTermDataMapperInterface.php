<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentNavigationGui\Communication\Mapper;

use Generated\Shared\Transfer\ContentNavigationTermTransfer;
use Spryker\Zed\ContentNavigationGui\Communication\Form\Constraint\ContentNavigationConstraint;

interface ContentNavigationTermDataMapperInterface
{
    /**
     * @param string|null $navigationData
     * @param \Spryker\Zed\ContentNavigationGui\Communication\Form\Constraint\ContentNavigationConstraint $constraint
     *
     * @return \Generated\Shared\Transfer\ContentNavigationTermTransfer
     */
    public function mapNavigationDataToContentNavigationTermTransfer(
        ?string $navigationData,
        ContentNavigationConstraint $constraint
    ): ContentNavigationTermTransfer;
}
