<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Intervention\Image\Facades\Image;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessPosterImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $concert;

    public function __construct($concert)
    {
        $this->concert = $concert;
    }

    public function handle()
    {
        $imageContents = Storage::disk('public')->get($this->concert->poster_image_path);

        $image = Image::make($imageContents)->resize(600, null, function ($constraint) {
            $constraint->aspectRatio();
        })->limitColors(255)->encode();

        Storage::disk('public')->put($this->concert->poster_image_path, (string) $image);
    }
}
