<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentBanner\Business\Model;

use Generated\Shared\Transfer\ContentBannerTermTransfer;
use Generated\Shared\Transfer\ContentParameterMessageTransfer;
use Generated\Shared\Transfer\ContentValidationResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\ContentBanner\Dependency\External\ContentBannerToValidationAdapterInterface;

class ContentBannerValidator implements ContentBannerValidatorInterface
{
    /**
     * @var \Spryker\Zed\ContentBanner\Dependency\External\ContentBannerToValidationAdapterInterface
     */
    protected $validationAdapter;

    /**
     * @var \Spryker\Zed\ContentBanner\Business\Model\ContentBannerConstraintsProviderInterface
     */
    protected $constraintsProvider;

    /**
     * @param \Spryker\Zed\ContentBanner\Dependency\External\ContentBannerToValidationAdapterInterface $validationAdapter
     * @param \Spryker\Zed\ContentBanner\Business\Model\ContentBannerConstraintsProviderInterface $constraintsProvider
     */
    public function __construct(
        ContentBannerToValidationAdapterInterface $validationAdapter,
        ContentBannerConstraintsProviderInterface $constraintsProvider
    ) {
        $this->validationAdapter = $validationAdapter;
        $this->constraintsProvider = $constraintsProvider;
    }

    /**
     * @param \Generated\Shared\Transfer\ContentBannerTermTransfer $contentBannerTermTransfer
     *
     * @return \Generated\Shared\Transfer\ContentValidationResponseTransfer
     */
    public function validateContentBannerTerm(ContentBannerTermTransfer $contentBannerTermTransfer): ContentValidationResponseTransfer
    {
        $isSuccess = true;
        $validator = $this->validationAdapter->createValidator();
        $properties = $contentBannerTermTransfer->toArray(true, true);
        $contentValidationResponseTransfer = new ContentValidationResponseTransfer();

        foreach ($this->constraintsProvider->getConstraintsMap() as $parameter => $constraintCollection) {
            $violations = $validator->validate(
                $properties[$parameter],
                $constraintCollection
            );
            if (count($violations) !== 0) {
                $contentParameterMessageTransfer = new ContentParameterMessageTransfer();
                $contentParameterMessageTransfer->setParameter($parameter);

                foreach ($violations as $violation) {
                    $contentParameterMessageTransfer->addMessage(
                        (new MessageTransfer())->setValue($violation->getMessage())
                    );
                }
                $contentValidationResponseTransfer->addParameterMessages($contentParameterMessageTransfer);
                $isSuccess = false;
            }
        }

        return $contentValidationResponseTransfer->setIsSuccess($isSuccess);
    }
}
