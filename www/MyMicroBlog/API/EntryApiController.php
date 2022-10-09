<?php

namespace MyMicroBlog\Api;

use MyMicroBlog\Framework\BaseApiController;
use MyMicroBlog\Model\EntryRepository;

class EntryApiController extends BaseApiController
{
    private EntryRepository $entryRepository;

    protected function initialize(): void
    {
        $this->entryRepository = new EntryRepository();
    }

    /**
     * @throws \JsonException
     */
    protected function baseRoute(): void
    {
        $GET = function (EntryApiController $controller) {
            return $controller->entryRepository->selectAll();
        };

        $POST = function (EntryApiController $controller) {
            $controller->entryRepository->insert($controller->jsonPostData);
            return  $controller->entryRepository->selectByPk($controller->jsonPostData['id']);
        };

        $PUT = function (EntryApiController $controller) {
            $controller->entryRepository->update($controller->jsonPostData['id'], $controller->jsonPostData);
            return $controller->entryRepository->selectByPk($controller->jsonPostData['id']);
        };

        $DELETE = function (EntryApiController $controller) {
            $controller->entryRepository->selectByPk($controller->queryParams['id']);
            return $controller->entryRepository->delete($controller->queryParams['id']);
        };

        if (is_callable(${$this->reqMethod})) {
            $data = ${$this->reqMethod}($this);
            $this->sendResponse(200, $data);
        } else {
            $this->sendResponse(405);
        }
    }

    /**
     * @throws \JsonException
     */
    protected function toggleRoute(): void
    {
        $GET = function (EntryApiController $controller) {
            $controller->entryRepository->toggleHighlight($controller->queryParams['id']);
            return $controller->entryRepository->selectByPk($controller->queryParams['id']);
        };

        if (is_callable(${$this->reqMethod})) {
            if (isset($this->queryParams['id'])) {
                $data = ${$this->reqMethod}($this);
                $this->sendResponse(200, $data);
            } else {
                $this->sendResponse(400, ['error' => "No query paramteter 'id' provided"]);
            }
        } else {
            $this->sendResponse(405);
        }
    }
}
