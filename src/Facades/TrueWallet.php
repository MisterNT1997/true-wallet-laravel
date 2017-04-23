<?php

namespace Thatphon05\TrueWallet\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class TrueWalletFacade.
 */
class TrueWallet extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'truewallet';
    }
}
