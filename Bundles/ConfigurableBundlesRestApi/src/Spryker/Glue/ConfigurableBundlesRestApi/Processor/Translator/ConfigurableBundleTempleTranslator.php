<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundlesRestApi\Processor\Translator;

use Spryker\Glue\ConfigurableBundlesRestApi\Dependency\Client\ConfigurableBundlesRestApiToGlossaryStorageClientInterface;

class ConfigurableBundleTempleTranslator implements ConfigurableBundleTempleTranslatorInterface
{
    /**
     * @var \Spryker\Glue\ConfigurableBundlesRestApi\Dependency\Client\ConfigurableBundlesRestApiToGlossaryStorageClientInterface
     */
    protected $glossaryStorageClient;

    /**
     * @param \Spryker\Glue\ConfigurableBundlesRestApi\Dependency\Client\ConfigurableBundlesRestApiToGlossaryStorageClientInterface $glossaryStorageClient
     */
    public function __construct(ConfigurableBundlesRestApiToGlossaryStorageClientInterface $glossaryStorageClient)
    {
        $this->glossaryStorageClient = $glossaryStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\RestConfigurableBundleTemplatesAttributesTransfer[] $restConfigurableBundleTemplatesAttributesTransfers
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\RestConfigurableBundleTemplatesAttributesTransfer[]
     */
    public function translateConfigurableBundleTemplateNames(
        array $restConfigurableBundleTemplatesAttributesTransfers,
        string $localeName
    ): array {
        $configurableBundleTemplateNames = [];

        foreach ($restConfigurableBundleTemplatesAttributesTransfers as $restConfigurableBundleTemplatesAttributesTransfer) {
            $configurableBundleTemplateNames[] = $restConfigurableBundleTemplatesAttributesTransfer->getName();
        }

        $translatedConfigurableBundleTemplateNames = $this->glossaryStorageClient
            ->translateBulk($configurableBundleTemplateNames, $localeName);

        foreach ($restConfigurableBundleTemplatesAttributesTransfers as $restConfigurableBundleTemplatesAttributesTransfer) {
            $restConfigurableBundleTemplatesAttributesTransfer->setName(
                $translatedConfigurableBundleTemplateNames[$restConfigurableBundleTemplatesAttributesTransfer->getName()]
            );
        }

        return $restConfigurableBundleTemplatesAttributesTransfers;
    }
}
