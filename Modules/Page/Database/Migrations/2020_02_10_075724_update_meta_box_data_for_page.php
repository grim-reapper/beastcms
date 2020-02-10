<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Page\Entities\Page;

class UpdateMetaBoxDataForPage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('meta_data')->where('reference_type', 'page')->update(['reference_type' => Page::class]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('meta_data')->where('reference_type', Page::class)->update(['reference_type' => 'page']);
    }
}
