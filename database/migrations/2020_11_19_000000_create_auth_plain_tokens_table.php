
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthPlainTokensTable extends Migration
{
    public function up()
    {
        Schema::create(config('awemapl-auth.tables.plain_tokens'), function (Blueprint $table) {
            $table->id();
            $table->text('plain_token');
            $table->timestamps();
        });
        Schema::table(config('awemapl-auth.tables.plain_tokens'), function (Blueprint $table) {
            $table->foreignId('token_id')->constrained('personal_access_tokens')->onDelete('cascade');;
        });

    }

    public function down()
    {
        Schema::table(config('awemapl-auth.tables.plain_tokens'), function (Blueprint $table) {
            $table->dropForeign(['token_id']);
        });
        Schema::drop(config('awemapl-auth.tables.plain_tokens'));
    }
}
