<?php

use yii\db\Migration;

/**
 * Class m180325_094935_alter_table_post_add_column_complaints
 */
class m180325_094935_alter_table_post_add_column_complaints extends Migration
{
    
    public function up()
    {
        $this->addColumn('{{%post}}', 'complaints', $this->integer());
    }

    public function down()
    {
        $this->dropColumn('{{%post}}', 'complaints');
    }
    
}
