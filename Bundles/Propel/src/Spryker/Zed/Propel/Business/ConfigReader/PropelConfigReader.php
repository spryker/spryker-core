<?php


namespace Spryker\Zed\Propel\Business\ConfigReader;


use Spryker\Zed\Propel\PropelConfig;

class PropelConfigReader implements PropelConfigReaderInterface
{
    /**
     * @var \Spryker\Zed\Propel\PropelConfig
     */
    protected $propelConfig;

    /**
     * @param \Spryker\Zed\Propel\PropelConfig $propelConfig
     */
    public function __construct(PropelConfig $propelConfig)
    {
        $this->propelConfig = $propelConfig;
    }

    /**
     * @return string
     */
    public function getSchemaDirectory(): string
    {
        return  $this->propelConfig->getSchemaDirectory();
    }
}
