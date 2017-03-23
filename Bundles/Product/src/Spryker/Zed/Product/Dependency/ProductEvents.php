<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Dependency;

interface ProductEvents
{

    const PRODUCT_ABSTRACT_BEFORE_CREATE = 'ProductAbstract.before.create';
    const PRODUCT_ABSTRACT_BEFORE_UPDATE = 'ProductAbstract.before.update';

    const PRODUCT_CONCRETE_BEFORE_CREATE = 'ProductConcrete.before.create';
    const PRODUCT_CONCRETE_BEFORE_UPDATE = 'ProductConcrete.before.update';

    const PRODUCT_ABSTRACT_AFTER_UPDATE = 'ProductAbstract.after.update';
    const PRODUCT_ABSTRACT_AFTER_CREATE = 'ProductAbstract.after.create';

    const PRODUCT_CONCRETE_AFTER_CREATE = 'ProductConcrete.after.create';
    const PRODUCT_CONCRETE_AFTER_UPDATE = 'ProductConcrete.after.update';

    const PRODUCT_ABSTRACT_RED = 'ProductAbstract.read';
    const PRODUCT_CONCRETE_READ = 'ProductConcrete.read';

}
