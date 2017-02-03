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
        $this->phpci      = $phpci;
        $this->build      = $build;
        $this->lessDirectory  = '';
        $this->cssDirectory = '';

        if (array_key_exists('lessDirectory', $options)) {
            $this->lessDirectory = $options['lessDirectory'];
        }

        if (array_key_exists('cssDirectory', $options)) {
            $this->cssDirectory = $options['cssDirectory'];
        }
    }

    /**
    * Executes lessc compiler
    */
    public function execute()
    {
        $pwdLocation = $this->phpci->findBinary(array('pwd'));
        $this->phpci->executeCommand($pwdLocation);
        $build_location = $this->phpci->getLastOutput();
        $cmd = 'cd ' . $build_location . ' && ' . $this->phpci->findBinary(array('lessc')) . ' "%s %s"';
        foreach (glob($this->lessDirectory . '/*.less') as $filename) {
            $basename = basename($filename, '.less');
            $this->phpci->executeCommand($cmd, $filename, $this->cssDirectory . '/' . $basename . '.css'); 
        }
        return TRUE;
    }
}
