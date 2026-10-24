<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

/**
 * Build an uploaded PNG without needing the GD extension.
 *
 * `UploadedFile::fake()->image()` draws the image with GD, which is not enabled
 * in every PHP build. This writes real PNG bytes instead, optionally padded so
 * a test can exercise the max file size rule.
 */
function fakeImageUpload(string $name = 'product.png', int $sizeInKilobytes = 10): UploadedFile
{
    $png = base64_decode(
        'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg=='
    );

    $path = tempnam(sys_get_temp_dir(), 'pest-image').'.png';

    // Padding after the IEND chunk keeps the file a valid PNG to MIME detection.
    file_put_contents($path, $png.str_repeat("\0", max(($sizeInKilobytes * 1024) - strlen($png), 0)));

    return new UploadedFile($path, $name, 'image/png', null, true);
}
