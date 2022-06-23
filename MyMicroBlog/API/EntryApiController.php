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
    protected function defaultAction(): void
    {
        if (in_array($this->reqMethod,['POST', 'PUT', 'GET', 'DELETE'])) {
            try {
                $data = [];
                if($this->reqMethod == 'GET') {
                    $data = $this->entryRepository->selectAll();
                }
                else if($this->reqMethod === 'POST'){
                    $this->entryRepository->insert($this->jsonPostData);
                    $data = $this->entryRepository->selectByPk($this->jsonPostData['id']);
                }
                else if($this->reqMethod === 'PUT'){
                    $this->entryRepository->update($this->jsonPostData['id'],$this->jsonPostData);
                    $data = $this->entryRepository->selectByPk($this->jsonPostData['id']);
                }
                else if($this->reqMethod === 'DELETE'){
                    $data = $this->entryRepository->selectByPk($this->queryParams['id']);
                    $this->entryRepository->delete($this->queryParams['id']);
                }
                $json = json_encode($data, JSON_THROW_ON_ERROR);
                $this->sendOutput($json, 200, ['HTTP/1.1 200 OK', 'Content-Type: application/json']);
            } catch (Error $e) {
                $errorJson = json_encode(
                    ['error' => $e->getMessage() . 'Something went wrong! Please contact support.'],
                    JSON_THROW_ON_ERROR
                );
                $errorHeader = 'HTTP/1.1 500 Internal Server Error';
                $this->sendOutput($errorJson, 500, [$errorHeader]);
            }
        }
        else {
            $errorJson = json_encode(['error' => 'Method not supported'],JSON_THROW_ON_ERROR);
            $errorHeader = 'HTTP/1.1 422 Unprocessable Entity';
            $this->sendOutput($errorJson, 422, [$errorHeader]);
        }
    }

    /**
     * @throws \JsonException
     */
    protected function toggleAction(): void
    {
        if ($this->reqMethod == 'GET') {
            try {
                $this->entryRepository->toggleHighlight($this->queryParams['id']);
                $data = $this->entryRepository->selectByPk($this->queryParams['id']);
                $json = json_encode($data, JSON_THROW_ON_ERROR);
                $this->sendOutput($json, 200, ['HTTP/1.1 200 OK', 'Content-Type: application/json']);

            } catch (Error $e) {
                $errorJson = json_encode(
                    ['error' => $e->getMessage() . 'Something went wrong! Please contact support.'],
                    JSON_THROW_ON_ERROR
                );
                $errorHeader = 'HTTP/1.1 500 Internal Server Error';
                $this->sendOutput($errorJson, 500, [$errorHeader]);
            }
        }
        else {
            $errorJson = json_encode(['error' => 'Method not supported'],JSON_THROW_ON_ERROR);
            $errorHeader = 'HTTP/1.1 422 Unprocessable Entity';
            $this->sendOutput($errorJson, 422, [$errorHeader]);
        }
    }
}