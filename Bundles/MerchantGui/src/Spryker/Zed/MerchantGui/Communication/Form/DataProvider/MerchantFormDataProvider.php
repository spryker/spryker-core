<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\MerchantGui\Communication\Form\MerchantUpdateForm;
use Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToMerchantFacadeInterface;

class MerchantFormDataProvider
{
    /**
     * @var \Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToMerchantFacadeInterface
     */
    protected $merchantFacade;

    /**
     * @param \Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToMerchantFacadeInterface $merchantFacade
     */
    public function __construct(MerchantGuiToMerchantFacadeInterface $merchantFacade)
    {
        $this->merchantFacade = $merchantFacade;
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
        $options = ['data_class' => MerchantTransfer::class];

        if ($currentStatus !== null) {
            $options = [
                MerchantUpdateForm::OPTION_STATUS_CHOICES => $this->merchantFacade->getNextStatuses($currentStatus),
            ];
        }

        return $options;
    }
}
