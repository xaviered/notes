<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNoteTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::create( 'note_tags', function( Blueprint $table ) {
		    $table->integer( 'note_id' )->unsigned();
		    $table->string( 'tag_id' );

		    $table->primary(['note_id', 'tag_id'])
			    ->foreign()
		    ;
//		    $table->foreign(['note_id', 'tag_name'])
//			    ->references( ['note_id', 'tag_name'] )
//			    ->on( ['notes', 'tags'] )
//		    ;
	    } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::dropIfExists( 'note_tags' );
    }
}
