<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\Address\AddressType;

class AddressDataProvider extends AbstractAddressDataProvider
{

//    /**
//     * @return array
//     */
//    public function getOptions()
//    {
//        return [
//            'data_class' => QuoteTransfer::class
//        ];
//    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            AddressType::OPTION_COUNTRY_CHOICES => $this->getAvailableCountries(),
        ];
    }

//    /**
//     * @param int|null $idCustomerAddress
//     *
//     * @return array
//     */
//    public function getData($idCustomerAddress = null)
//    {
////        $customerTransfer = $this->customerClient->getCustomer();
////
////        if ($idCustomerAddress === null) {
////            return $this->getDefaultAddressData($customerTransfer);
////        }
////
////        $addressTransfer = $this->loadAddressTransfer($customerTransfer, $idCustomerAddress);
////        if ($addressTransfer !== null) {
////            return $addressTransfer->modifiedToArray();
////        }
//
//        return [];
//    }



}
