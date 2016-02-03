<?php
namespace hotrush\Webshotter;

use Illuminate\Support\ServiceProvider;

class WebshotServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            'webshot',
            'hotrush\Webshot'
        );
    }
}
