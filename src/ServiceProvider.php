<?php

namespace Phpsa\ContentToc;

use Phpsa\ContentToc\Modifiers\Toc;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    // Do amazing things!

    protected $modifiers = [
        Toc::class
    ];
}
