<?php namespace Calotype\SEO\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Application;
use Symfony\Component\Routing\Route;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class RoutesCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'seo:routes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check all the routes for meta tags and open graph tags';

    /**
     * The table helper set.
     *
     * @var Symfony\Component\Console\Helper\TableHelper
     */
    protected $table;

    /**
     * Create a new seo check command instance.
     *
     * @param  Illuminate\Foundation\Application $app
     */
    public function __construct(Application $app)
    {
        parent::__construct();

        $this->app = $app;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $this->table = $this->getHelperSet()->get('table');
        $routes = $this->app['router']->getRoutes();

        if (count($routes) == 0) {
            return $this->error("Your application doesn't have any routes.");
        }

        $this->displayRoutes($this->getRoutes());
    }

    /**
     * Compile the routes into a displayable format.
     *
     * @return array
     */
    protected function getRoutes()
    {
        $results = array();
        $routes = $this->app['router']->getRoutes();

        foreach ($routes as $name => $route) {
            if (! in_array($name, array('get sitemap.xml', 'get robots.txt'))) {
                foreach ($this->getRouteInformation($name, $route) as $single_route) {
                    $results[] = $single_route;
                }
            }
        }

        return $results;
    }

    /**
     * Get the route information for a given route.
     *
     * @param  string $name
     * @param  Symfony\Component\Routing\Route $route
     *
     * @return array
     */
    protected function getRouteInformation($name, Route $route)
    {
        $uri = head($route->getMethods()) . ' ' . $route->getPath();

        $route->run($this->app['request']);

        $opengraph = array();
        $properties = $this->app['calotype.seo.generators.opengraph']->getProperties();

        $url = $this->app['request']->root() . '/' . $route->getPath();
        $in_sitemap = $this->app['calotype.seo.generators.sitemap']->contains($url);

        $results = array(
            array(
                'uri'         => $uri,
                'title'       => $this->app['calotype.seo.generators.meta']->getTitle(),
                'description' => $this->app['calotype.seo.generators.meta']->getDescription(),
                'opengraph'   => 'v----------------',
                'sitemap'     => $in_sitemap ? 'Yes' : 'No'
            )
        );

        foreach ($properties as $property => $value) {
            $results[] = array('', '', '', "$property: $value");
        }

        return $results;
    }

    /**
     * Display the route information on the console.
     *
     * @param array $routes
     */
    protected function displayRoutes(array $routes)
    {
        $headers = array('URI', 'Title', 'Description', 'Open Graph', 'Is in Sitemap?');

        $this->table->setHeaders($headers)->setRows($routes);

        $this->table->render($this->getOutput());
    }

    /**
     * Get the route name for the given name.
     *
     * @param  string  $name
     *
     * @return string
     */
    protected function getRouteName($name)
    {
        return str_contains($name, ' ') ? '' : $name;
    }

}
