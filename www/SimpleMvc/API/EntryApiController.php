<?php

namespace SimpleMvc\Api;

use SimpleMvc\Framework\BaseApiController;
use SimpleMvc\Domain\EntryRepository;

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
        $GET = function (EntryApiController $c) {
            return $c->entryRepository->selectAll();
        };

        $POST = function (EntryApiController $c) {
            $c->entryRepository->insert($c->jsonPostData);
            return  $c->entryRepository->selectByPk($c->jsonPostData['id']);
        };

        $PUT = function (EntryApiController $c) {
            $c->entryRepository->update($c->jsonPostData['id'], $c->jsonPostData);
            return $c->entryRepository->selectByPk($c->jsonPostData['id']);
        };

        $DELETE = function (EntryApiController $c) {
            $c->entryRepository->selectByPk($c->queryParams['id']);
            return $c->entryRepository->delete($c->queryParams['id']);
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
        $GET = function (EntryApiController $c) {
            $c->entryRepository->toggleHighlight($c->queryParams['id']);
            return $c->entryRepository->selectByPk($c->queryParams['id']);
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
