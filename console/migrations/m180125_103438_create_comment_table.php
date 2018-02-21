<?php

use yii\db\Migration;

/**
 * Handles the creation of table `comment`.
 */
class m180125_103438_create_comment_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('comment', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'post_id' => $this->integer()->notNull(),
            'text_comment' => $this->text()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('comment');
    }
}
