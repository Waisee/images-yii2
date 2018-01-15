<?php

use yii\db\Migration;

/**
 * Class m180111_123151_alter_user_table
 */
class m180111_123151_alter_user_table extends Migration
{

    public function up()
    {
        $this->addColumn('{{%user}}', 'about', $this->text());
        $this->addColumn('{{%user}}', 'type', $this->integer());
        $this->addColumn('{{%user}}', 'nickname', $this->string());
        $this->addColumn('{{%user}}', 'picture', $this->string());
    }

    public function down()
    {
        $this->dropColumn('{{%user}}', 'about');
        $this->dropColumn('{{%user}}', 'type');
        $this->dropColumn('{{%user}}', 'nickname');
        $this->dropColumn('{{%user}}', 'picture');
    }

}
