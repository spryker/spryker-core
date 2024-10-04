<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TestifyAsyncApi\Business\Codeception\Helper;

use Generated\Shared\Transfer\MessagePropertiesValidationRequestTransfer;
use Generated\Shared\Transfer\MessagePropertiesValidationResponseTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerSdk\AsyncApi\AsyncApi\Message\AsyncApiMessageInterface;
use Symfony\Component\PropertyAccess\PropertyAccessor;

trait AsyncApiMessageValidatorHelperTrait
{
    use AsyncApiMessageAttributeCollectionExpanderHelperTrait;

    /**
     * @param string $messageNameToTest
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $messageTransfer
     * @param \SprykerSdk\AsyncApi\AsyncApi\Message\AsyncApiMessageInterface $asyncApiMessage
     *
     * @return \Generated\Shared\Transfer\MessagePropertiesValidationResponseTransfer
     */
    protected function validateMessage(
        string $messageNameToTest,
        AbstractTransfer $messageTransfer,
        AsyncApiMessageInterface $asyncApiMessage
    ): MessagePropertiesValidationResponseTransfer {
        $messagePropertiesValidationRequestTransfer = (new MessagePropertiesValidationRequestTransfer())
            ->setMessageName($messageNameToTest);

        $messagePropertiesValidationRequestTransfer = $this->prepareMessageForValidation(
            $messagePropertiesValidationRequestTransfer,
            $messageTransfer,
            $asyncApiMessage,
        );

        return $this->executeValidation($messagePropertiesValidationRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MessagePropertiesValidationRequestTransfer $messagePropertiesValidationRequestTransfer
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $messageTransfer
     * @param \SprykerSdk\AsyncApi\AsyncApi\Message\AsyncApiMessageInterface $asyncApiMessage
     *
     * @return \Generated\Shared\Transfer\MessagePropertiesValidationRequestTransfer
     */
    protected function prepareMessageForValidation(
        MessagePropertiesValidationRequestTransfer $messagePropertiesValidationRequestTransfer,
        AbstractTransfer $messageTransfer,
        AsyncApiMessageInterface $asyncApiMessage
    ): MessagePropertiesValidationRequestTransfer {
        $messagePropertiesValidationRequestTransfer = $this->expandWithRequiredPayloadAttributes(
            $messagePropertiesValidationRequestTransfer,
            $asyncApiMessage,
        );

        $properties = $messageTransfer->modifiedToArray(true, true);

        return $messagePropertiesValidationRequestTransfer->setProperties($properties);
    }

    /**
     * @param \Generated\Shared\Transfer\MessagePropertiesValidationRequestTransfer $messagePropertiesValidationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MessagePropertiesValidationResponseTransfer
     */
    protected function executeValidation(
        MessagePropertiesValidationRequestTransfer $messagePropertiesValidationRequestTransfer
    ): MessagePropertiesValidationResponseTransfer {
        $messagePropertiesValidationResponseTransfer = (new MessagePropertiesValidationResponseTransfer())
            ->setIsSuccessful(true);

        if (!$this->hasRequiredProperties($messagePropertiesValidationRequestTransfer)) {
            return $messagePropertiesValidationResponseTransfer;
        }

        $propertyAccessor = new PropertyAccessor();

        $missingProperties = $this->getMissingPropertiesFromRequiredProperties($messagePropertiesValidationRequestTransfer, $propertyAccessor, []);
        $missingProperties = $this->getMissingPropertiesFromRequiredArrayProperties($messagePropertiesValidationRequestTransfer, $propertyAccessor, $missingProperties);

        if (!$missingProperties) {
            return $messagePropertiesValidationResponseTransfer;
        }

        return $messagePropertiesValidationResponseTransfer
            ->setIsSuccessful(false)
            ->setErrorMessage($this->getValidationError($messagePropertiesValidationRequestTransfer, $missingProperties));
    }

    /**
     * @param \Generated\Shared\Transfer\MessagePropertiesValidationRequestTransfer $messagePropertiesValidationRequestTransfer
     *
     * @return bool
     */
    protected function hasRequiredProperties(MessagePropertiesValidationRequestTransfer $messagePropertiesValidationRequestTransfer): bool
    {
        return $messagePropertiesValidationRequestTransfer->getRequiredProperties()
            || $messagePropertiesValidationRequestTransfer->getRequiredArrayProperties();
    }

    /**
     * @param \Generated\Shared\Transfer\MessagePropertiesValidationRequestTransfer $messagePropertiesValidationRequestTransfer
     * @param \Symfony\Component\PropertyAccess\PropertyAccessor $propertyAccessor
     * @param array<int, string> $missingProperties
     *
     * @return array<int, string>
     */
    protected function getMissingPropertiesFromRequiredArrayProperties(
        MessagePropertiesValidationRequestTransfer $messagePropertiesValidationRequestTransfer,
        PropertyAccessor $propertyAccessor,
        array $missingProperties
    ): array {
        foreach ($messagePropertiesValidationRequestTransfer->getRequiredArrayProperties() as $requiredArrayProperty) {
            $parentPath = sprintf('[%s]', implode('][', explode('.', $requiredArrayProperty['parent'])));
            $items = $propertyAccessor->getValue($messagePropertiesValidationRequestTransfer->getProperties(), $parentPath);

            if ($items === null) {
                continue;
            }

            $missingProperties = array_merge(
                $missingProperties,
                $this->getMissingItemsProperties($items, $requiredArrayProperty),
            );
        }

        return $missingProperties;
    }

    /**
     * @param list<mixed> $items
     * @param array<string, mixed> $requiredArrayProperty
     *
     * @return array<int, string>
     */
    protected function getMissingItemsProperties(iterable $items, array $requiredArrayProperty): array
    {
        $missingItemsProperties = [];
        foreach ($items as $position => $value) {
            if (!isset($value[$requiredArrayProperty['property']])) {
                $missingItemsProperties[] = sprintf('%s[%s].%s', $requiredArrayProperty['parent'], $position, $requiredArrayProperty['property']);
            }
        }

        return $missingItemsProperties;
    }

    /**
     * @param \Generated\Shared\Transfer\MessagePropertiesValidationRequestTransfer $messagePropertiesValidationRequestTransfer
     * @param \Symfony\Component\PropertyAccess\PropertyAccessor $propertyAccessor
     * @param array<int, string> $missingProperties
     *
     * @return array<int, string>
     */
    protected function getMissingPropertiesFromRequiredProperties(
        MessagePropertiesValidationRequestTransfer $messagePropertiesValidationRequestTransfer,
        PropertyAccessor $propertyAccessor,
        array $missingProperties
    ): array {
        foreach ($messagePropertiesValidationRequestTransfer->getRequiredProperties() as $requiredProperty) {
            $propertyPath = sprintf('[%s]', implode('][', explode('.', $requiredProperty)));

            if (
                !$propertyAccessor->isReadable($messagePropertiesValidationRequestTransfer->getProperties(), $propertyPath)
                || $propertyAccessor->getValue($messagePropertiesValidationRequestTransfer->getProperties(), $propertyPath) === null
                || $propertyAccessor->getValue($messagePropertiesValidationRequestTransfer->getProperties(), $propertyPath) === []
            ) {
                $missingProperties[] = $requiredProperty;
            }
        }

        return $missingProperties;
    }

    /**
     * @param \Generated\Shared\Transfer\MessagePropertiesValidationRequestTransfer $messagePropertiesValidationRequestTransfer
     * @param array<int, string> $missingProperties
     *
     * @return string
     */
    protected function getValidationError(
        MessagePropertiesValidationRequestTransfer $messagePropertiesValidationRequestTransfer,
        array $missingProperties
    ): string {
        $requiredProperties = array_merge(
            $messagePropertiesValidationRequestTransfer->getRequiredProperties(),
            array_column($messagePropertiesValidationRequestTransfer->getRequiredArrayProperties(), 'path'),
        );

        return sprintf(
            'The message "%s" does not contain all required properties "%s". The following properties are missing "%s".',
            $messagePropertiesValidationRequestTransfer->getMessageName(),
            implode(', ', $requiredProperties),
            implode(', ', $missingProperties),
        );
    }
}
