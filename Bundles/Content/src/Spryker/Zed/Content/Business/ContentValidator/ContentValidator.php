<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Content\Business\ContentValidator;

use Generated\Shared\Transfer\ContentParameterMessageTransfer;
use Generated\Shared\Transfer\ContentTransfer;
use Generated\Shared\Transfer\ContentValidationResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\Content\Dependency\External\ContentToValidationAdapterInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ContentValidator implements ContentValidatorInterface
{
    /**
     * @var \Spryker\Zed\Content\Business\ContentValidator\ContentConstraintsProviderInterface
     */
    protected $contentConstraintsProvider;

    /**
     * @var \Spryker\Zed\Content\Dependency\External\ContentToValidationAdapterInterface
     */
    protected $validationAdapter;

    /**
     * @param \Spryker\Zed\Content\Business\ContentValidator\ContentConstraintsProviderInterface $contentConstraintsProvider
     * @param \Spryker\Zed\Content\Dependency\External\ContentToValidationAdapterInterface $validationAdapter
     */
    public function __construct(
        ContentConstraintsProviderInterface $contentConstraintsProvider,
        ContentToValidationAdapterInterface $validationAdapter
    ) {
        $this->contentConstraintsProvider = $contentConstraintsProvider;
        $this->validationAdapter = $validationAdapter;
    }

    /**
     * @param \Generated\Shared\Transfer\ContentTransfer $contentTransfer
     *
     * @return \Generated\Shared\Transfer\ContentValidationResponseTransfer
     */
    public function validateContent(ContentTransfer $contentTransfer): ContentValidationResponseTransfer
    {
        $isSuccess = true;
        $validator = $this->validationAdapter->createValidator();
        $properties = $contentTransfer->toArray(true, true);
        $contentValidationResponseTransfer = new ContentValidationResponseTransfer();

        foreach ($this->contentConstraintsProvider->getConstraintsMap() as $parameter => $constraintCollection) {
            $violations = $validator->validate($properties[$parameter], $constraintCollection);
            if (count($violations)) {
                $contentValidationResponseTransfer->addParameterMessages(
                    $this->createContentParameterMessageTransfer($parameter, $violations)
                );
                $isSuccess = false;
            }
        }

        return $contentValidationResponseTransfer->setIsSuccess($isSuccess);
    }

    /**
     * @param string $parameter
     * @param \Symfony\Component\Validator\ConstraintViolationListInterface $violations
     *
     * @return \Generated\Shared\Transfer\ContentParameterMessageTransfer
     */
    protected function createContentParameterMessageTransfer(
        string $parameter,
        ConstraintViolationListInterface $violations
    ): ContentParameterMessageTransfer {
        $contentParameterMessageTransfer = (new ContentParameterMessageTransfer())->setParameter($parameter);
        foreach ($violations as $violation) {
            $contentParameterMessageTransfer->addMessage(
                (new MessageTransfer())->setValue($violation->getMessage())
            );
        }

        return $contentParameterMessageTransfer;
    }
}
