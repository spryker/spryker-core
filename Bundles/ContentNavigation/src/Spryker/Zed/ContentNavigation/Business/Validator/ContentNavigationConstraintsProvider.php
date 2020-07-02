<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentNavigation\Business\Validator;

use Generated\Shared\Transfer\ContentNavigationTermTransfer;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContentNavigationConstraintsProvider implements ContentNavigationConstraintsProviderInterface
{
    /**
     * @var \Symfony\Component\Validator\Constraint
     */
    protected $navigationKeyExistsConstraint;

    /**
     * @param \Symfony\Component\Validator\Constraint $navigationKeyExistsConstraint
     */
    public function __construct(Constraint $navigationKeyExistsConstraint)
    {
        $this->navigationKeyExistsConstraint = $navigationKeyExistsConstraint;
    }

    /**
     * @return \Symfony\Component\Validator\Constraint[][]
     */
    public function getConstraintsMap(): array
    {
        return [
            ContentNavigationTermTransfer::NAVIGATION_KEY => $this->getNavigationKeyConstraints(),
        ];
    }

    /**
     * @return \Symfony\Component\Validator\Constraint[]
     */
    protected function getNavigationKeyConstraints(): array
    {
        return [
            new NotBlank(),
            $this->navigationKeyExistsConstraint,
        ];
    }
}
