<?php 

namespace MyModule\Service;

class DataService
{
    public function getData(): string
    {
        dump('DataService called');
        return 'Data from DataService';
    }
}