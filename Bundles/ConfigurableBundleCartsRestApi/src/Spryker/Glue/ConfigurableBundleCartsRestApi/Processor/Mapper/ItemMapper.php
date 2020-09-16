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
}
