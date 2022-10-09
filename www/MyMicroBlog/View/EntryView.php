<?php

namespace MyMicroBlog\View;

use MyMicroBlog\Model\Domain\MyMicroBlogEntry;

class EntryView
{
    protected string $formAction;

    public function __construct()
    {
        $this->formAction = $_SERVER['PHP_SELF'];
    }

    /**
     * Prints entry argument as a nice card
     * @param array $entry
     * @return void
     */
    public function printEntry(MyMicroBlogEntry $entry): void
    {
        echo '<div class="entry card mb-3  ' . ($entry->highlight == 1 ? 'highlight border-primary' : '') . '">' .
            '<div class="card-header text-muted small">' . $this->createdEditedDate($entry) .
            '</div>' .
            '<div class="card-body">' .
            '<h3 class="card-title h5">' . $entry->title . '</h3>' .
            '<p class="card-text">' . $entry->text .
            $this->renderEditable($entry) .
            '</div>
            </div>';
    }

    private function createdEditedDate(MyMicroBlogEntry $entry): string
    {
        $res = 'Erstellt am: ' . $entry->created . ' von ' . getUserById($entry->createdUser)->name;
        if ($entry->created != $entry->edited) {
            $res .= '<br/>Zuletzt geändert am: ' . $entry->edited;
            $res .= $entry->editedUser !== $entry->createdUser ? ' von ' . getUserById($entry->editedUser)->name : '';
        }
        return $res;
    }

    private function renderEditable(MyMicroBlogEntry $entry): string
    {
        $role = $_SESSION['role'] ?? '';

        if ((isLoggedIn() && $role == 0) || (isLoggedIn() && $entry->createdUser == getCurrentUser()->id)) {
            return '<br>' .
                $this->showHighlightCheckbox($entry) .
                $this->showDeleteButton($entry) .
                $this->showEditButton($entry);
        }
        return '';
    }

    private function showEditButton(MyMicroBlogEntry $entry): string
    {
        return "<form action='$this->formAction' method='POST' class='d-inline'>" .
            "<input type='hidden' name='entryId' value='$entry->id'>" .
            "<input type='hidden' name='action' value='edit'>" .
            '<input type="submit" value="🖊️" class="btn btn-outline-primary btn-sm">' .
            "</form>";
    }

    private function showHighlightCheckbox(MyMicroBlogEntry $entry): string
    {
        return "<form action='$this->formAction' method='POST' class='d-inline'>" .
            "<input type='hidden' name='action' value='highlight'>" .
            "<input type='hidden' name='entryId' value='$entry->id'>" .
            '<input type="submit" value="💡" class="btn btn-outline-warning btn-sm">' .
            "</form>";
    }

    private function showDeleteButton(MyMicroBlogEntry $entry): string
    {
        return "<form action='$this->formAction' method='POST' class='d-inline'>" .
            "<input type='hidden' name='entryId' value='$entry->id'>" .
            "<input type='hidden' name='action' value='delete'>" .
            '<input type="submit" value="🗑️" class="btn btn-outline-danger btn-sm">' .
            "</form>";
    }
}