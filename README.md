
## How to install

### 1. Install all requirement
- Composer
- This is using laravel, so you also need to install all requirement from laravel [Laravel 5.6](https://laravel.com/docs/5.4/installation)

### 2. Install all dependency
- ```composer install```

### 3. Set Credential
- Copy .env.example to .env
- Set the credential (Database name, mysql root username, mysql password) with your own credential.
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=base-backend-game
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Run artisan migration
- ```php artisan migrate```

### 5. Install Laravel Admin Asset and config
- ```php artisan vendor:publish --provider="Encore\Admin\AdminServiceProvider"```

### 6. Insert basic seed.
- ```php artisan db:seed```

### 7. Generate key application
- ```php artisan key:generate```

### 8. Serve the application
- ```php artisan serve```

### 9. That is done.
- You can access the backend at:
```
http://localhost:8000/admin
username:admin 
password:admin
```

### 10. API
- All API documented using [postman](https://www.getpostman.com/) , all collection and environment saved in ```./postman``` at this repository.

## All API list.

#### Auth
- Login.
- Register.
- Update Setting.
- Update Profile.

#### Levels
- Set last level user.
- Get last level user.
- Get all level.

#### Session
- Get all history game of user.
- Get session history by level game.
- Finish level session.

##### Statistic
- Get player statistic.
- Set player statistic.

#### Leaderboard
- Get leaderboard.
- Get inbetween leaderboard.
- Get best score.

## License

This Project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
