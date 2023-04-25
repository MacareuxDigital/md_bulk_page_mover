<?php
/**
 * @author: Biplob Hossain <biplob.ice@gmail.com>
 */

namespace Concrete\Package\MdBulkPageMover;

use Concrete\Core\Application\Application;
use Concrete\Core\Backup\ContentImporter;
use Concrete\Core\Package\Package;
use Macareux\BulkPageMover\ConcreteCMS;
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
    protected $appVersionRequired = '8.5.0';

    /**
     * @var string package version
     */
    protected $pkgVersion = '0.0.1';

    /**
     * @var array Autoload custom classes
     */
    protected $pkgAutoloaderRegistries = [
        'src/Concrete' => '\Macareux\BulkPageMover',
    ];
    /**
     * @var bool|int
     */
    protected $isV9;

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
        $this->registerTasks();
    }

    public function install(): void
    {
        $pkg = parent::install();

        if ($this->isV9()) {
            $ci = new ContentImporter();
            $ci->importContentFile($this->getPackagePath() . '/config/tasks.xml');
        } else {
            \Concrete\Core\Job\Job::installByPackage('bulk_move_pages', $pkg);
        }
    }

    protected function installXml()
    {
        $ci = new ContentImporter();
        $ci->importContentFile($this->getPackagePath() . '/config/tasks.xml');
    }

    protected function registerCommands(): void
    {
        if (Application::isRunThroughCommandLineInterface()) {
            $console = $this->app->make('console');
            $console->add(new PageMoveCommand());
        }
    }

    protected function registerTasks()
    {
        if ($this->isV9()) {
            $manager = $this->app->make(\Concrete\Core\Command\Task\Manager::class);
            $manager->extend('bulk_move_pages', function () {
                return new \Macareux\BulkPageMover\Command\Task\Controller\BulkMovePagesController();
            });
        }
    }

    protected function isV9()
    {
        if (!$this->isV9) {
            $this->isV9 = ConcreteCMS::isV9();
        }

        return $this->isV9;
    }
}
