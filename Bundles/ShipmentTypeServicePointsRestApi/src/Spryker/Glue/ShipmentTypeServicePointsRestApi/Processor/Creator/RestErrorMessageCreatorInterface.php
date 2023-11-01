<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypeServicePointsRestApi\Processor\Creator;

use Generated\Shared\Transfer\RestErrorMessageTransfer;

interface RestErrorMessageCreatorInterface
{
    /**
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    public function createServicePointNotProvidedErrorMessage(): RestErrorMessageTransfer;

    /**
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    public function createServicePointShouldNotBeProvidedErrorMessage(): RestErrorMessageTransfer;

    /**
     * @param string $itemGroupKey
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    public function createServicePointForItemShouldNotBeProvidedErrorMessage(string $itemGroupKey): RestErrorMessageTransfer;

    /**
     * @param string $servicePointUuid
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    public function createServicePointAddressMissingErrorMessage(string $servicePointUuid): RestErrorMessageTransfer;

    /**
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    public function createCustomerDataMissingErrorMessage(): RestErrorMessageTransfer;

    /**
     * @param list<string> $itemGroupKeys
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    public function createItemShippingAddressMissingErrorMessage(array $itemGroupKeys): RestErrorMessageTransfer;

    /**
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    public function createOnlyOneServicePointShouldBeSelectedErrorMessage(): RestErrorMessageTransfer;
}
