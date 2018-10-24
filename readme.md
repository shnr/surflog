# Surflog

Surflog is a web application for take a log for your surf session.  
The application using Vue.js for frontend, and API using Laravel.  
Actually this project is just for myself, however I think it would be great if it helps your life makes better. 


## Description

There is some basic features.

- authentication/authorization
- REST API
- Relational DB
- Pagenation
- Image uplaod and resizing


These features are in progress.

- Social Login  
- Share your session via social  
- Auto post to wordpress  
- Toppage animation  


## Demo

[https://surflog.shnr.net/](https://surflog.shnr.net/)


## Requirement

The API using Laravel, so it depends on Laravel server requirements.

- PHP >= 7.1.3
- OpenSSL PHP Extension
- PDO PHP Extension
- Mbstring PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
- Ctype PHP Extension
- JSON PHP Extension




## Install

At first, clone it.

```
git clone https://username@github.com/shnr/surflog.git
```

It need to be create .env.
Duplicate .envsamne, and edit it.

```
cp .envsample .env
vim .env
```

Make storage/cache directory permission writable.

```
sudo chmod -R 777 storage
sudo chmod -R 777 bootstrap/cache
```

Generate keys.

```
php artisan key:generate
php artisan passport:keys
```

Also, upload image directory need to be writable too.

```
sudo chmod -R 777 public/uploads/
```



## Development


## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.


## License
[MIT](https://choosealicense.com/licenses/mit/)

