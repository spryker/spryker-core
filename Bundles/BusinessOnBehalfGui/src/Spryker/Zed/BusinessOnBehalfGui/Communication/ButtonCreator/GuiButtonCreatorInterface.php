<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\BusinessOnBehalfGui\Communication\ButtonCreator;

interface GuiButtonCreatorInterface
{
    /**
     * @param array $companyUserDataItem
     * @param string[] $actionButtons
     *
     * @return string[]
     */
    public function addDeleteButtonForCompanyUserTable(array $companyUserDataItem, array $actionButtons): array;

    /**
     * @param array $companyUserDataItem
     * @param string[] $actionButtons
     *
     * @return string[]
     */
    public function addAttachToBusinessUnitButtonForCompanyUserTable(array $companyUserDataItem, array $actionButtons): array;
}
