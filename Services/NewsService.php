<?php

namespace Services;

use Core\Base;
use Core\Common;
use Core\Exceptions\ModelException;
use Models\NewsModel;

class NewsService extends  Base
{
    /**
     * @var NewsModel
     */
    private $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new NewsModel();
    }

    /**
     * @param null $limit
     * @param int $start
     * @return array
     */
    public function all($limit = NULL, $start = 0)
    {
        return $this->model->all($limit, $start);
    }

    /**
     * @param $title
     * @param $text
     * @throws ModelException
     */
    public function add($title, $text)
    {
        $errors = [];
        $title = htmlspecialchars(trim($title));
        $text = htmlspecialchars(trim($text));
        if (!Common::validateStringLength($title, 1, 60)) {
            $errors[] = $this->__('The field "title" length must be between 1 and 60.');
        }
        if (!Common::validateStringLength($text, 1)) {
            $errors[] = $this->__('The field "text" length must be atleast 1 character.');
        }

        if (!empty($errors)) {
            throw new ModelException($errors);
        }
        $news = new NewsModel();
        $time = time();
        $news->add(['title' => $title, 'text' => $text, 'date' => $time, 'updated' => $time]);
    }

    /**
     * @param $id
     * @param $title
     * @param $text
     * @throws ModelException
     */
    public function  update($id, $title, $text)
    {
        $title = htmlspecialchars(trim($title));
        $text = htmlspecialchars(trim($text));
        if (!$id) {
            throw new ModelException([$this->__('The id is missing.')]);
        }
        $news = new NewsModel();

        if (!$news->findById($id)) {
            throw new ModelException([$this->__('The given ID does not exist.')], 404);
        }

        $errors = [];
        $update = [];
        if (Common::validateStringLength($title, NULL, 60)) {
            if (Common::validateStringLength($title, 1)) {
                $update['title'] = $title;
            }
        } else {
            $errors[] = $this->__('The field "title" length must be max 60 characters.');
        }
        if (Common::validateStringLength($text, 1)) {
            $update['text'] = $text;
        }

        if($errors) {
            throw new ModelException($errors);
        }

        if($update) {
            $update['id'] = $id;
            $update['updated'] = time();
            $news->update($update);
            return true;
        }

    }

    /**
     * @param $id
     * @throws ModelException
     */
    public function delete($id){
        $news = new NewsModel();

        if (!$news->findById($id)) {
            throw new ModelException([$this->__('The given ID does not exist.')], 404);
        }

        $news->delete($id);
    }

    /**
     * @param $id
     * @return mixed
     * @throws ModelException
     */
    public function findById($id){
        $news = new NewsModel();

        $newsData = $news->findById($id);
        if (!$newsData) {
            throw new ModelException([$this->__('The given ID does not exist.')], 404);
        }

        return $newsData;
    }
}