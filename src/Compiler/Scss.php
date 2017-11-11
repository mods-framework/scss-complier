<?php

namespace Mods\Scss\Compiler;

use Leafo\ScssPhp\Compiler as ScssCompiler;
use Mods\Theme\Contracts\Compiler;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\Container\Container;
use Symfony\Component\Console\Output\OutputInterface;

class Scss implements Compiler
{
    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * The container implementation.
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $container;

    /**
     * Hold the type of asset need to be moved.
     *
     * @var string
     */
    protected $type = null;

    /**
     * Create a new compiler command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @param  \Illuminate\Contracts\Container\Container  $container
     * @return void
     */
    public function __construct(Filesystem $files, Container $container)
    {
        $this->files = $files;
        $this->container = $container;
    }

    public function handle($traveler, $pass)
    {
        extract($traveler);
        $traveler['manifest']['has_theme'] = false;
        if (!class_exists('Leafo\ScssPhp\Compiler')) {
            $console->warn("`Leafo\ScssPhp\Compiler` is not found \n composer require leafo/scssphp");
            return $pass($traveler);
        }

        $scss = new ScssCompiler();

        $originPath = formPath([
            $this->container['path.resources'], 'assets', $area,
            $theme, 'scss'
        ]);

        $destination = formPath([
            $this->container['path.resources'], 'assets', $area,
            $theme, 'css', 'theme.css'
        ]);

        $scssFile = formPath([$originPath, 'theme.scss']);

        $scss->setImportPaths($originPath);

        $compiledContent = $scss->compile($this->files->get($scssFile));
        
        if ($this->files->put(
            $destination,
            $compiledContent,
            true
        )) {
            $console->info("\t* Compiling `scss` in {$area} ==> {$theme}.");
        } else {
            $console->warn("`scss` file not found in {$area} ==> {$theme}.");
        }
        $traveler['manifest']['has_theme'] = true;
        return $pass($traveler);
    }
}
