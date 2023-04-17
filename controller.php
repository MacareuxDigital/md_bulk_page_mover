<?php
/**
 * @author: Biplob Hossain <biplob.ice@gmail.com>
 */

namespace Concrete\Package\MdBulkPageMover;

use Concrete\Core\Application\Application;
use Concrete\Core\Package\Package;
use Macareux\BulkPageMover\Console\Command\PageMoveCommand;

class Controller extends Package
{
    /**
     * @var string package handle
     */
    protected $pkgHandle = 'md_bulk_page_mover';

    /**
     * @var string required concrete5 version
     */
    protected $appVersionRequired = '8.5.6';

    /**
     * @var string package version
     */
    protected $pkgVersion = 'MdBulkPageMover';

    /**
     * @var array Autoload custom classes
     */
    protected $pkgAutoloaderRegistries = [
        'src/Concrete' => '\Macareux\BulkPageMover',
    ];

    /**
     * @return string Package name
     */
    public function getPackageName(): string
    {
        return t('Macareux Bulk Page Mover');
    }

    /**
     * @return string Package description
     */
    public function getPackageDescription(): string
    {
        return t('Moves bulk pages from one location to another');
    }

    public function on_start(): void
    {
        $this->registerCommands();
    }

    protected function registerCommands(): void
    {
        if (Application::isRunThroughCommandLineInterface()) {
            $console = $this->app->make('console');
            $console->add(new PageMoveCommand());
        }
    }
}
