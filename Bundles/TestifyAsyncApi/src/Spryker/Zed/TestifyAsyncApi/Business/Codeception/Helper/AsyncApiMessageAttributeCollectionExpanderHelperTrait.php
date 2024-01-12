<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TestifyAsyncApi\Business\Codeception\Helper;

use Exception;
use Generated\Shared\Transfer\MessagePropertiesValidationRequestTransfer;
use SprykerSdk\AsyncApi\AsyncApi\Message\AsyncApiMessageInterface;
use SprykerSdk\AsyncApi\AsyncApi\Message\Attributes\AsyncApiMessageAttributeCollectionInterface;

trait AsyncApiMessageAttributeCollectionExpanderHelperTrait
{
    /**
     * @param \Generated\Shared\Transfer\MessagePropertiesValidationRequestTransfer $messagePropertiesValidationRequestTransfer
     * @param \SprykerSdk\AsyncApi\AsyncApi\Message\AsyncApiMessageInterface $message
     *
     * @return \Generated\Shared\Transfer\MessagePropertiesValidationRequestTransfer
     */
    protected function expandWithRequiredPayloadAttributes(
        MessagePropertiesValidationRequestTransfer $messagePropertiesValidationRequestTransfer,
        AsyncApiMessageInterface $message
    ): MessagePropertiesValidationRequestTransfer {
        /** @var \SprykerSdk\AsyncApi\AsyncApi\Message\Attributes\AsyncApiMessageAttributeCollectionInterface|null $payloadAttributeCollection */
        $payloadAttributeCollection = $message->getAttribute('payload');

        // In case we have a "marker" message without any payload then we can skip the required field validation.
        if (!$payloadAttributeCollection) {
            return $messagePropertiesValidationRequestTransfer;
        }

        $this->expandWithRequiredAttributeCollection($payloadAttributeCollection, $messagePropertiesValidationRequestTransfer);

        return $messagePropertiesValidationRequestTransfer;
    }

    /**
     * @param \SprykerSdk\AsyncApi\AsyncApi\Message\Attributes\AsyncApiMessageAttributeCollectionInterface $attributeCollection
     * @param \Generated\Shared\Transfer\MessagePropertiesValidationRequestTransfer $messagePropertiesValidationRequestTransfer
     * @param string $currentKey
     *
     * @return void
     */
    protected function expandWithRequiredAttributeCollection(
        AsyncApiMessageAttributeCollectionInterface $attributeCollection,
        MessagePropertiesValidationRequestTransfer $messagePropertiesValidationRequestTransfer,
        string $currentKey = ''
    ): void {
        /** @var \SprykerSdk\AsyncApi\AsyncApi\Message\Attributes\AsyncApiMessageAttributeCollectionInterface $propertiesAttributeCollection */
        $propertiesAttributeCollection = $attributeCollection->getAttribute('properties');

        /** @var \SprykerSdk\AsyncApi\AsyncApi\Message\Attributes\AsyncApiMessageAttributeCollectionInterface|null $requiredAttributeCollection */
        $requiredAttributeCollection = $attributeCollection->getAttribute('required');

        $isArray = $this->isArray($attributeCollection);
        if ($isArray && $attributeCollection->getAttribute('items')) {
            [$propertiesAttributeCollection, $requiredAttributeCollection] = $this->getPropertiesAndRequiredFromAttributeCollection($attributeCollection);
        }

        if (!$requiredAttributeCollection) {
            return;
        }

        $this->collectRequiredProperties(
            $requiredAttributeCollection,
            $propertiesAttributeCollection,
            $messagePropertiesValidationRequestTransfer,
            $currentKey,
            $isArray,
        );
    }

    /**
     * @param \SprykerSdk\AsyncApi\AsyncApi\Message\Attributes\AsyncApiMessageAttributeCollectionInterface $requiredAttributeCollection
     * @param \SprykerSdk\AsyncApi\AsyncApi\Message\Attributes\AsyncApiMessageAttributeCollectionInterface $propertiesAttributeCollection
     * @param \Generated\Shared\Transfer\MessagePropertiesValidationRequestTransfer $messagePropertiesValidationRequestTransfer
     * @param string $currentKey
     * @param bool $isArray
     *
     * @return void
     */
    protected function collectRequiredProperties(
        AsyncApiMessageAttributeCollectionInterface $requiredAttributeCollection,
        AsyncApiMessageAttributeCollectionInterface $propertiesAttributeCollection,
        MessagePropertiesValidationRequestTransfer $messagePropertiesValidationRequestTransfer,
        string $currentKey,
        bool $isArray
    ): void {
        foreach ($requiredAttributeCollection->getAttributes() as $requiredAttribute) {
            /** @var string $attributeValue */
            $attributeValue = $requiredAttribute->getValue();
            $key = $currentKey ? sprintf('%s.%s', $currentKey, $attributeValue) : $attributeValue;

            if ($isArray) {
                $messagePropertiesValidationRequestTransfer->addRequiredArrayProperty([
                    'parent' => $currentKey,
                    'path' => $key,
                    'property' => $attributeValue,
                ]);

                continue;
            }

            $this->processRegularAttribute(
                $propertiesAttributeCollection,
                $messagePropertiesValidationRequestTransfer,
                $key,
                $attributeValue,
            );
        }
    }

    /**
     * @param \SprykerSdk\AsyncApi\AsyncApi\Message\Attributes\AsyncApiMessageAttributeCollectionInterface $propertiesAttributeCollection
     * @param \Generated\Shared\Transfer\MessagePropertiesValidationRequestTransfer $messagePropertiesValidationRequestTransfer
     * @param string $key
     * @param string $attributeValue
     *
     * @return void
     */
    protected function processRegularAttribute(
        AsyncApiMessageAttributeCollectionInterface $propertiesAttributeCollection,
        MessagePropertiesValidationRequestTransfer $messagePropertiesValidationRequestTransfer,
        string $key,
        string $attributeValue
    ): void {
        $messagePropertiesValidationRequestTransfer->addRequiredProperty($key);

        if (!$this->hasCollectionProperty($propertiesAttributeCollection, $attributeValue)) {
            return;
        }

        $this->expandWithRequiredAttributeCollection(
            $this->getCollectionProperty($propertiesAttributeCollection, $attributeValue),
            $messagePropertiesValidationRequestTransfer,
            $key,
        );
    }

    /**
     * @param \SprykerSdk\AsyncApi\AsyncApi\Message\Attributes\AsyncApiMessageAttributeCollectionInterface $attributeCollection
     * @param string $lookupAttributeName
     *
     * @return bool
     */
    protected function hasCollectionProperty(AsyncApiMessageAttributeCollectionInterface $attributeCollection, string $lookupAttributeName): bool
    {
        foreach ($attributeCollection->getAttributes() as $attributeName => $attribute) {
            if ($attributeName === $lookupAttributeName) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \SprykerSdk\AsyncApi\AsyncApi\Message\Attributes\AsyncApiMessageAttributeCollectionInterface $attributeCollection
     * @param string $lookupPropertyName
     *
     * @throws \Exception
     *
     * @return \SprykerSdk\AsyncApi\AsyncApi\Message\Attributes\AsyncApiMessageAttributeCollectionInterface
     */
    protected function getCollectionProperty(
        AsyncApiMessageAttributeCollectionInterface $attributeCollection,
        string $lookupPropertyName
    ): AsyncApiMessageAttributeCollectionInterface {
        /** @var \SprykerSdk\AsyncApi\AsyncApi\Message\Attributes\AsyncApiMessageAttributeCollectionInterface $attribute */
        foreach ($attributeCollection->getAttributes() as $attributeName => $attribute) {
            if ($attributeName !== $lookupPropertyName) {
                continue;
            }

            return $attribute;
        }

        throw new Exception(sprintf('You MUST call "hasCollectionProperty" before "getCollectionProperty". Property "%s" not found in collection.', $lookupPropertyName));
    }

    /**
     * @param \SprykerSdk\AsyncApi\AsyncApi\Message\Attributes\AsyncApiMessageAttributeCollectionInterface $attributeCollection
     *
     * @return bool
     */
    protected function isArray(AsyncApiMessageAttributeCollectionInterface $attributeCollection): bool
    {
        /** @var \SprykerSdk\AsyncApi\AsyncApi\Message\Attributes\AsyncApiMessageAttributeInterface $attributeType */
        $attributeType = $attributeCollection->getAttribute('type');

        return $attributeType !== null && $attributeType->getValue() === 'array';
    }

    /**
     * @param \SprykerSdk\AsyncApi\AsyncApi\Message\Attributes\AsyncApiMessageAttributeCollectionInterface $attributeCollection
     *
     * @return array<int, mixed>
     */
    protected function getPropertiesAndRequiredFromAttributeCollection(AsyncApiMessageAttributeCollectionInterface $attributeCollection): array
    {
        /** @var \SprykerSdk\AsyncApi\AsyncApi\Message\Attributes\AsyncApiMessageAttributeCollectionInterface $itemsAttributeCollection */
        $itemsAttributeCollection = $attributeCollection->getAttribute('items');

        /** @var \SprykerSdk\AsyncApi\AsyncApi\Message\Attributes\AsyncApiMessageAttributeCollectionInterface $propertiesAttributeCollection */
        $propertiesAttributeCollection = $itemsAttributeCollection->getAttribute('properties');

        /** @var \SprykerSdk\AsyncApi\AsyncApi\Message\Attributes\AsyncApiMessageAttributeCollectionInterface|null $requiredAttributeCollection */
        $requiredAttributeCollection = $itemsAttributeCollection->getAttribute('required');

        return [$propertiesAttributeCollection, $requiredAttributeCollection];
    }
}
