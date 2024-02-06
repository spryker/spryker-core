<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantAgentGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantAgentGui\Communication\Expander\MerchantAgentUserFormExpander;
use Spryker\Zed\MerchantAgentGui\Communication\Expander\MerchantAgentUserFormExpanderInterface;
use Spryker\Zed\MerchantAgentGui\Communication\Expander\MerchantAgentUserTableConfigExpander;
use Spryker\Zed\MerchantAgentGui\Communication\Expander\MerchantAgentUserTableConfigExpanderInterface;
use Spryker\Zed\MerchantAgentGui\Communication\Expander\MerchantAgentUserTableDataExpander;
use Spryker\Zed\MerchantAgentGui\Communication\Expander\MerchantAgentUserTableDataExpanderInterface;

class MerchantAgentGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\MerchantAgentGui\Communication\Expander\MerchantAgentUserFormExpanderInterface
     */
    public function createMerchantAgentUserFormExpander(): MerchantAgentUserFormExpanderInterface
    {
        return new MerchantAgentUserFormExpander();
    }

    /**
     * @return \Spryker\Zed\MerchantAgentGui\Communication\Expander\MerchantAgentUserTableConfigExpanderInterface
     */
    public function createMerchantAgentUserTableConfigExpander(): MerchantAgentUserTableConfigExpanderInterface
    {
        return new MerchantAgentUserTableConfigExpander();
    }

    /**
     * @return \Spryker\Zed\MerchantAgentGui\Communication\Expander\MerchantAgentUserTableDataExpanderInterface
     */
    public function createMerchantAgentUserTableDataExpander(): MerchantAgentUserTableDataExpanderInterface
    {
        return new MerchantAgentUserTableDataExpander();
    }
}
