<?php

namespace common\models;

use common\components\Gadget;
use Yii;
use yii\base\Model;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $name;
    public $lastname;
    public $mobile;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'نام کاربری وارد شده تکراری است.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['name', 'trim'],
            ['name', 'required'],
            ['name', 'string', 'min' => 2, 'max' => 255],

            ['lastname', 'trim'],
            ['lastname', 'required'],
            ['lastname', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'ایمیل وارد شده تکراری است.'],

            ['mobile', 'trim'],
            ['mobile', 'required'],
            ['mobile', 'string', 'min' => 11, 'max' => 11],
            ['mobile', 'unique', 'targetClass' => '\common\models\User', 'message' => 'شماره موبایل وارد شده تکراری است.'],
            ['mobile', 'validateMobile'],

            ['password', 'required'],
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('app', 'نام کاربری'),
            'name' => Yii::t('app', 'نام'),
            'lastname' => Yii::t('app', 'نام خانوادگی'),
            'email' => Yii::t('app', 'ایمیل'),
            'mobile' => Yii::t('app', 'شماره موبایل'),
            'password' => Yii::t('app', 'رمز عبور'),
        ];
    }


    public function validateMobile($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $this->mobile = Gadget::convertToEnglish($this->mobile);
            if (is_numeric($this->mobile)){
                $numbers = str_split($this->mobile);
                if (count($numbers) != 11 || $numbers[0] != 0 || $numbers[1] != 9) {
                    $this->addError($attribute, 'شماره موبایل وارد شده صحیح نمی‌باشد');
                }
            }else{
                $this->addError($attribute, 'شماره موبایل وارد شده صحیح نمی‌باشد');
            }
        }
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function signup($role, $login = false)
    {
        $this->username = $this->mobile;
        $this->password = $this->mobile;

        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->name = $this->name;
        $user->lastname = $this->lastname;
        $user->username = $this->username;
        $user->email = $this->email;
        $user->mobile = $this->mobile;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();

        if ($user->save()){
            $auth = Yii::$app->authManager;

            $item = $auth->getRole($role);
            $auth->assign($item, $user->getId());

            if ($login) {
                $userLogin = new LoginForm();

                $userLogin->username = $this->mobile;
                $userLogin->password = $this->mobile;

                return $userLogin->login();
            }

            return true;
        }
        return false;

//        return $user->save() && $this->sendEmail($user);
    }


    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail($user)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();
    }
}
