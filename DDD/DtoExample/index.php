<?php

//DTO's can be created using a PHP class 
class GreetingDto {
    // We can use the constructor to specify visibility, type, and field optionality 
    public function __construct(
        public string $name,
        public int $age,
        private string $message,
        protected string $cantTouchThis = 'yolo',
        public string $favoriteColor = ''
    ) 
    {
        
    }
}

//Creating DTO instances can be done easily using named parameters
$greeting = new GreetingDto(
    name: 'Chris',
    message: 'Hello World!',
    age: 10, //constructor order doesn't matter if you use names 
    favoriteColor: 'blue'
);

print(json_encode($greeting));

$greeting->message;

$greeting->cantTouchThis;
