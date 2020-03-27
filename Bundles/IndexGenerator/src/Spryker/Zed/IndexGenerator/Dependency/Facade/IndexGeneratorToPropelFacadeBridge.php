<?php


namespace Spryker\Zed\IndexGenerator\Dependency\Facade;


class IndexGeneratorToPropelFacadeBridge implements IndexGeneratorToPropelFacadeInterface
{
    /**
     * @var \Spryker\Zed\Propel\Business\PropelFacadeInterface
     */
    protected $propelFacade;

    /**
     * @param \Spryker\Zed\Propel\Business\PropelFacadeInterface $propelFacade
     */
    public function __construct($propelFacade)
    {
        $this->propelFacade = $propelFacade;
    }

    /**
     * @return string
     */
    public function getSchemaDirectory(): string
    {
        return $this->propelFacade->getSchemaDirectory();
    }
}
