<?php

namespace App;

use App\Classes\ORMHelper;
use App\Classes\Single;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use App\Exception\ApiBadRequestException;
use Symfony\Component\Dotenv\Dotenv;

/**
 * Class App
 * @package App
 */
class App extends Single
{
    private const ROUTES_YAML = BASEPATH . '/config/api_routes.yaml';

    /** @var Request $request */
    private $request;
    /**@var Router  $router  */
    private $router;
    /** @var RouteCollection $routes  */
    private $routes;
    /** @var RequestContext $requestContext */
    private $requestContext;
    /**@var array $controller */
    private $controller;
    /** @var array $arguments  */
    private $arguments;
    /** @var EntityManager $entityManager  */
    private $entityManager;

    /**
     *
     */
    public function run(): void
    {
        try {

            $matcher = new UrlMatcher($this->routes, $this->requestContext);
            $this->request->attributes->add($matcher->match($this->request->getPathInfo()));
            $this->controller = $this->getController();
            $this->arguments = $this->getArguments();

            $responce = call_user_func_array($this->controller, $this->arguments);
        } catch (ApiBadRequestException $e) {
            $responce = new JsonResponse(['code' => 400, 'error' => $e->getMessage()]);
        } catch (RouteNotFoundException|ResourceNotFoundException $e) {
            $responce = new JsonResponse(['code' => 404, 'error' => $e->getMessage()]);
        } catch (MethodNotAllowedException $e) {
            $responce = new JsonResponse(['code' => 405, 'error' => 'Method not allowed']);
        } catch (\Throwable $t) {
            $responce = new JsonResponse(['code' => 500, 'error' => $t->getMessage()]);
        }

        $responce->send();
    }

    /**
     * @return array
     */
    public function getController(): array
    {
        return (new ControllerResolver())->getController($this->request);
    }

    /**
     * @return array
     */
    public function getArguments(): array
    {
        return (new ArgumentResolver())->getArguments($this->request, $this->controller);
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager(): EntityManager
    {
        return $this->entityManager;
    }

    /**
     * App constructor.
     * @throws \Doctrine\ORM\ORMException
     */
    protected function __construct()
    {
        $this->initEnvironment();
        $this->setRequest();
        $this->setRequestContext();
        $this->setRoutes();
        $this->setEntityManager();
    }

    /**
     *
     */
    private function initEnvironment(): void
    {
        $dotenv = new Dotenv();
        $dotenv->load(BASEPATH . '/.env');
    }

    /**
     *
     */
    private function setRequest(): void
    {
        $this->request = Request::createFromGlobals();
    }

    /**
     *
     */
    private function setRequestContext(): void
    {
        $this->requestContext = (new RequestContext())->fromRequest($this->request);
    }

    /**
     *
     */
    private function setRoutes(): void
    {
        $fileLocator = new FileLocator([__DIR__]);
        $this->router = new Router(new YamlFileLoader($fileLocator), self::ROUTES_YAML);
        $this->routes = $this->router->getRouteCollection();
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    private function setEntityManager(): void
    {
        $this->entityManager = ORMHelper::getEntityManager();
    }
}