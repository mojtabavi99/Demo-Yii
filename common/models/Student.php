<?php

namespace common\models;

use common\components\Gadget;
use common\components\Jdf;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "student".
 *
 * @property int $id
 * @property int $user_id
 * @property int $school_id
 * @property string $national_id
 * @property int $birthdate
 * @property string $field_of_study
 * @property string $grade
 * @property string $academic_year
 * @property string $status
 * @property int $created_at
 * @property int $updated_at
 * @property string $name
 * @property string $lastname
 *
 * @property User $user
 */
class Student extends ActiveRecord
{
    const FIELD_NONE = 'none'; // سایر موارد
    const FIELD_HUMANITIES = 'humanities'; // انسانی
    const FIELD_MATHEMATICS = 'mathematics'; // ریاضی
    const FIELD_SCIENCES = 'sciences'; // تجربی
    const FIELD_TECHNICAL = 'technical'; // فنی و حرفه‌ای
    const FIELD_ARTS = 'arts'; // هنر

    const GRADE_ELEMENTARY_SCHOOL = 'elementary school'; // دبستان
    const GRADE_MIDDLE_SCHOOL = 'middle school'; // راهنمایی
    const GRADE_HIGH_SCHOOL = 'high school'; // دبیرستان

    const STATUS_REJECTED = 'rejected'; // رد شده
    const STATUS_APPROVED = 'approved'; // تایید شده
    const STATUS_CANCELLED = 'cancelled'; // انصراف داده
    const STATUS_PENDING = 'pending'; // در انتظار پرداخت
    const STATUS_COMPLETION = 'completion'; // تکمیل پرونده
    const STATUS_REGISTERED = 'registered'; // ثبت اطلاعات


    // only for filtering
    public $name;
    public $lastname;

    public static function getFields(): array
    {
        return [
            self::FIELD_NONE => 'سایر موارد',
            self::FIELD_HUMANITIES => 'انسانی',
            self::FIELD_MATHEMATICS => 'ریاضی',
            self::FIELD_SCIENCES => 'تجربی',
            self::FIELD_TECHNICAL => 'فنی و حرفه‌ای',
            self::FIELD_ARTS => 'هنر',
        ];
    }

    public static function getGrades(): array
    {
        return [
            self::GRADE_ELEMENTARY_SCHOOL => 'دبستان',
            self::GRADE_MIDDLE_SCHOOL => 'راهنمایی',
            self::GRADE_HIGH_SCHOOL => 'دبیرستان',
        ];
    }

    public static function getStatus(): array
    {
        return [
            self::STATUS_REGISTERED => 'ثبت اطلاعات',
            self::STATUS_APPROVED => 'تایید شده',
            self::STATUS_REJECTED => 'رد شده',
            self::STATUS_CANCELLED => 'انصراف داده',
            self::STATUS_COMPLETION => 'تکمیل پرونده',
            self::STATUS_PENDING => 'در انتظار پرداخت',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'student';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'school_id', 'national_id', 'birthdate', 'field_of_study', 'grade', 'academic_year', 'created_at', 'updated_at'], 'required'],
            [['user_id', 'school_id', 'birthdate', 'created_at', 'updated_at'], 'integer'],
            [['national_id', 'field_of_study', 'grade', 'academic_year', 'status'], 'string', 'max' => 255],
            [['national_id'], 'unique'],
            ['status', 'default', 'value' => self::STATUS_REGISTERED],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            ['national_id', 'validateNationalID'],

            [['name', 'lastname'], 'string', 'max' => 255],

        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'national_id' => Yii::t('app', 'کد ملی'),
            'birthdate' => Yii::t('app', 'تاریخ تولد'),
            'field_of_study' => Yii::t('app', 'رشته تحصیلی'),
            'grade' => Yii::t('app', 'پایه تحصیلی'),
            'school_id' => Yii::t('app', 'مدرسه'),
            'academic_year' => Yii::t('app', 'سال تحصیلی'),
            'status' => Yii::t('app', 'وضعیت'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),

            'name' => Yii::t('app', 'نام'),
            'lastname' => Yii::t('app', 'نام خانوادگی'),
        ];
    }


    public function validateNationalID($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $this->national_id = Gadget::convertToEnglish($this->national_id);
            if (!preg_match('/^[0-9]{10}$/', $this->national_id)) {
                $this->addError($attribute, 'کد ملی وارد شده صحیح نمی‌باشد');
            }

            for ($i = 0; $i < 10; $i++) {
                if (preg_match('/^' . $i . '{10}$/', $this->national_id))
                    $this->addError($attribute, 'کد ملی وارد شده صحیح نمی‌باشد');
            }

            for ($i = 0, $sum = 0; $i < 9; $i++)
                $sum += ((10 - $i) * intval(substr($this->national_id, $i, 1)));

            $ret = $sum % 11;
            $parity = intval(substr($this->national_id, 9, 1));
            if (!($ret < 2 && $ret == $parity) && !($ret >= 2 && $ret == 11 - $parity))
                $this->addError($attribute, 'کد ملی وارد شده صحیح نمی‌باشد');
        }
    }

    public function registerStudent($user_id)
    {
        $this->user_id = $user_id;
        $this->created_at = Jdf::jmktime();
        $this->updated_at = Jdf::jmktime();

        if (!$this->validate()) {
            return null;
        }

        if ($this->save()) {
            return true;
        }

        return false;
    }

    /**
     * Gets query for [[User]].
     *
     * @return yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Gets query for [[School]].
     *
     * @return yii\db\ActiveQuery
     */
    public function getSchool()
    {
        return $this->hasOne(School::class, ['id' => 'school_id']);
    }
}
