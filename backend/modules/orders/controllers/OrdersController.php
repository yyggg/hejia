<?php

namespace app\modules\orders\controllers;


use app\modules\staff\models\Staff;
use app\modules\staff\models\StaffSearch;
use Yii;
use app\modules\orders\models\Orders;
use app\modules\orders\models\OrdersSearch;
use app\components\CommonController;
use yii\web\NotFoundHttpException;

/**
 * OrdersController implements the CRUD actions for Orders model.
 */
class OrdersController extends CommonController
{

    /**
     * Lists all Orders models.
     * @return mixed
     */
    //托服订单列表
    public function actionIndex()
    {
        $searchModel = new OrdersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andFilterWhere(['type'=>0]);
        //限制跨校区操作
        $dataProvider = $this->schoolRule($dataProvider);

        return $this->render('/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    //活动订单列表
    public function actionAtvIndex()
    {
        $searchModel = new OrdersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andFilterWhere(['type'=>1]);
        //限制跨校区操作
        $dataProvider = $this->schoolRule($dataProvider);

        return $this->render('/atv-index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    //已过期订单列表
    public function actionExpiredIndex()
    {
        $searchModel = new OrdersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['<','etime',time()]);
        //限制跨校区操作
        $dataProvider = $this->schoolRule($dataProvider);

        return $this->render('/expired-index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Orders model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = Orders::find()->joinWith('staff')->where([Orders::tableName().'.'.'id'=>$id])->one();
        return $this->render('/view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Orders model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Orders();
        $data = Yii::$app->request->post();

        if ($model->load($data)) {
            $tmp = Orders::find()
                ->where(['student_id'=>$model->student_id])
                ->andWhere(['product_id'=>$model->product_id])
                ->andWhere(['patriarch_name'=>$model->patriarch_name])
                ->andWhere(['>','stime',strtotime(date("Y-m-d"))])
                ->andWhere(['<','etime',strtotime(date("Y-m-d"))+86400])
                ->andWhere(['type'=>0])
                ->one();
            if($tmp)
            {
                Yii::$app->session->setFlash('error', ['delay'=>3000,'message'=>'保存失败,同一个订单已经存在']);
                return $this->render('/create', [
                    'model' => $model,
                ]);
            }

            $model->principal = Yii::$app->user->identity->name;
            $model->stime = strtotime($data['Orders']['stime']);
            $model->etime = strtotime($data['Orders']['etime']);
            //print_r($model);die;
            if($model->save())
            {
                Yii::$app->session->setFlash('success', ['delay'=>3000,'message'=>'保存成功！']);
                return $this->redirect(['index']);
            }
            Yii::$app->session->setFlash('error', ['delay'=>3000,'message'=>'保存失败！']);

        }
        return $this->render('/create', [
            'model' => $model,
        ]);

    }

    //选择服务人员
    public function actionSelectTeacher()
    {
        $searchModel = new StaffSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andFilterWhere(['in','position',['老师','校长']]);


        return $this->renderAjax('/teacher-list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Updates an existing Orders model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $staff = Staff::findOne($model->teacher_id);
        $model->teacher_name = $staff ? $staff->name : '';
        $model->stime = date('Y-m-d', $model->stime);
        $model->etime = date('Y-m-d', $model->etime);
        $data = Yii::$app->request->post();

        if ($model->load($data)) {
            $model->principal = Yii::$app->user->identity->name;
            $model->stime = strtotime($data['Orders']['stime']);
            $model->etime = strtotime($data['Orders']['etime']);
            //print_r($data);
            //print_r($model);die;
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('/update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Orders model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Orders model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Orders the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Orders::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
