<?php

namespace SimpleMvc\Api;

use SimpleMvc\Framework\BaseApiController;
use SimpleMvc\Domain\UserRepository;

class UserApiController extends BaseApiController
{
    private UserRepository $userRepository;

    protected function initialize(): void
    {
        $this->userRepository = new UserRepository();
    }

    /**
     * @throws \JsonException
     */
    protected function baseRoute(): void
    {
        $GET = function (UserApiController $c) {
            return $c->userRepository->selectAll();
        };

        if (is_callable(${$this->reqMethod})) {
            $data = ${$this->reqMethod}($this);
            $this->sendResponse(200, $data);
        } else {
            $this->sendResponse(405);
        }
    }
}
