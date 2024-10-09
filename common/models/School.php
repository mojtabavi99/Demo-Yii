<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "school".
 *
 * @property int $id
 * @property int $gender
 * @property string $name
 *
 * @property Student[] $students
 */
class School extends \yii\db\ActiveRecord
{
    CONST GENDER_MALE = 1;
    CONST GENDER_FEMALE = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'school';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gender', 'name'], 'required'],
            [['gender'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    public static function getGenderList(): array
    {
        return [
            self::GENDER_MALE => 'پسرانه',
            self::GENDER_FEMALE => 'دخترانه',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'gender' => Yii::t('app', 'جنسیت'),
            'name' => Yii::t('app', 'نام مدرسه'),
        ];
    }

    /**
     * Gets query for [[Students]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStudents()
    {
        return $this->hasMany(Student::class, ['school_id' => 'id']);
    }
}
