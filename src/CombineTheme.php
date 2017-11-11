<?php

namespace Mods\Scss;

use Symfony\Component\Finder\Finder;
use Illuminate\Filesystem\Filesystem;

class CombineTheme
{
	/**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Event Response
     *
     * @var string
     */
    protected $response;

	/**
     * Create a new config cache command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @return void
     */
    public function __construct(
        Filesystem $files
    ) {
        $this->files = $files;
    }

	 /**
	 * Handle asset is deployed events.
	 *
	 * @param  string  $area
	 * @return void
	 */
    public function handle($area, $paths, $basePath, $type)
    {
    	if(empty($type) || in_array('css', $type)) {
        	$this->combineScssAssests($area, $paths, $basePath);
        }

        return [$this->response];
    }

    protected function combineScssAssests($area, $paths, $basePath)
    {
    	$lang = 'scss';
        foreach ($paths as $themekey => $locations) {
            $themePath = formPath(
                [$basePath, 'assets', $area, $themekey, $lang]
            );
            if ($this->files->exists($themePath)) {
                $import = ['$base-url: '.'"/assets/'.$area.'/'.$themekey.'/";'];
                $files = Finder::create()->files()
                    ->name('_theme.'.$lang)
                    ->name('_module.'.$lang)
                    ->in([$themePath]);   
                foreach ($files as $file) {
                    $import[] = "@import \"{$file->getRelativePathName()}\";";
                }
                $this->response .="  => Combining `{$lang}` in `{$area}` area for `{$themekey}` theme.\n";
                $this->writeThemeFiles($import, 'theme.'.$lang, $themePath);
            }
        }
    }

    protected function writeThemeFiles($content, $name, $path)
    {
        $this->files->put(
            $path.'/'.$name, implode(PHP_EOL, $content)
        );
    }
}