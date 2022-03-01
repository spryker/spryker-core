<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\Transfer;

class AbstractAttributesTransfer extends AbstractTransfer
{
    /**
     * @var string|null
     */
    protected ?string $transferType = null;

    /**
     * @var \Spryker\Shared\Kernel\Transfer\AbstractTransfer|null
     */
    protected ?AbstractTransfer $transfer = null;

    /**
     * @param array<string, mixed> $data
     * @param bool $ignoreMissingProperty
     *
     * @return $this
     */
    public function fromArray(array $data, $ignoreMissingProperty = false)
    {
        if (!isset($data['transfer']) && !$this->transfer) {
            return $this;
        }

        if (isset($data['transfer']) && !$this->transfer) {
            $transferAttributesType = $this->getAttributesTransferType($data['transfer']);
            if ($transferAttributesType) {
                $this->setAbstractAttributesType($transferAttributesType);
            }
        }

        if ($this->transfer) {
            $this->transfer->fromArray($data, $ignoreMissingProperty);
        }

        return $this;
    }

    /**
     * @param bool $isRecursive
     * @param bool $camelCasedKeys Set to true for camelCased keys, defaults to under_scored keys.
     *
     * @return array
     */
    public function toArray($isRecursive = true, $camelCasedKeys = false): array
    {
        if (!$this->transfer) {
            return [];
        }

        return $this->transfer->toArray($isRecursive, $camelCasedKeys);
    }

    /**
     * @param bool $isRecursive
     * @param bool $camelCasedKeys
     *
     * @return array<string, mixed>
     */
    public function modifiedToArray($isRecursive = true, $camelCasedKeys = false): array
    {
        if (!$this->transfer) {
            return [];
        }

        return $this->transfer->modifiedToArray($isRecursive, $camelCasedKeys);
    }

    /**
     * @param string $transferAttributesType
     *
     * @return $this
     */
    public function setAbstractAttributesType(string $transferAttributesType)
    {
        if (!is_subclass_of($transferAttributesType, AbstractTransfer::class)) {
            return $this;
        }

        $this->transferType = $transferAttributesType;
        $this->transfer = new $transferAttributesType();

        return $this;
    }

    /**
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface|null
     */
    public function getValueTransfer(): ?TransferInterface
    {
        return $this->transfer;
    }

    /**
     * @param string $transferName
     *
     * @return string|null
     */
    protected function getAttributesTransferType(string $transferName): ?string
    {
        $className = '\\Generated\\Shared\\Transfer\\' . $transferName;
        if (!class_exists($className) || !is_subclass_of($className, AbstractTransfer::class)) {
            return null;
        }

        return $className;
    }
}
