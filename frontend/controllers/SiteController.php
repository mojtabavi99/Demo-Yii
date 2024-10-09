<?php

namespace frontend\controllers;

use common\components\Gadget;
use common\components\Jdf;
use common\models\LoginForm;
use common\models\School;
use common\models\SignupForm;
use common\models\Student;
use common\models\User;
use Yii;
use yii\captcha\CaptchaAction;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\ErrorAction;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => ErrorAction::class,
            ],
            'captcha' => [
                'class' => CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/signup']);
        }

        return $this->render('index');
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $userModel = new SignupForm();
        $studentModel = new Student();

        $schoolList = ArrayHelper::map(School::find()->all(), 'id', 'name');

        if ($userModel->load(Yii::$app->request->post()) && $studentModel->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $studentModel->birthdate = Gadget::JalaliDateToTimeStamp($studentModel->birthdate);

                if ($userModel->signup(User::ROLE_STUDENT, true)) {
                    if ($studentModel->registerStudent(Yii::$app->user->id)) {
                        $transaction->commit();
                        return $this->redirect(['/site/index']);
                    }else {
                        $transaction->rollBack();
                    }
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->session->setFlash('danger', 'خطای غیر منتظره‌ای رخ داده است');
            }
        }

        return $this->render('signup', [
            'userModel' => $userModel,
            'studentModel' => $studentModel,
            'schoolList' => $schoolList,
        ]);
    }
}
