<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Service\Mapper;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\ServiceDateTimeEnabledProductConcreteForm;

class ServiceDateTimeProductFormMapper implements ServiceDateTimeProductFormMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param array<string, mixed> $formData
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function mapServiceDateTimeFormDataToProductConcrete(
        ProductConcreteTransfer $productConcreteTransfer,
        array $formData
    ): ProductConcreteTransfer {
        if (isset($formData[ServiceDateTimeEnabledProductConcreteForm::FIELD_IS_SERVICE_DATE_TIME_ENABLED])) {
            $productConcreteTransfer->setIsServiceDateTimeEnabled(
                (bool)$formData[ServiceDateTimeEnabledProductConcreteForm::FIELD_IS_SERVICE_DATE_TIME_ENABLED],
            );
        }

        return $productConcreteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param array<string, mixed> $formData
     *
     * @return array<string, mixed>
     */
    public function mapProductConcreteServiceDateTimeToFormData(
        ProductConcreteTransfer $productConcreteTransfer,
        array $formData
    ): array {
        $formData[ServiceDateTimeEnabledProductConcreteForm::FIELD_IS_SERVICE_DATE_TIME_ENABLED] = $productConcreteTransfer->getIsServiceDateTimeEnabled();

        return $formData;
    }
}
