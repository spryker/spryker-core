<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlot\Business\ConstraintsProvider;

use Generated\Shared\Transfer\CmsSlotTemplateTransfer;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class CmsSlotTemplateConstraintsProvider implements ConstraintsProviderInterface
{
    /**
     * @return array
     */
    public function getConstraintsMap(): array
    {
        return [
            CmsSlotTemplateTransfer::PATH => $this->getPathConstraints(),
            CmsSlotTemplateTransfer::NAME => $this->getNameConstraints(),
            CmsSlotTemplateTransfer::DESCRIPTION => $this->getDescriptionConstraints(),
        ];
    }

    /**
     * @return array<\Symfony\Component\Validator\Constraint>
     */
    protected function getPathConstraints(): array
    {
        return [
            new NotBlank(),
            new Regex([
                'pattern' => '/^@.+\.twig$/',
            ]),
        ];
    }

    /**
     * @return array<\Symfony\Component\Validator\Constraint>
     */
    protected function getNameConstraints(): array
    {
        return [
            new NotBlank(),
            new Length(['max' => 255]),
        ];
    }

    /**
     * @return array<\Symfony\Component\Validator\Constraint>
     */
    protected function getDescriptionConstraints(): array
    {
        return [
            new NotBlank(),
            new Length(['max' => 1024]),
        ];
    }
}
