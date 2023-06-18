<?php

namespace App\Controllers;

use App\Models\EntryRepository;
use App\Models\UserRepository;
use SimpleMvc\BaseController;

class WebController extends BaseController
{
    private UserRepository $userRepository;
    private EntryRepository $entryRepository;

    public function initialize(): void
    {
        $this->userRepository = new UserRepository();
        $this->entryRepository = new EntryRepository();
    }
    public function index(): void
    {
        $entries = $this->entryRepository->findAll();
        $user = $this->userRepository->find(1);
        $user->name = 'testupdate';
        $this->userRepository->save($user);
        $this->response->sendView('user', ['user' => $user, 'entries' => $entries], 'layouts/page');
    }

    public function test(): void
    {
        $this->response->sendHtml('test');
    }
}