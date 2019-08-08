<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Content\Business\ContentValidator;

use Generated\Shared\Transfer\ContentTransfer;
use Spryker\Zed\Content\Business\ContentValidator\Constraints\NotWhitespace;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
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
            ContentTransfer::KEY => $this->getKeyConstraints(),
        ];
    }

    /**
     * @return \Symfony\Component\Validator\Constraint[]
     */
    protected function getNameConstraints(): array
    {
        return [
            new NotWhitespace(),
            new Required(),
            new Length(['max' => 255]),
        ];
    }

    /**
     * @return \Symfony\Component\Validator\Constraint[]
     */
    protected function getDescriptionConstraints(): array
    {
        return [
            new NotWhitespace(),
            new Required(),
            new Length(['max' => 1024]),
        ];
    }

    /**
     * @return \Symfony\Component\Validator\Constraint[]
     */
    protected function getKeyConstraints(): array
    {
        return [
            new NotBlank(),
            new Required(),
            new Length(['max' => 255]),
            new Regex([
               'pattern' => '/^[a-z0-9\-]+$/',
            ]),
        ];
    }
}
