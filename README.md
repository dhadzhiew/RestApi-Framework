# Rest API Framework (MVC)

## Install
 - Set the db configuration in /congif/database.php
 - Run /public/install.php
 
##Examples
 - Request: GET public/news
 - Response: [{ "id": 1, "title": 'Title', "text": 'Text' }, ...]
 - Request: GET public/news/1
 - Response: { "id": 1, "title": 'Title', "text": 'Text' }
 - Request: POST public/news
 - Response: {"message":"The news was added."}
