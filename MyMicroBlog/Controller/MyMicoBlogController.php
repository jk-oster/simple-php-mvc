<?php
namespace MyMicroBlog\Controller;

use MyMicroBlog\Framework\BaseController;
use MyMicroBlog\Model\Domain\MyMicroBlogEntry;
use MyMicroBlog\Model\EntryRepository;
use MyMicroBlog\View\MyMicroBlogView;

class MyMicoBlogController extends BaseController
{
    private EntryRepository $entryRepository;
    private MyMicroBlogView $blogView;

    protected function initialize(): void
    {
        $this->entryRepository = new EntryRepository();
        $this->blogView = new MyMicroBlogView();
    }

    protected function beforeDispatch(): void
    {
        if ($this->reqAction !== 'edit' && isLoggedIn()) {
            $this->blogView->showNewEntryForm();
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
        $this->blogView->printAllEntries($entries);
    }

    protected function editAction(): void
    {
        $entry = $this->entryRepository->selectByPk($_REQUEST['entryId']);
        $this->blogView->showEditForm($entry);
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
                'editedUser' => 0,
                'title' => $_REQUEST['title'],
                'highlight' => 0,
                'text' => $_REQUEST['noticeText'],
            ]);
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
