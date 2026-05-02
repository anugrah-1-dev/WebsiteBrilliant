<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVideoUrlToGalleryImagesTable extends Migration
{
    public function up()
    {
        Schema::table('gallery_images', function (Blueprint $table) {
            $table->string('type')->default('image')->after('gallery_id'); // 'image' atau 'video'
            $table->string('video_url')->nullable()->after('image_path');  // URL YouTube
            $table->string('caption')->nullable()->after('video_url');
            $table->string('image_path')->nullable()->change();            // jadikan nullable
        });
    }

    public function down()
    {
        Schema::table('gallery_images', function (Blueprint $table) {
            $table->dropColumn(['type', 'video_url', 'caption']);
            $table->string('image_path')->nullable(false)->change();
        });
    }
}
