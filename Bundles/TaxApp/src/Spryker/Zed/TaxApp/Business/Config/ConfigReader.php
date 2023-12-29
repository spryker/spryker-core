<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxApp\Business\Config;

use Generated\Shared\Transfer\TaxAppConfigConditionsTransfer;
use Generated\Shared\Transfer\TaxAppConfigCriteriaTransfer;
use Generated\Shared\Transfer\TaxAppConfigTransfer;
use Spryker\Zed\TaxApp\Persistence\TaxAppRepositoryInterface;

class ConfigReader implements ConfigReaderInterface
{
    /**
     * @var \Spryker\Zed\TaxApp\Persistence\TaxAppRepositoryInterface
     */
    protected TaxAppRepositoryInterface $taxAppRepository;

    /**
     * @param \Spryker\Zed\TaxApp\Persistence\TaxAppRepositoryInterface $taxAppRepository
     */
    public function __construct(TaxAppRepositoryInterface $taxAppRepository)
    {
        $this->taxAppRepository = $taxAppRepository;
    }

    /**
     * @param int $idStore
     *
     * @return \Generated\Shared\Transfer\TaxAppConfigTransfer|null
     */
    public function getTaxAppConfigByIdStore(int $idStore): ?TaxAppConfigTransfer
    {
        $taxAppConfigConditionsTransfer = new TaxAppConfigConditionsTransfer();
        $taxAppConfigConditionsTransfer->addFkStore($idStore);

        $taxAppConfigCriteriaTransfer = (new TaxAppConfigCriteriaTransfer())->setTaxAppConfigConditions($taxAppConfigConditionsTransfer);

        $taxAppConfigCollectionTransfer = $this->taxAppRepository->getTaxAppConfigCollection($taxAppConfigCriteriaTransfer);

        if (!$taxAppConfigCollectionTransfer->getTaxAppConfigs()->count()) {
            return null;
        }

        return $taxAppConfigCollectionTransfer->getTaxAppConfigs()->offsetGet(0);
    }
}
