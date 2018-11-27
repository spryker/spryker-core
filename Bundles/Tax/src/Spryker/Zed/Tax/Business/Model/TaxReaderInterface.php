<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Business\Model;

use Generated\Shared\Transfer\TaxRateTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;

interface TaxReaderInterface
{
    /**
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Generated\Shared\Transfer\TaxRateCollectionTransfer
     */
    public function getTaxRates();

    /**
     * @param int $id
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Zed\Tax\Business\Model\Exception\ResourceNotFoundException
     *
     * @return \Generated\Shared\Transfer\TaxRateTransfer
     */
    public function getTaxRate($id);

    /**
     * @param int $id
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return bool
     */
    public function taxRateExists($id);

    /**
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Generated\Shared\Transfer\TaxSetCollectionTransfer
     */
    public function getTaxSets();

    /**
     * @param int $id
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Zed\Tax\Business\Model\Exception\ResourceNotFoundException
     *
     * @return \Generated\Shared\Transfer\TaxSetTransfer
     */
    public function getTaxSet($id);

    /**
     * @param int $id
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return bool
     */
    public function taxSetExists($id);

    /**
     * @param string $name
     *
     * @return bool
     */
    public function taxSetWithSameNameExists(string $name): bool;

    /**
     * @param string $name
     * @param int $idTaxSet
     *
     * @return bool
     */
    public function taxSetWithSameNameAndIdExists(string $name, int $idTaxSet): bool;

    /**
     * @param int $idTaxRate
     *
     * @return \Generated\Shared\Transfer\TaxRateTransfer|null
     */
    public function findTaxRate(int $idTaxRate): ?TaxRateTransfer;

    /**
     * @param int $idTaxSet
     *
     * @return \Generated\Shared\Transfer\TaxSetTransfer|null
     */
    public function findTaxSet(int $idTaxSet): ?TaxSetTransfer;
}
