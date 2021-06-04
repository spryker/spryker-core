<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteExtension\Dependency\Plugin;

interface QuotePostExpanderPluginInterface
{
    /**
     * Specification:
     * - Method is executed after {@link \Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteExpanderPluginInterface::expand() }.
     *
     * @api
     *
     * @return void
     */
    public function postExpand(): void;
}
