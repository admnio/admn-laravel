# ADMN.io Laravel SDK

A simple wrapper for [ADMN.io](https://admn.io) API written in PHP for Laravel.

## Features

- Log action as entity (User, Customer, Employee, etc. Any Model you apply our Trait to)

## Requirements

- PHP 7+

## Installation

Via Composer.

```bash
composer require admn/admn-laravel
```

## Model Configuration

```php
<?php 

namspace App;

class User extends Authenticatable {
    ...
    use \Admn\Admn\PerformsActions;
    ...
        
    /**
     * How we display the entity in our interface 
     * @return string
     */
    protected function getAuditDisplayValue()
    {
        return $this->name;
    }
    
    /**
    * Key used to identify the entity in our platform 
    * @return string
    */
    protected function getAuditIdentifierKey()
    {
         return 'email';
    }
    
    /**
     * Value used to identify the entity in our platform 
     * @return string|int
     */
    protected function getAuditIdentifierValue()
    {
        return $this->email;
    }
}

```

## Usage

```php
    $user = User::find(1);

    $user->logAction('Updated post title',['post:123'],['title' => 'My new title']);

    //OR in PHP 8.0+
    $user->logAction(
        action: 'Updated post title',
        tags: [
            'post:123'
        ],
        context: [
            'title' => 'My new title'
        ]
    );
```
