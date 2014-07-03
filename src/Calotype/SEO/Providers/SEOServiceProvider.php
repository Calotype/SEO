<?php namespace Calotype\SEO\Providers;

use Illuminate\Http\Response;
use Illuminate\Support\ServiceProvider;

use Calotype\SEO\Generators\MetaGenerator;
use Calotype\SEO\Generators\RobotsGenerator;
use Calotype\SEO\Generators\SitemapGenerator;
use Calotype\SEO\Generators\OpenGraphGenerator;

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
        $this->app['config']->package('calotype/seo', __DIR__ . '/../../../config', 'calotype-seo');

        $this->registerBindings();
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // If the user does not want us to create default routes, we won't
        $should_generate_routes = $this->app['config']->get('calotype-seo::generate_routes');

        if ($should_generate_routes) {
            $this->generateDefaultRoutes();
        }
    }

    /**
     * Generate default routes for /sitemap.xml and /robots.txt
     *
     * @return void
     */
    public function generateDefaultRoutes()
    {
        $app = $this->app;

        // Create the default robots.txt content
        $app['calotype.seo.generators.robots']->addUserAgent('*');
        $app['calotype.seo.generators.robots']->addDisallow('');
        $app['calotype.seo.generators.robots']->addSpacer();
        $app['calotype.seo.generators.robots']->addSitemap($app['request']->root() . '/sitemap.xml');

        // Generate sitemap.xml route
        $app['router']->get('sitemap.xml', function () use ($app) {
            $response = new Response($app['calotype.seo.generators.sitemap']->generate(), 200);
            $response->header('Content-Type', 'application/xml');

            return $response;
        });

        // Generate robots.txt route
        $app['router']->get('robots.txt', function () use ($app) {
            $response = new Response($app['calotype.seo.generators.robots']->generate(), 200);
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
        // Register the sitemap.xml generator
        $this->app->singleton('calotype.seo.generators.sitemap', function ($app) {
            return new SitemapGenerator();
        });

        // Register the meta tags generator
        $this->app->singleton('calotype.seo.generators.meta', function ($app) {
            return new MetaGenerator($app['config']->get('calotype-seo::defaults'));
        });

        // Register the robots.txt generator
        $this->app->singleton('calotype.seo.generators.robots', function ($app) {
            return new RobotsGenerator();
        });

        // Register the open graph properties generator
        $this->app->singleton('calotype.seo.generators.opengraph', function ($app) {
            return new OpenGraphGenerator();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array(
            'calotype.seo.generators.meta',
            'calotype.seo.generators.opengraph',
            'calotype.seo.generators.sitemap',
            'calotype.seo.generators.robots',
        );
    }
}
