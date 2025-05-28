<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductOfferGui;

/**
 * Represents statuses for product offers
 */
enum ProductOfferStatusEnum: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
}
