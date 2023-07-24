<?php

use App\Models\Travel;

it('can generate unique slugs', function () {
    // Create 3x travel with the same name
    $travel = Travel::factory(3)->create([
        'name' => 'This is a test name',
    ]);

    // Slugs should be different!
    expect($travel[0]->slug)->toEqual('this-is-a-test-name');
    expect($travel[1]->slug)->toEqual('this-is-a-test-name-1');
    expect($travel[2]->slug)->toEqual('this-is-a-test-name-2');
});
