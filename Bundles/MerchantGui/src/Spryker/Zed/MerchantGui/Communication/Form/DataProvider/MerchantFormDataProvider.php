<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\MerchantTransfer;
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
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function getData(?int $idMerchant = null): MerchantTransfer
    {
        $merchantTransfer = $this->createMerchantTransfer();
        if (!$idMerchant) {
            return $merchantTransfer;
        }

        $merchantTransfer->setIdMerchant($idMerchant);

        return $this->merchantFacade->getMerchantById($merchantTransfer);
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            'data_class' => MerchantTransfer::class,
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    protected function createMerchantTransfer(): MerchantTransfer
    {
        return new MerchantTransfer();
    }
}
