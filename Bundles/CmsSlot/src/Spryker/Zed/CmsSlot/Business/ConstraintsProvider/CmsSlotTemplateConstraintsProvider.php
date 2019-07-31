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
use Symfony\Component\Validator\Constraints\Required;

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
     * @return \Symfony\Component\Validator\Constraint[]
     */
    protected function getPathConstraints(): array
    {
        return [
            new Required(),
            new NotBlank(),
            new Regex([
                'pattern' => '/^@.+\.twig$/',
            ]),
        ];
    }

    /**
     * @return \Symfony\Component\Validator\Constraint[]
     */
    protected function getNameConstraints(): array
    {
        return [
            new Required(),
            new NotBlank(),
            new Length(['max' => 255]),
        ];
    }

    /**
     * @return \Symfony\Component\Validator\Constraint[]
     */
    protected function getDescriptionConstraints(): array
    {
        return [
            new Required(),
            new NotBlank(),
            new Length(['max' => 1024]),
        ];
    }
}
