<?php
namespace MyMicroBlog\View;

use MyMicroBlog\Model\Domain\MyMicroBlogEntry;

class MyMicroBlogView extends EntryView
{
    /**
     * Prints all entries
     * @param array $entries
     * @return void
     */
    public function printAllEntries(array $entries): void
    {
        foreach ($entries as $entry) {
            $this->printEntry($entry);
        }
    }

    public function showNewEntryForm(): void
    {
        echo '<h3>Write new post</h3>
            <form action="' . $this->formAction . '" method="POST" class="mb-3">
            <label for="title" class="form-label">Titel</label>
            <input type="text" class="form-control" name="title" id="title">
            <label for="content" class="form-label">Text</label>
            <input type="text" name="noticeText" class="form-control" id="textarea">
            <input type="hidden" name="action" value="newEntry">
            <input class="btn btn-primary" type="submit"  value="New Entry" >
            </form>';
    }

    public function showEditForm(MyMicroBlogEntry $entry): void
    {
        echo "<h3>Edit Entry</h3>" .
            "<form action='$this->formAction' method='POST' class='mb-3'>" .
            '<label for="title" class="form-label">Titel</label>' .
            "<input type='text' id='title' class='form-control' name='title' value='" . $entry->title . "' />" .
            '<label for="notice" class="form-label">Text</label>' .
            "<input type='text' id='notice' class='form-control' name='noticeText' value='" . $entry->text . "'/>" .
            "<input type='hidden' name='entryId' value='" . $_REQUEST['entryId'] . "'>" .
            "<input type='hidden' name='userId' value='" . getCurrentUser()->id . "'>" .
            "<input type='hidden' name='action' value='save'>" .
            '<input type="submit" class="btn btn-primary" value="Save Entry" class="card-link">' .
            "</form>";
    }
}
