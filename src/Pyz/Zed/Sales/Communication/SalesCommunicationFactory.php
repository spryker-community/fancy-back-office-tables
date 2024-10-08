<?php

namespace Pyz\Zed\Sales\Communication;

use Pyz\Zed\Sales\Communication\ConfigurationProvider\SalesOrderTableConfigurationProvider;
use Pyz\Zed\Sales\Communication\DataProvider\SalesOrderGuiTableDataProvider;
use Pyz\Zed\Sales\SalesDependencyProvider;
use Spryker\Shared\GuiTable\GuiTableFactory;
use Spryker\Shared\GuiTable\Http\GuiTableDataRequestExecutorInterface;
use Spryker\Zed\Sales\Communication\SalesCommunicationFactory as SprykerSalesCommunicationFactory;

class SalesCommunicationFactory extends SprykerSalesCommunicationFactory
{
    /**
     * @return \Spryker\Shared\GuiTable\Http\GuiTableDataRequestExecutorInterface
     */
    public function getGuiTableHttpDataRequestExecutor(): GuiTableDataRequestExecutorInterface
    {
        return $this->getProvidedDependency(SalesDependencyProvider::SERVICE_GUI_TABLE_HTTP_DATA_REQUEST_EXECUTOR);
    }

    public function createOrderTableDataProvider()
    {
        return new SalesOrderGuiTableDataProvider(
            $this->getRepository(),
        );

    }

    public function createSalesOrderTableConfigurationProvider(): SalesOrderTableConfigurationProvider
    {
        return new SalesOrderTableConfigurationProvider(
            new GuiTableFactory(),

        );
    }
}
