<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentNavigation\Business\Validator;

use Generated\Shared\Transfer\ContentNavigationTermTransfer;
use Symfony\Component\Validator\Constraints\Required;

class ContentNavigationConstraintsProvider implements ContentNavigationConstraintsProviderInterface
{
    /**
     * @return \Symfony\Component\Validator\Constraint[][]
     */
    public function getConstraintsMap(): array
    {
        return [
            ContentNavigationTermTransfer::NAVIGATION_KEY => []
        ];
    }

    /**
     * @return \Symfony\Component\Validator\Constraint[]
     */
    protected function getNavigationKeyConstraints(): array
    {
        return [
            new Required(),
        ];
    }
}
