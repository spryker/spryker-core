<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount;

interface DiscountConfigInterface
{

    const KEY_VOUCHER_CODE_CONSONANTS = 'consonants';
    const KEY_VOUCHER_CODE_VOWELS = 'vowels';
    const KEY_VOUCHER_CODE_NUMBERS = 'numbers';

    /**
     * @return array
     */
    public function getVoucherCodeCharacters();

    /**
     * @return int
     */
    public function getVoucherCodeLength();

    /**
     * @return string
     */
    public function getVoucherPoolTemplateReplacementString();

    /**
     * @return int
     */
    public function getAllowedCodeCharactersLength();

}
