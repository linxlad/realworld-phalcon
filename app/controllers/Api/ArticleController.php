<?php

namespace RealWorld\Controllers\Api;

/**
 * Class ArticleController
 * @package RealWorld\Controllers\Api
 */
class ArticleController extends ApiController
{
    public function initialize()
    {

    }

    /**
     * The start action, it returns the "search"
     */
    public function indexAction()
    {
        // ...
    }

    /**
     * Execute the "search" based on the criteria sent from the "index"
     * Returning a paginator for the results
     */
    public function searchAction()
    {
        // ...
    }

    /**
     * Shows the view to return a "new" article
     */
    public function newAction()
    {
        // ...
    }

    /**
     * "edit" an existing article
     */
    public function editAction()
    {
        // ...
    }

    /**
     * Creates a article based on the data entered in the "new" action
     */
    public function createAction()
    {
        // ...
    }

    /**
     * Updates a article based on the data entered in the "edit" action
     */
    public function saveAction()
    {
        // ...
    }

    /**
     * Deletes an existing article
     *
     * @param $id
     */
    public function deleteAction($id)
    {
        // ...
    }
}