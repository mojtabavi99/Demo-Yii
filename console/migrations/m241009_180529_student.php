<?php

use yii\db\Migration;

/**
 * Class m241009_180529_student
 */
class m241009_180529_student extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // https://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('student', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'school_id' => $this->integer()->notNull(),
            'national_id' => $this->string()->notNull()->unique(),
            'birthdate' => $this->integer()->notNull(),
            'field_of_study' => $this->string()->notNull(),
            'grade' => $this->string()->notNull(),
            'academic_year' => $this->string()->notNull(),
            'status' => $this->string()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey(
            'student-user',
            'student',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'student-school',
            'student',
            'school_id',
            'school',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m241009_180529_student cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241009_180529_student cannot be reverted.\n";

        return false;
    }
    */
}
