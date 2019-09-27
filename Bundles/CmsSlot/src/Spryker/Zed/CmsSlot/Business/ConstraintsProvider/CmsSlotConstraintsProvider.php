<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlot\Business\ConstraintsProvider;

use Generated\Shared\Transfer\CmsSlotTransfer;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Constraints\Type;

class CmsSlotConstraintsProvider implements ConstraintsProviderInterface
{
    /**
     * @return array
     */
    public function getConstraintsMap(): array
    {
        return [
            CmsSlotTransfer::KEY => $this->getKeyConstraints(),
            CmsSlotTransfer::CONTENT_PROVIDER_TYPE => $this->getContentProviderTypeConstraints(),
            CmsSlotTransfer::NAME => $this->getNameConstraints(),
            CmsSlotTransfer::DESCRIPTION => $this->getDescriptionConstraints(),
            CmsSlotTransfer::IS_ACTIVE => $this->getIsActiveConstraints(),
        ];
    }

    /**
     * @return \Symfony\Component\Validator\Constraint[]
     */
    protected function getKeyConstraints(): array
    {
        return [
            new Required(),
            new NotBlank(),
            new Length(['max' => 255]),
            new Regex([
                'pattern' => '/^[a-z0-9\-]+$/',
            ]),
        ];
    }

    /**
     * @return \Symfony\Component\Validator\Constraint[]
     */
    protected function getContentProviderTypeConstraints(): array
    {
        return [
            new Required(),
            new NotBlank(),
            new Length(['max' => 64]),
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

    /**
     * @return \Symfony\Component\Validator\Constraint[]
     */
    protected function getIsActiveConstraints(): array
    {
        return [
            new Required(),
            new Type([
                'type' => 'boolean',
            ]),
        ];
    }
}
