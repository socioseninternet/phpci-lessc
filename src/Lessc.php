<?php

namespace SociosEnInternet\PhpciPlugins;

use PHPCI;
use PHPCI\Builder;
use PHPCI\Model\Build;
use PHPCI\Helper\Lang;

/**
* Lessc Plugin - Provides Less compilation to PHPCI.
* @author       Ivan Bustos <contacto@ivanbustos.com>
* @package      PHPCI
* @subpackage   Plugins
*/
class Lessc implements PHPCI\Plugin, PHPCI\ZeroConfigPlugin
{
    protected $directory;
    protected $command;
    protected $phpci;

    /**
     * Check if this plugin can be executed.
     * @param $stage
     * @param Builder $builder
     * @param Build $build
     * @return bool
     */
    public static function canExecute($stage, Builder $builder, Build $build)
    {
        if ($this->phpci->findBinary(array('lessc'))) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Set up the plugin, configure options, etc.
     * @param Builder $phpci
     * @param Build $build
     * @param array $options
     */
    public function __construct(Builder $phpci, Build $build, array $options = array())
    {
        $path             = $phpci->buildPath;
        $this->phpci      = $phpci;
        $this->build      = $build;
        $this->lessDirectory  = $path;
        $this->cssDirectory = $path;

        if (array_key_exists('lessDirectory', $options)) {
            $this->lessDirectory = $path . $options['lessDirectory'];
        }

        if (array_key_exists('cssDirectory', $options)) {
            $this->cssDirectory = $path . $options['cssDirectory'];
        }
    }

    /**
    * Executes lessc compiler
    */
    public function execute()
    {
        $cmd = $this->phpci->findBinary(array('lessc')) . ' "%s %s"';
        foreach (glob($this->lessDirectory . '/*.less') as $filename) {
            $basename = basename($filename, '.less');
            $this->phpci->executeCommand($cmd, $filename, $this->cssDirectory . '/' . $basename . '.css'); 
        }
        return TRUE;
    }
}
