<?php
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

$collection = new RouteCollection();
$collection->add('index', new Route('/', array(
    '_controller' => 'SupportFrontBundle:Title:index',
)));

$collection->add('index', new Route('/', array(
    '_controller' => 'SupportFrontBundle:Login:index',
)));

$collection->add('login_email', new Route('/login', array(
    '_controller' => 'SupportFrontBundle:Login:loginEmail',
)));

$collection->add('index', new Route('/', array(
    '_controller' => 'SupportFrontBundle:MainPage:index',
)));

$collection->add('search_keyword', new Route('/searchKeyword', array(
    '_controller' => 'SupportFrontBundle:Search:searchKeyword',
)));

$collection->add('detail', new Route('/detail', array(
    '_controller' => 'SupportFrontBundle:Product:detail',
)));

$collection->add('change_my_info', new Route('/change', array(
    '_controller' => 'SupportFrontBundle:MyPage:changeMyInfo',
)));

return $collection;