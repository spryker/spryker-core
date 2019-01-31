<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\MerchantGui\Communication\Form\MerchantForm;
use Spryker\Zed\MerchantGui\Communication\Form\MerchantUpdateForm;
use Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToMerchantFacadeInterface;
use Spryker\Zed\MerchantGui\MerchantGuiConfig;

class MerchantFormDataProvider
{
    /**
     * @var \Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToMerchantFacadeInterface
     */
    protected $merchantFacade;

    /**
     * @var \Spryker\Zed\MerchantGui\MerchantGuiConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToMerchantFacadeInterface $merchantFacade
     * @param \Spryker\Zed\MerchantGui\MerchantGuiConfig $config
     */
    public function __construct(
        MerchantGuiToMerchantFacadeInterface $merchantFacade,
        MerchantGuiConfig $config
    ) {
        $this->merchantFacade = $merchantFacade;
        $this->config = $config;
    }

    /**
     * @param int|null $idMerchant
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer|null
     */
    public function getData(?int $idMerchant = null): ?MerchantTransfer
    {
        $merchantTransfer = new MerchantTransfer();
        if (!$idMerchant) {
            return $merchantTransfer;
        }

        $merchantTransfer->setIdMerchant($idMerchant);

        return $this->merchantFacade->findMerchantById($merchantTransfer);
    }

    /**
     * @param string|null $currentStatus
     *
     * @return array
     */
    public function getOptions(?string $currentStatus = null): array
    {
        $options = [
            'data_class' => MerchantTransfer::class,
            MerchantForm::SALUTATION_CHOICES_OPTION => $this->config->getSalutationChoices(),
        ];

        if ($currentStatus !== null) {
            $options += [
                MerchantUpdateForm::OPTION_STATUS_CHOICES => $this->merchantFacade->getNextStatuses($currentStatus),
            ];
        }

        return $options;
    }
}
