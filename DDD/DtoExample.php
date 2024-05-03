<?php

//DTO's can be created using a PHP class 
class GreetingDto
{
    // We can use the constructor to specify visibility, type, and field optionality 
    public function __construct(
        public string $name,
        public int $age,
        private string $message,
        protected string $cantTouchThis = 'yolo',
        public string $favoriteColor = ''
    ) {
    }
}

//Creating DTO instances can be done easily using named parameters
$greeting = new GreetingDto(
    name: 'Chris',
    message: 'Hello World!',
    age: 10, //constructor order doesn't matter if you use names 
    favoriteColor: 'blue'
);

//we can see that only public fields are visible
echo json_encode($greeting) . "\n";

//Issues with the data will happen at the error level

//unable to access private fields
try {
    $greeting->message;
} catch (Error $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}

//unable to access protected fields
try {
    $greeting->cantTouchThis;
} catch (Error $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}

// typing will try to auto-cast 
$greeting = new GreetingDto(
    name: 'Chris',
    message: 'Hello World!',
    age: '5', // That's why this still works
);

echo "Exception: " . json_encode($greeting) . "\n";


// here we can see that type safety works
try {
    $greeting = new GreetingDto(
        name: 'Chris',
        message: 'Hello World!',
        age: 'some random string',
    );
} catch (Error $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
