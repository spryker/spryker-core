<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\RestConfiguredBundleItemTransfer;
use Generated\Shared\Transfer\RestConfiguredBundleTransfer;
use Generated\Shared\Transfer\RestItemsAttributesTransfer;
use Generated\Shared\Transfer\RestOrderItemsAttributesTransfer;
use Generated\Shared\Transfer\RestSalesOrderConfiguredBundleItemTransfer;
use Generated\Shared\Transfer\RestSalesOrderConfiguredBundleTransfer;
use Spryker\Glue\ConfigurableBundleCartsRestApi\Dependency\Client\ConfigurableBundleCartsRestApiToGlossaryStorageClientInterface;

class ItemMapper implements ItemMapperInterface
{
    /**
     * @var \Spryker\Glue\ConfigurableBundleCartsRestApi\Dependency\Client\ConfigurableBundleCartsRestApiToGlossaryStorageClientInterface
     */
    protected $glossaryStorageClient;

    /**
     * @param \Spryker\Glue\ConfigurableBundleCartsRestApi\Dependency\Client\ConfigurableBundleCartsRestApiToGlossaryStorageClientInterface $glossaryStorageClient
     */
    public function __construct(ConfigurableBundleCartsRestApiToGlossaryStorageClientInterface $glossaryStorageClient)
    {
        $this->glossaryStorageClient = $glossaryStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\RestItemsAttributesTransfer $restItemsAttributesTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\RestItemsAttributesTransfer
     */
    public function mapItemTransferToRestItemsAttributesTransfer(
        ItemTransfer $itemTransfer,
        RestItemsAttributesTransfer $restItemsAttributesTransfer,
        string $localeName
    ): RestItemsAttributesTransfer {
        if (!$itemTransfer->getConfiguredBundle() || !$itemTransfer->getConfiguredBundleItem()) {
            return $restItemsAttributesTransfer;
        }

        $restConfiguredBundleTransfer = (new RestConfiguredBundleTransfer())
            ->fromArray($itemTransfer->getConfiguredBundle()->toArray(), true);

        $restConfiguredBundleItemTransfer = (new RestConfiguredBundleItemTransfer())
            ->fromArray($itemTransfer->getConfiguredBundleItem()->toArray(), true);

        $restItemsAttributesTransfer
            ->setConfiguredBundle($restConfiguredBundleTransfer)
            ->setConfiguredBundleItem($restConfiguredBundleItemTransfer);

        return $this->translateConfiguredBundleTemplate($restItemsAttributesTransfer, $localeName);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\RestOrderItemsAttributesTransfer $restOrderItemsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestOrderItemsAttributesTransfer
     */
    public function mapItemToRestOrderItemsAttributes(
        ItemTransfer $itemTransfer,
        RestOrderItemsAttributesTransfer $restOrderItemsAttributesTransfer
    ): RestOrderItemsAttributesTransfer {
        if (!$itemTransfer->getSalesOrderConfiguredBundle() || !$itemTransfer->getSalesOrderConfiguredBundleItem()) {
            return $restOrderItemsAttributesTransfer;
        }

        $restSalesOrderConfiguredBundleTransfer = (new RestSalesOrderConfiguredBundleTransfer())
            ->fromArray($itemTransfer->getSalesOrderConfiguredBundle()->toArray(), true);

        $restSalesOrderConfiguredBundleTransfer = $this->copyTranslatedTemplateName($itemTransfer, $restSalesOrderConfiguredBundleTransfer);

        $restSalesOrderConfiguredBundleItemTransfer = (new RestSalesOrderConfiguredBundleItemTransfer())
            ->fromArray($itemTransfer->getSalesOrderConfiguredBundleItem()->toArray(), true);

        $restOrderItemsAttributesTransfer
            ->setSalesOrderConfiguredBundle($restSalesOrderConfiguredBundleTransfer)
            ->setSalesOrderConfiguredBundleItem($restSalesOrderConfiguredBundleItemTransfer);

        return $restOrderItemsAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RestItemsAttributesTransfer $restItemsAttributesTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\RestItemsAttributesTransfer
     */
    public function translateConfiguredBundleTemplate(
        RestItemsAttributesTransfer $restItemsAttributesTransfer,
        string $localeName
    ): RestItemsAttributesTransfer {
        $templateName = $restItemsAttributesTransfer->getConfiguredBundle()
            ->getTemplate()
            ->getName();

        if (!$templateName) {
            return $restItemsAttributesTransfer;
        }

        $translations = $this->glossaryStorageClient->translateBulk([$templateName], $localeName);

        $restItemsAttributesTransfer->getConfiguredBundle()
            ->getTemplate()
            ->setName($translations[$templateName]);

        return $restItemsAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\RestSalesOrderConfiguredBundleTransfer $restSalesOrderConfiguredBundleTransfer
     *
     * @return \Generated\Shared\Transfer\RestSalesOrderConfiguredBundleTransfer
     */
    protected function copyTranslatedTemplateName(
        ItemTransfer $itemTransfer,
        RestSalesOrderConfiguredBundleTransfer $restSalesOrderConfiguredBundleTransfer
    ): RestSalesOrderConfiguredBundleTransfer {
        if (!$itemTransfer->getSalesOrderConfiguredBundle()->getTranslations()->offsetExists(0)) {
            return $restSalesOrderConfiguredBundleTransfer;
        }

        return $restSalesOrderConfiguredBundleTransfer->setName(
            $itemTransfer->getSalesOrderConfiguredBundle()->getTranslations()->offsetGet(0)->getName()
        );
    }
}
