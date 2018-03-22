<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Business\Model;

use Generated\Shared\Transfer\TaxRateTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;

interface TaxWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\TaxRateTransfer $taxRateTransfer
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Generated\Shared\Transfer\TaxRateTransfer
     */
    public function createTaxRate(TaxRateTransfer $taxRateTransfer);

    /**
     * @param \Generated\Shared\Transfer\TaxRateTransfer $taxRateTransfer
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Zed\Tax\Business\Model\Exception\ResourceNotFoundException
     *
     * @return int
     */
    public function updateTaxRate(TaxRateTransfer $taxRateTransfer);

    /**
     * @param \Generated\Shared\Transfer\TaxSetTransfer $taxSetTransfer
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Zed\Tax\Business\Model\Exception\ResourceNotFoundException
     * @throws \Spryker\Zed\Tax\Business\Model\Exception\MissingTaxRateException
     *
     * @return \Generated\Shared\Transfer\TaxSetTransfer
     */
    public function createTaxSet(TaxSetTransfer $taxSetTransfer);

    /**
     * @param \Generated\Shared\Transfer\TaxSetTransfer $taxSetTransfer
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Zed\Tax\Business\Model\Exception\ResourceNotFoundException
     * @throws \Spryker\Zed\Tax\Business\Model\Exception\MissingTaxRateException
     *
     * @return int
     */
    public function updateTaxSet(TaxSetTransfer $taxSetTransfer);

    /**
     * @param int $taxSetId
     * @param \Generated\Shared\Transfer\TaxRateTransfer $taxRateTransfer
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Zed\Tax\Business\Model\Exception\ResourceNotFoundException
     *
     * @return int|null
     */
    public function addTaxRateToTaxSet($taxSetId, TaxRateTransfer $taxRateTransfer);

    /**
     * @param int $taxSetId
     * @param int $taxRateId
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Zed\Tax\Business\Model\Exception\ResourceNotFoundException
     * @throws \Spryker\Zed\Tax\Business\Model\Exception\MissingTaxRateException
     *
     * @return int|null
     */
    public function removeTaxRateFromTaxSet($taxSetId, $taxRateId);

    /**
     * @param int $id
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return void
     */
    public function deleteTaxRate($id);

    /**
     * @param int $id
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return void
     */
    public function deleteTaxSet($id);
}
