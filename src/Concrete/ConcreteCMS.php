<?php
/**
 * A file to maintain Concrete CMS v8 & v9 cross compatibility
 * @author: Biplob Hossain <biplob.ice@gmail.com>
 */

namespace Macareux\BulkPageMover;

class ConcreteCMS
{
    public static function isV9()
    {
        return version_compare(app('config')->get('concrete.version'), '9.0.0', '>=');
    }
}

abstract class AbstractController
{
}

class Command
{
}
