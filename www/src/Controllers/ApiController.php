<?php

namespace App\Controllers;

use App\Models\EntryRepository;
use App\Models\UserRepository;
use SimpleMvc\BaseController;
use SimpleMvc\Router;

class ApiController extends BaseController
{
    private UserRepository $userRepository;
    private EntryRepository $entryRepository;

    public function initialize(): void
    {
//        $this->userRepository = new UserRepository();
//        $this->entryRepository = new EntryRepository();
    }

    public function index(): void
    {
        $router = Router::getInstance();
        $sortedRoutes = _collect($router->getRoutes())
            ->sort(fn($a, $b) => $a->getRoute() <=> $b->getRoute())
            ->toArray();
//        $this->response->sendJson($sortedRoutes);
        $this->response->sendJson($this->request);
    }
    public function entries(): void
    {
        $entries = $this->entryRepository->findAll();
        $this->response->sendJson($entries);
    }

    public function user(): void
    {
        $user = null;
        $id = $this->request->param('id');
        if($id){
            $user = $this->userRepository->find($id);
        }

        $name = $this->request->param('name');
        if($name){
            $user = $this->userRepository->findBy('name', $name);
        }

        $this->response->sendJson($user);
    }
}