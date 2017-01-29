<?php

namespace Controllers;

use Core\BaseController;
use Core\Exceptions\ModelException;
use Core\JSONResponse;
use Services\NewsService;

/**
 * Class StudentsController
 * @package Controller
 */
class NewsController extends BaseController
{
    /**
     * @Route("GET", "index")
     */
    public function all()
    {
        $response = new JSONResponse();
        $news = new NewsService();
        $data = $news->all();
        $response->setData($data);

        return $response;
    }

    /**
     * @Route("POST", "index")
     */
    public function add()
    {
        $response = new JSONResponse();
        $input = $this->getInputData();
        $news = new NewsService();

        try {
            $news->add($input->title, $input->text);
            $response->setStatusCode(201);
            $response->setData(['message' => $this->__('The news was added.')]);
        } catch(ModelException $e) {
            $response->setStatusCode($e->getCode());
            $response->setData(['errors' => $e->getErrors()]);
        }

        return $response;
    }

    /**
     * @Route("PUT", "index")
     */
    public function update($id)
    {
        $response = new JSONResponse();
        $input = $this->getInputData();
        $news = new NewsService();

        try {
            $isModified = $news->update($id, $input->title, $input->text);
            $message = $isModified ? $this->__('The content was modified.') : $this->__('The content was not modified.');
            $response->setData(['message' => $message]);
        } catch(ModelException $e) {
            $response->setStatusCode($e->getCode());
            $response->setData(['errors' => $e->getErrors()]);
        }

        return $response;
    }

    /**
     * @Route("Delete", "index")
     */
    public function delete($id)
    {
        $response = new JSONResponse();
        $news = new NewsService();

        try {
            $news->delete($id);
            $response->setData(['message' => $this->__('The news was deleted.')]);
        } catch(ModelException $e){
            $response->setStatusCode($e->getCode());
            $response->setData(['errors' => $e->getErrors()]);
        }

        return $response;
    }

    /**
     * @Route("Get", "find")
     */
    public function find($id){
        $response = new JSONResponse();
        $news = new NewsService();

        try {
            $newsData = $news->findById($id);
            $response->setData($newsData);
        } catch(ModelException $e) {
            $response->setStatusCode($e->getCode());
            $response->setData(['errors' => $e->getErrors()]);
        }

        return $response;
    }
}