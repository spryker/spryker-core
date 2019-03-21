<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentBanner\Business\Model;

use Generated\Shared\Transfer\ContentBannerTransfer;
use Generated\Shared\Transfer\ContentParameterMessageTransfer;
use Generated\Shared\Transfer\ContentValidationResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\ContentBanner\Dependency\External\ContentBannerToValidationAdapterInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Constraints\Url;

class ContentBannerValidator implements ContentBannerValidatorInterface
{
    /**
     * @var \Spryker\Zed\ContentBanner\Dependency\External\ContentBannerToValidationAdapterInterface
     */
    protected $validationAdapter;

    /**
     * @param \Spryker\Zed\ContentBanner\Dependency\External\ContentBannerToValidationAdapterInterface $validationAdapter
     */
    public function __construct(ContentBannerToValidationAdapterInterface $validationAdapter)
    {
        $this->validationAdapter = $validationAdapter;
    }

    /**
     * @param \Generated\Shared\Transfer\ContentBannerTransfer $contentBannerTransfer
     *
     * @return \Generated\Shared\Transfer\ContentValidationResponseTransfer
     */
    public function validateContentBanner(ContentBannerTransfer $contentBannerTransfer): ContentValidationResponseTransfer
    {
        $isSuccess = true;
        $validator = $this->validationAdapter->createValidator();
        $properties = $contentBannerTransfer->toArray(true, true);
        $contentValidationResponseTransfer = new ContentValidationResponseTransfer();

        foreach ($this->getConstraintsMap() as $key => $value) {
            $violations = $validator->validate(
                $properties[$key],
                $value
            );
            if (count($violations) !== 0) {
                $contentParameterMessageTransfer = new ContentParameterMessageTransfer();
                $contentParameterMessageTransfer->setParameter($key);

                foreach ($violations as $violation) {
                    $contentParameterMessageTransfer->addMessage(
                        (new MessageTransfer())->setValue($violation->getMessage())
                    );
                }
                $contentValidationResponseTransfer->addParameterMessages($contentParameterMessageTransfer);
                $isSuccess = false;
            }
        }

        $contentValidationResponseTransfer->setIsSuccess($isSuccess);

        return $contentValidationResponseTransfer;
    }

    /**
     * @return array
     */
    private function getConstraintsMap(): array
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
