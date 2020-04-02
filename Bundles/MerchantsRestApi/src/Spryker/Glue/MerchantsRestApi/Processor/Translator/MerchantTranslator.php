<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantsRestApi\Processor\Translator;

use Generated\Shared\Transfer\MerchantStorageTransfer;
use Spryker\Glue\MerchantsRestApi\Dependency\Client\MerchantsRestApiToGlossaryStorageClientInterface;

class MerchantTranslator implements MerchantTranslatorInterface
{
    /**
     * @var \Spryker\Glue\MerchantsRestApi\Dependency\Client\MerchantsRestApiToGlossaryStorageClientInterface
     */
    protected $glossaryStorageClient;

    /**
     * @param \Spryker\Glue\MerchantsRestApi\Dependency\Client\MerchantsRestApiToGlossaryStorageClientInterface $glossaryStorageClient
     */
    public function __construct(MerchantsRestApiToGlossaryStorageClientInterface $glossaryStorageClient)
    {
        $this->glossaryStorageClient = $glossaryStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantStorageTransfer $merchantStorageTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\MerchantStorageTransfer
     */
    public function getTranslatedMerchantStorageTransfer(MerchantStorageTransfer $merchantStorageTransfer, string $localeName): MerchantStorageTransfer
    {
        $glossaryStorageKeys = $this->getGlossaryStorageKeysFromMerchantStorageTransfer($merchantStorageTransfer);

        $translations = $this->glossaryStorageClient->translateBulk($glossaryStorageKeys, $localeName);

        return $this->setTranslationsToMerchantStorageTransfer($merchantStorageTransfer, $translations);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantStorageTransfer $merchantStorageTransfer
     *
     * @return array
     */
    protected function getGlossaryStorageKeysFromMerchantStorageTransfer(MerchantStorageTransfer $merchantStorageTransfer): array
    {
        return $merchantStorageTransfer->toArray();
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantStorageTransfer $merchantStorageTransfer
     * @param array $translations
     *
     * @return \Generated\Shared\Transfer\MerchantStorageTransfer
     */
    protected function setTranslationsToMerchantStorageTransfer(MerchantStorageTransfer $merchantStorageTransfer, array $translations): MerchantStorageTransfer
    {
        return $merchantStorageTransfer;
    }
}
