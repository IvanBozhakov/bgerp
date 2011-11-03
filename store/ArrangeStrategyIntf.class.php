<?php
/**
 * Интерфейс за автоматично подреждане на палети в склада
 */
class store_ArrangeStrategyIntf
{
    /**
     * Връща автоматично изчислено палет място
     * 
     *  @param int $palletId
     */
    function getAutoPalletPlace($palletId)
    {
        return $this->class->getAutoPalletPlace($palletId);
    }
    
}