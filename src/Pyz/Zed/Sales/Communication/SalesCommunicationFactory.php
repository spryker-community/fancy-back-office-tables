<?php

namespace Pyz\Zed\Sales\Communication;

use Pyz\Zed\Sales\SalesDependencyProvider;
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
}
