<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Communication\Form\DataProvider;

use Generated\Shared\Transfer\TaxSetTransfer;
use Spryker\Zed\Tax\Business\TaxFacadeInterface;
use Spryker\Zed\Tax\Communication\Form\TaxSetForm;

class TaxSetFormDataProvider
{
    /**
     * @var \Spryker\Zed\Tax\Business\TaxFacadeInterface
     */
    protected $taxFacade;

    /**
     * @var \Generated\Shared\Transfer\TaxSetTransfer
     */
    protected $taxSetTransfer;

    /**
     * @param \Spryker\Zed\Tax\Business\TaxFacadeInterface $taxFacade
     * @param \Generated\Shared\Transfer\TaxSetTransfer|null $taxSetTransfer
     */
    public function __construct(TaxFacadeInterface $taxFacade, ?TaxSetTransfer $taxSetTransfer = null)
    {
        $this->taxFacade = $taxFacade;
        $this->taxSetTransfer = $taxSetTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\TaxSetTransfer|null
     */
    public function getData()
    {
        return $this->taxSetTransfer;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            TaxSetForm::FIELD_TAX_RATES => $this->createTaxRatesList(),
        ];
    }

    /**
     * @return array
     */
    protected function createTaxRatesList()
    {
        $taxRateCollection = $this->taxFacade->getTaxRates();

        return $taxRateCollection->getTaxRates();
    }
}
