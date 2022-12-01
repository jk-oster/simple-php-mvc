<?php
namespace SimpleMvc\Controller;

use SimpleMvc\Framework\BaseController;
use SimpleMvc\Domain\Model\MyMicroBlogEntry;
use SimpleMvc\Domain\EntryRepository;

class MyMicoBlogController extends BaseController
{
    private EntryRepository $entryRepository;

    protected function initialize(): void
    {
        $this->entryRepository = new EntryRepository();
    }

    protected function beforeDispatch(): void
    {
        if ($this->reqAction !== 'edit' && isLoggedIn()) {
            // $this->blogView->showNewEntryForm();
            echo _template('NewEntryForm');
        }
    }

    // Check if user has permission to dispatch action
    protected function canAccessDispatch(): bool
    {
        if($this->reqAction === 'newEntry'){
            return isLoggedIn();
        }
        else if(in_array($this->reqAction, ['save','highlight','delete','edit'])) {
            $user = getCurrentUser();
            $createdUserId = $this->entryRepository->selectByPk($_REQUEST['entryId'])->createdUser;
            return isLoggedIn() &&  ($user->role === 0 || $user->id === $createdUserId);
        }
        return true;
    }

    protected function afterDispatch(): void
    {
        $entries = $this->entryRepository->getEntriesSorted();
        echo _template('EntriesList', ['entries' => $entries]);
    }

    protected function editAction(): void
    {
        $entry = $this->entryRepository->selectByPk($_REQUEST['entryId']);
        echo _template('EditEntryForm', ['entry' => $entry]);
    }

    protected function deleteAction(): void
    {
        $this->entryRepository->delete($_REQUEST['entryId']);
    }

    protected function highlightAction(): void
    {
        $this->entryRepository->toggleHighlight($_REQUEST['entryId']);
    }

    protected function newEntryAction(): void
    {
        if ($_REQUEST['title'] !== '') {
            $entry = MyMicroBlogEntry::objectFrom([
                'createdUser' => getCurrentUser()->id,
                'title' => $_REQUEST['title'],
                'text' => $_REQUEST['noticeText'],
            ]);
            _debug($entry);
            $this->entryRepository->insert($entry);
        } else {
            echo "Please enter entry title";
        }
    }

    protected function saveAction(): void
    {
        if ($_REQUEST['title'] !== '') {
            $entry = MyMicroBlogEntry::objectFrom([
                'title' => $_REQUEST['title'],
                'text' =>$_REQUEST['noticeText'],
                'editedUser' => getCurrentUser()->id
            ]);
            $this->entryRepository->update($_REQUEST['entryId'], $entry);
        } else {
            echo "Please enter entry title";
        }
    }
}
