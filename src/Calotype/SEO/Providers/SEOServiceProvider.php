<?php namespace Calotype\SEO\Providers;

use Illuminate\Http\Response;
use Illuminate\Support\ServiceProvider;

use Calotype\SEO\Generators\MetaGenerator;
use Calotype\SEO\Generators\RobotsGenerator;
use Calotype\SEO\Generators\SitemapGenerator;

class SEOServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerBindings();
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $app = $this->app;

        // Generate sitemap.xml
        $this->app['router']->get('sitemap.xml', function() use ($app) {
            $response = new Response($app['doit.seo.generators.sitemap']->generate(), 200);
            $response->header('Content-Type', 'text/xml');

            return $response;
        });

        // Generate robots.txt
        $this->app['router']->get('robots.txt', function() use ($app) {
            $response = new Response($app['doit.seo.generators.robots']->generate(), 200);
            $response->header('Content-Type', 'text/plain');

            return $response;
        });
    }

    /**
     * Register the bindings.
     *
     * @return void
     */
    public function registerBindings()
    {
        // Register the Sitemap generator
        $this->app->singleton('doit.seo.generators.sitemap', function($app) {
            return new SitemapGenerator();
        });

        // Register the Meta generator
        $this->app->singleton('doit.seo.generators.meta', function($app) {
            return new MetaGenerator();
        });

        // Register the Robots generator
        $this->app->singleton('doit.seo.generators.robots', function($app) {
            return new RobotsGenerator();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('doit.seo.generators.meta', 'doit.seo.generators.sitemap', 'doit.seo.generators.robots');
    }

}
