<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentBanner\Business\Model;

use Generated\Shared\Transfer\ContentBannerTermTransfer;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContentBannerConstraintsProvider implements ContentBannerConstraintsProviderInterface
{
    /**
     * @return array
     */
    public function getConstraintsMap(): array
    {
        return [
            ContentBannerTermTransfer::TITLE => $this->getTitleConstraints(),
            ContentBannerTermTransfer::SUBTITLE => $this->getSubtitleConstraints(),
            ContentBannerTermTransfer::IMAGE_URL => $this->getImageUrlConstraints(),
            ContentBannerTermTransfer::CLICK_URL => $this->getClickUrlConstraints(),
            ContentBannerTermTransfer::ALT_TEXT => $this->getAltTextConstraints(),
        ];
    }

    /**
     * @return array<\Symfony\Component\Validator\Constraint>
     */
    protected function getTitleConstraints(): array
    {
        return [
            new NotBlank(),
            new Length(['max' => 64]),
        ];
    }

    /**
     * @return array<\Symfony\Component\Validator\Constraint>
     */
    protected function getSubtitleConstraints(): array
    {
        return [
            new NotBlank(),
            new Length(['max' => 128]),
        ];
    }

    /**
     * @return array<\Symfony\Component\Validator\Constraint>
     */
    protected function getImageUrlConstraints(): array
    {
        return [
            new NotBlank(),
            new Length(['max' => 1028]),
        ];
    }

    /**
     * @return array<\Symfony\Component\Validator\Constraint>
     */
    protected function getClickUrlConstraints(): array
    {
        return [
            new NotBlank(),
            new Length(['max' => 1028]),
        ];
    }

    /**
     * @return array<\Symfony\Component\Validator\Constraint>
     */
    protected function getAltTextConstraints(): array
    {
        return [
            new NotBlank(),
            new Length(['max' => 125]),
        ];
    }
}
