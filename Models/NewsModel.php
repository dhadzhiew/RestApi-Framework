<?php

namespace Models;

use Core\BaseModel;

class NewsModel extends BaseModel
{
    public function __construct($fields = ['id', 'title', 'text','date', 'updated'])
    {
        parent::__construct('news', $fields);
    }
}