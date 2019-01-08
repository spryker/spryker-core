<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\BusinessOnBehalfGui\Communication\ButtonCreator;

use Generated\Shared\Transfer\ButtonTransfer;

interface CompanyUserTableButtonCreatorInterface
{
    /**
     * @param array $companyUserTableRowItem
     * @param string[] $buttons
     *
     * @return \Generated\Shared\Transfer\ButtonTransfer
     */
    public function addAttachCustomerToBusinessUnitButton(array $companyUserTableRowItem, array $buttons): ButtonTransfer;
}
