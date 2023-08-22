<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ApiKeyGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ApiKeyConditionsTransfer;
use Generated\Shared\Transfer\ApiKeyCriteriaTransfer;
use Spryker\Zed\ApiKeyGui\Dependency\Facade\ApiKeyGuiToApiKeyFacadeInterface;

class EditApiKeyFormDataProvider
{
    /**
     * @var \Spryker\Zed\ApiKeyGui\Dependency\Facade\ApiKeyGuiToApiKeyFacadeInterface
     */
    protected ApiKeyGuiToApiKeyFacadeInterface $apiKeyFacade;

    /**
     * @param \Spryker\Zed\ApiKeyGui\Dependency\Facade\ApiKeyGuiToApiKeyFacadeInterface $apiKeyFacade
     */
    public function __construct(ApiKeyGuiToApiKeyFacadeInterface $apiKeyFacade)
    {
        $this->apiKeyFacade = $apiKeyFacade;
    }

    /**
     * @param int $idApiKey
     *
     * @return array<mixed>|null
     */
    public function getData(int $idApiKey): ?array
    {
        $apiKeyCriteriaTransfer = (new ApiKeyCriteriaTransfer())
            ->setApiKeyConditions(
                (new ApiKeyConditionsTransfer())
                    ->addIdApiKey($idApiKey),
            );

        $apiKeyCollectionResponseTransfer = $this->apiKeyFacade->getApiKeyCollection($apiKeyCriteriaTransfer);

        if ($apiKeyCollectionResponseTransfer->getApiKeys()->count() === 0) {
            return null;
        }

        return $apiKeyCollectionResponseTransfer->getApiKeys()->offsetGet(0)->toArray();
    }
}
