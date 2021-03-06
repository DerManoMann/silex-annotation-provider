<?php
/**
 * This file is part of the silex-annotation-provider package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license       MIT License
 * @copyright (c) 2014, Dana Desrosiers <dana.desrosiers@gmail.com>
 */

namespace DDesrosiers\Test\SilexAnnotations\Annotations;

use DDesrosiers\SilexAnnotations\Annotations as SLX;
use DDesrosiers\SilexAnnotations\AnnotationServiceProvider;
use Silex\Application;
use Silex\Provider\UrlGeneratorServiceProvider;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Client;
use Symfony\Component\Routing\Generator\UrlGenerator;

class BindTest extends \PHPUnit_Framework_TestCase
{
    /** @var Application */
    protected $app;

    /** @var Client */
    protected $client;

    public function setUp()
    {
        $this->app = new Application();
        $this->app['debug'] = true;

        $this->app->register(
                  new AnnotationServiceProvider(),
                  array(
                      "annot.controllers" => array("DDesrosiers\\Test\\SilexAnnotations\\Annotations\\BindTestController")
                  )
        );

        $this->app->register(new UrlGeneratorServiceProvider());

        $this->client = new Client($this->app);
    }

    public function testBind()
    {
        $this->client->request("GET", "/test");
        $response = $this->client->getResponse();
        $this->assertEquals('/test', $response->getContent());
    }
}

class BindTestController
{
    /**
     * @SLX\Request(method="GET", uri="/test")
     * @SLX\Bind(routeName="testRouteName")
     */
    public function testMethod(Application $app)
    {
        /** @var UrlGenerator $urlGenerator */
        $urlGenerator = $app['url_generator'];
        return new Response($urlGenerator->generate('testRouteName'));
    }
}
