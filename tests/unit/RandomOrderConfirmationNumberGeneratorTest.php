<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RandomOrderConfirmationNumberGeneratorTest extends TestCase
{
    // Must be 24 characters long
    // Can only contain uppercase letters and numbers
    // Cannot contain ambiguous characters
    // All order confirmation numbers must be unique
    //
    // ABCDEFGHJKLMNPQRSTUVWXYZ
    // 23456789
}
