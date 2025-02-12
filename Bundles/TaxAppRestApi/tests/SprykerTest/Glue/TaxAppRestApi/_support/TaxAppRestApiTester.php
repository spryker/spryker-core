<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Glue\TaxAppRestApi;

use Codeception\Actor;
use Generated\Shared\DataBuilder\RestTaxAppValidationAttributesBuilder;
use Generated\Shared\Transfer\RestTaxAppValidationAttributesTransfer;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(PHPMD)
 */
class TaxAppRestApiTester extends Actor
{
    use _generated\TaxAppRestApiTesterActions;

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\RestTaxAppValidationAttributesTransfer
     */
    public function createRestTaxAppValidationAttributesTransfer(array $seed = []): RestTaxAppValidationAttributesTransfer
    {
        return (new RestTaxAppValidationAttributesBuilder())->seed($seed)->build();
    }
}
