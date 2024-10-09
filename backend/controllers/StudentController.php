<?php

namespace backend\controllers;

use common\components\Gadget;
use common\components\Jdf;
use common\models\School;
use common\models\Student;
use common\models\StudentSearch;
use common\models\User;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * StudentController implements the CRUD actions for Student model.
 */
class StudentController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Student models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new StudentSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Student model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Updates an existing Student model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $user = User::findOne(['id' => $id]);
        $model = Student::findOne(['user_id' => $id]);
        $schoolList = ArrayHelper::map(School::find()->all(), 'id', 'name');

        if ($this->request->isPost && $model->load($this->request->post())
            && $user->load($this->request->post())) {

            $model->birthdate = Gadget::JalaliDateToTimeStamp($model->birthdate);
            $model->updated_at = Jdf::jmktime();

            $transaction = \Yii::$app->db->beginTransaction();

            try {
                if ($model->validate() && $user->validate()) {
                    $user->username = $user->mobile;
                    $user->setPassword($user->mobile);

                    if ($model->save() && $user->save()) {
                        $transaction->commit();
                        return $this->redirect(['/site/index']);
                    }
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->session->setFlash('danger', 'خطای غیر منتظره‌ای رخ داده است');
            }

        }

        return $this->render('update', [
            'user' => $user,
            'model' => $model,
            'schoolList' => $schoolList,
        ]);
    }

    /**
     * Deletes an existing Student model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $user = User::findOne(['id' => $id]);
        $student = Student::findOne(['user_id' => $id]);

        $user->status = User::STATUS_DELETED;

        $transaction = \Yii::$app->db->beginTransaction();
        if ($user->save() && $student->delete()) {
            $transaction->commit();
        }else {
            $transaction->rollBack();
        }

        return $this->redirect(['/site/index']);
    }

    /**
     * Finds the Student model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Student the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Student::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }
}
