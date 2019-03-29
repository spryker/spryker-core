<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Content\Business\ContentValidator;

use Generated\Shared\Transfer\ContentTransfer;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;

class ContentConstraintsProvider implements ContentConstraintsProviderInterface
{
    /**
     * @return array
     */
    public function getConstraintsMap(): array
    {
        return [
            ContentTransfer::NAME => $this->getNameConstraints(),
            ContentTransfer::DESCRIPTION => $this->getDescriptionConstraints(),
        ];
    }

    /**
     * @return array
     */
    private function getNameConstraints(): array
    {
        return [
            new NotBlank(),
            new Required(),
            new Length(['max' => 255]),
        ];
    }

    /**
     * @return array
     */
    private function getDescriptionConstraints(): array
    {
        return [
            new NotBlank(),
            new Required(),
            new Length(['max' => 1024]),
        ];
    }
}
