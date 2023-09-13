<?php
namespace App\Interfaces;

interface UserServiceInterface
{
    public function hash(string $pwd);

    public function prefixName(): array;
}