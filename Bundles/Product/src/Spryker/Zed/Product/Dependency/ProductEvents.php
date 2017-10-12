<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Dependency;

interface ProductEvents
{
    const PRODUCT_ABSTRACT_BEFORE_CREATE = 'Product.product_abstract.before.create';
    const PRODUCT_ABSTRACT_BEFORE_UPDATE = 'Product.product_abstract.before.update';

    const PRODUCT_CONCRETE_BEFORE_CREATE = 'Product.product_concrete.before.create';
    const PRODUCT_CONCRETE_BEFORE_UPDATE = 'Product.product_concrete.before.update';

    const PRODUCT_ABSTRACT_AFTER_UPDATE = 'Product.product_abstract.after.update';
    const PRODUCT_ABSTRACT_AFTER_CREATE = 'Product.product_abstract.after.create';

    const PRODUCT_CONCRETE_AFTER_CREATE = 'Product.product_concrete.after.create';
    const PRODUCT_CONCRETE_AFTER_UPDATE = 'Product.product_concrete.after.update';

    const PRODUCT_ABSTRACT_READ = 'Product.product_abstract.read';
    const PRODUCT_CONCRETE_READ = 'Product.product_concrete.read';
}
