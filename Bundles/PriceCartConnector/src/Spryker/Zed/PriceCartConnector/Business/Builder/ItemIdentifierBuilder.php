<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceCartConnector\Business\Builder;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\PriceCartConnector\Business\Exception\TransferPropertyNotFoundException;
use Spryker\Zed\PriceCartConnector\Dependency\Service\PriceCartConnectorToUtilEncodingServiceInterface;
use Spryker\Zed\PriceCartConnector\Dependency\Service\PriceCartConnectorToUtilTextServiceInterface;
use Spryker\Zed\PriceCartConnector\PriceCartConnectorConfig;

class ItemIdentifierBuilder implements ItemIdentifierBuilderInterface
{
    /**
     * @uses \Spryker\Service\UtilText\Model\Hash::MD5
     *
     * @var string
     */
    protected const MD5 = 'md5';

    /**
     * @var \Spryker\Zed\PriceCartConnector\PriceCartConnectorConfig
     */
    protected PriceCartConnectorConfig $priceCartConnectorConfig;

    /**
     * @var \Spryker\Zed\PriceCartConnector\Dependency\Service\PriceCartConnectorToUtilEncodingServiceInterface
     */
    protected PriceCartConnectorToUtilEncodingServiceInterface $utilEncodingService;

    /**
     * @var \Spryker\Zed\PriceCartConnector\Dependency\Service\PriceCartConnectorToUtilTextServiceInterface
     */
    protected PriceCartConnectorToUtilTextServiceInterface $utilTextService;

    /**
     * @param \Spryker\Zed\PriceCartConnector\PriceCartConnectorConfig $priceCartConnectorConfig
     * @param \Spryker\Zed\PriceCartConnector\Dependency\Service\PriceCartConnectorToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Zed\PriceCartConnector\Dependency\Service\PriceCartConnectorToUtilTextServiceInterface $utilTextService
     */
    public function __construct(
        PriceCartConnectorConfig $priceCartConnectorConfig,
        PriceCartConnectorToUtilEncodingServiceInterface $utilEncodingService,
        PriceCartConnectorToUtilTextServiceInterface $utilTextService
    ) {
        $this->priceCartConnectorConfig = $priceCartConnectorConfig;
        $this->utilEncodingService = $utilEncodingService;
        $this->utilTextService = $utilTextService;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    public function buildItemIdentifier(ItemTransfer $itemTransfer): string
    {
        $itemFieldsForIdentifier = $this->getItemFieldsForIdentifier($itemTransfer);
        if ($itemFieldsForIdentifier === []) {
            return '';
        }

        return $this->utilTextService->hashValue(
            $this->utilEncodingService->encodeJson($itemFieldsForIdentifier),
            static::MD5,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @throws \Spryker\Zed\PriceCartConnector\Business\Exception\TransferPropertyNotFoundException
     *
     * @return array<string, mixed>
     */
    protected function getItemFieldsForIdentifier(ItemTransfer $itemTransfer): array
    {
        $itemFieldsForIdentifier = [];
        foreach ($this->priceCartConnectorConfig->getItemFieldsForIdentifier() as $fieldName) {
            if (!$itemTransfer->offsetExists($fieldName)) {
                throw new TransferPropertyNotFoundException(
                    sprintf(
                        'The property "%s" can\'t be found in ItemTransfer.',
                        $fieldName,
                    ),
                );
            }

            $itemFieldsForIdentifier[$fieldName] = $itemTransfer[$fieldName];
        }

        return $itemFieldsForIdentifier;
    }
}
