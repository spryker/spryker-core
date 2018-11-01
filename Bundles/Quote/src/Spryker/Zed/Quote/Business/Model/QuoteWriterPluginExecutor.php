<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Quote\Business\Model;

use Generated\Shared\Transfer\QuoteTransfer;

class QuoteWriterPluginExecutor implements QuoteWriterPluginExecutorInterface
{
    /**
     * @var array|\Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteWritePluginInterface[]
     */
    protected $quoteCreateBeforePlugins;

    /**
     * @var array|\Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteWritePluginInterface[]
     */
    protected $quoteCreateAfterPlugins;

    /**
     * @var array|\Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteWritePluginInterface[]
     */
    protected $quoteUpdateBeforePlugins;

    /**
     * @var array|\Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteWritePluginInterface[]
     */
    protected $quoteUpdateAfterPlugin;

    /**
     * @param \Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteWritePluginInterface[] $quoteCreateBeforePlugins
     * @param \Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteWritePluginInterface[] $quoteCreateAfterPlugins
     * @param \Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteWritePluginInterface[] $quoteUpdateBeforePlugins
     * @param \Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteWritePluginInterface[] $quoteUpdateAfterPlugin
     */
    public function __construct(
        array $quoteCreateBeforePlugins,
        array $quoteCreateAfterPlugins,
        array $quoteUpdateBeforePlugins,
        array $quoteUpdateAfterPlugin
    ) {
        $this->quoteCreateBeforePlugins = $quoteCreateBeforePlugins;
        $this->quoteCreateAfterPlugins = $quoteCreateAfterPlugins;
        $this->quoteUpdateBeforePlugins = $quoteUpdateBeforePlugins;
        $this->quoteUpdateAfterPlugin = $quoteUpdateAfterPlugin;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function executeCreateBeforePlugins(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->executePlugins($this->quoteCreateBeforePlugins, $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function executeCreateAfterPlugins(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->executePlugins($this->quoteCreateAfterPlugins, $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function executeUpdateBeforePlugins(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->executePlugins($this->quoteUpdateBeforePlugins, $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function executeUpdateAfterPlugins(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->executePlugins($this->quoteUpdateAfterPlugin, $quoteTransfer);
    }

    /**
     * @param \Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteWritePluginInterface[] $quoteWritePlugins
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function executePlugins(array $quoteWritePlugins, QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        foreach ($quoteWritePlugins as $quoteWritePlugin) {
            $quoteTransfer = $quoteWritePlugin->execute($quoteTransfer);
        }

        return $quoteTransfer;
    }
}
