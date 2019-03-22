<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentBanner\Business\Model;

use Generated\Shared\Transfer\ContentBannerTransfer;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Constraints\Url;

class ContentBannerConstraintsProvider implements ContentBannerConstraintsProviderInterface
{
    /**
     * @return array
     */
    public function getConstraintsMap(): array
    {
        return [
            ContentBannerTransfer::TITLE => $this->getTitleConstraints(),
            ContentBannerTransfer::SUBTITLE => $this->getSubtitleConstraints(),
            ContentBannerTransfer::IMAGE_URL => $this->getImageUrlConstraints(),
            ContentBannerTransfer::CLICK_URL => $this->getClickUrlConstraints(),
            ContentBannerTransfer::ALT_TEXT => $this->getAltTextConstraints(),
        ];
    }

    /**
     * @return array
     */
    private function getTitleConstraints(): array
    {
        return [
            new Required(),
            new NotBlank(),
            new Length(['max' => 64]),
        ];
    }

    /**
     * @return array
     */
    private function getSubtitleConstraints(): array
    {
        return [
            new Required(),
            new NotBlank(),
            new Length(['max' => 128]),
        ];
    }

    /**
     * @return array
     */
    private function getImageUrlConstraints(): array
    {
        return [
            new Required(),
            new NotBlank(),
            new Length(['max' => 1028]),
            new Url(),
        ];
    }

    /**
     * @return array
     */
    private function getClickUrlConstraints(): array
    {
        return [
            new Required(),
            new NotBlank(),
            new Length(['max' => 1028]),
            new Url(),
        ];
    }

    /**
     * @return array
     */
    private function getAltTextConstraints(): array
    {
        return [
            new Required(),
            new NotBlank(),
            new Length(['max' => 125]),
        ];
    }
}
