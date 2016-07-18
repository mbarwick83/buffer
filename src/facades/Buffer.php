<?php 

namespace Mbarwick83\Buffer\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Mbarwick83\Buffer\Buffer
 */
class Buffer extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Mbarwick83\Buffer\Buffer';
    }
}