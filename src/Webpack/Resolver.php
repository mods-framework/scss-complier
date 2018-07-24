<?php

namespace Mods\Scss\Webpack;

use Symfony\Component\Finder\Finder;
use Illuminate\Filesystem\Filesystem;

class Resolver
{
	/**
	 * Parse the content
	 *
	 * @param  string  $contents
	 * @return array
	 */
    public function handle($contents)
    {
    	return ['theme.scss?nomodule'];
    }
}